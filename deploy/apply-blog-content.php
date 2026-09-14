<?php
/**
 * Apply a reviewed content bundle via WordPress, preserving embeds and revisions.
 * php apply-blog-content.php /path/to/wordpress /path/to/bundle [--apply]
 * Defaults to a read-only preflight. Every post is checked before any write.
 */
if (PHP_SAPI !== 'cli' || $argc < 3 || $argc > 4 || ($argc === 4 && $argv[3] !== '--apply')) {
    fwrite(STDERR, "Usage: php apply-blog-content.php WORDPRESS_ROOT BUNDLE [--apply]\n");
    exit(2);
}
require rtrim($argv[1], '/') . '/wp-load.php';
$bundle = rtrim($argv[2], '/');
$apply = isset($argv[3]);
$changed = array();

try {
    $manifest = json_decode(file_get_contents($bundle . '/manifest.json'), true, 512, JSON_THROW_ON_ERROR);
    $ready = array();
    $seen = array();
    foreach ($manifest['posts'] as $row) {
        $post = get_post($row['ID']);
        if (!$post || $post->post_type !== 'post' || $post->post_status !== 'publish'
            || $post->post_name !== $row['slug'] || get_permalink($post) !== $row['url']
            || isset($seen[$post->ID])) {
            throw new RuntimeException('Post identity/status mismatch: ' . $row['ID']);
        }
        $seen[$post->ID] = true;
        $content = file_get_contents($bundle . '/' . basename($row['file']));
        if ($content === false || hash('sha256', $content) !== $row['after_sha256']) {
            throw new RuntimeException('Bundle checksum mismatch: ' . $post->ID);
        }
        $current_hash = hash('sha256', $post->post_content);
        if ($current_hash !== $row['after_sha256'] && $current_hash !== $row['before_sha256']) {
            throw new RuntimeException('Post changed since backup; rebase edits first: ' . $post->ID);
        }
        $before_meta = array();
        $meta_already = true;
        foreach ($row['before_meta'] as $key => $expected) {
            $before_meta[$key] = get_post_meta($post->ID, $key, false);
            $updated = array_key_exists($key, $row['meta_updates']) ? array($row['meta_updates'][$key]) : null;
            if ($updated !== null && $before_meta[$key] === $updated) {
                continue;
            }
            if ($updated !== null) {
                $meta_already = false;
            }
            if ($before_meta[$key] !== $expected) {
                throw new RuntimeException('SEO metadata changed since backup: ' . $post->ID . ' ' . $key);
            }
        }
        $already_applied = $current_hash === $row['after_sha256'] && $meta_already;
        $schema = wordiva_faq_schema_from_content($content);
        $faq_count = $schema ? count($schema['mainEntity']) : 0;
        if ($faq_count !== $row['faq_count']) {
            throw new RuntimeException('FAQ parser count mismatch: ' . $post->ID);
        }
        $enable_faq = $row['meta_updates']['_wordiva_enable_faq_schema']
            ?? get_post_meta($post->ID, '_wordiva_enable_faq_schema', true);
        if ($faq_count && !$enable_faq) {
            throw new RuntimeException('FAQ schema is disabled: ' . $post->ID);
        }
        if (strpos($content, ')https:') !== false || serialize_blocks(parse_blocks($content)) !== $content) {
            throw new RuntimeException('Malformed link or block serialization: ' . $post->ID);
        }
        $ready[] = array('row' => $row, 'content' => $content, 'post' => clone $post,
            'meta' => $before_meta, 'skip' => $already_applied);
        printf("Preflight %d: %d FAQs, %s\n", $post->ID, $faq_count, $already_applied ? 'already applied' : 'ready');
    }
    if (!$apply) {
        echo "Dry run passed. No posts changed.\n";
        exit(0);
    }

    // The bundle contains trusted existing Gutenberg HTML (including video/iframe
    // embeds) and reviewed additions. Anonymous-user KSES must not strip embeds.
    kses_remove_filters();
    foreach ($ready as $item) {
        if ($item['skip']) {
            continue;
        }
        $post = $item['post'];
        $changed[] = $item;
        if (hash('sha256', $post->post_content) !== $item['row']['after_sha256']) {
            wp_save_post_revision($post->ID);
            $result = wp_update_post(wp_slash(array('ID' => $post->ID, 'post_content' => $item['content'])), true);
            if (is_wp_error($result)) {
                throw new RuntimeException($result->get_error_message());
            }
        }
        foreach ($item['row']['meta_updates'] as $key => $value) {
            update_post_meta($post->ID, $key, wp_slash($value));
        }
        clean_post_cache($post->ID);
        if (get_post_field('post_content', $post->ID, 'raw') !== $item['content']) {
            throw new RuntimeException('Saved content differs from reviewed content: ' . $post->ID);
        }
        foreach ($item['row']['meta_updates'] as $key => $value) {
            if (get_post_meta($post->ID, $key, true) !== $value) {
                throw new RuntimeException('Saved metadata mismatch: ' . $post->ID);
            }
        }
        printf("Updated %d: %s\n", $post->ID, $item['row']['url']);
    }
    printf("Applied %d post updates.\n", count($changed));
} catch (Throwable $error) {
    fwrite(STDERR, $error->getMessage() . "\n");
    foreach (array_reverse($changed) as $item) {
        $post = $item['post'];
        wp_update_post(wp_slash(array('ID' => $post->ID, 'post_content' => $post->post_content)), true);
        foreach ($item['row']['meta_updates'] as $key => $unused) {
            delete_post_meta($post->ID, $key);
            foreach ($item['meta'][$key] as $value) {
                add_post_meta($post->ID, $key, wp_slash($value));
            }
        }
        clean_post_cache($post->ID);
        $restored = get_post_field('post_content', $post->ID, 'raw') === $post->post_content;
        foreach ($item['row']['meta_updates'] as $key => $unused) {
            $restored = $restored && get_post_meta($post->ID, $key, false) === $item['meta'][$key];
        }
        fwrite(STDERR, ($restored ? 'Restored ' : 'RESTORE FAILED for ') . $post->ID . "\n");
    }
    exit(1);
}

<?php
/**
 * Self-check for wordiva_fix_glued_hrefs (run: php deploy/test-glued-hrefs.php).
 * Extracts the function from functions.php so there is no duplicated logic.
 */
function esc_url($url) { return $url; }

$src = file_get_contents(__DIR__ . '/../wordiva-blog-theme/functions.php');
preg_match('/^function wordiva_fix_glued_hrefs.*?^}/ms', $src, $m) || exit("function not found\n");
eval($m[0]);

$cases = array(
    // Glued: keep the first URL only.
    array(
        'href="https://wordiva.ai/blog/ai-content-marketing/automate-blog-content-without-losing-quality)https:/wordiva.ai/blog/ai-content-marketing/blog-seo-automationarticle"',
        'href="https://wordiva.ai/blog/ai-content-marketing/automate-blog-content-without-losing-quality"',
    ),
    array('href="https://wordiva.ai/a http://wordiva.ai/b"', 'href="https://wordiva.ai/a"'),
    // Untouched.
    array('href="https://wordiva.ai/blog/post/"', 'href="https://wordiva.ai/blog/post/"'),
    array('href="/blog/post/"', 'href="/blog/post/"'),
    array('href="#faq"', 'href="#faq"'),
    array('href="https://wordiva.ai/r?url=https://example.com"', 'href="https://wordiva.ai/r?url=https://example.com"'),
    array('<p>no links here</p>', '<p>no links here</p>'),
);

foreach ($cases as $i => $case) {
    $got = wordiva_fix_glued_hrefs($case[0]);
    if ($got !== $case[1]) {
        printf("FAIL case %d\n  expected: %s\n  got:      %s\n", $i, $case[1], $got);
        exit(1);
    }
}
echo "ok: " . count($cases) . " cases\n";

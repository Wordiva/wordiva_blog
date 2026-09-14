<?php
/**
 * Self-check for leaked task-title stripping (run: php deploy/test-leaked-tasks.php).
 */
$src = file_get_contents(__DIR__ . '/../wordiva-blog-theme/inc/seo-helpers.php');
foreach (array(
    'wordiva_leaked_task_pattern',
    'wordiva_text_has_leaked_task_payload',
    'wordiva_title_is_leaked_task',
    'wordiva_strip_leaked_task_payloads',
) as $name) {
    if (!preg_match('/^function ' . $name . '.*?^}/ms', $src, $m)) {
        fwrite(STDERR, "function not found: {$name}\n");
        exit(1);
    }
    eval($m[0]);
}

$keep = 'A B2B content calendar maps buyer pain to a weekly publishing plan.';
$cases = array(
    array($keep, $keep, false),
    array('%task hello calendar', 'hello calendar', true),
    array('+task (routed by content-calendar-dispatch hellomedspa)', '', true),
    array('Intro. content-calendar-dispatch payload here. Keep this.', 'Intro. payload here. Keep this.', true),
    array('Plan for hellomedspa clients leaked', 'Plan for clients leaked', true),
    array('B2B Content Calendar: Free 13-Column Template', 'B2B Content Calendar: Free 13-Column Template', false),
    array('href="https://wordiva.ai/blog/post/"', 'href="https://wordiva.ai/blog/post/"', false),
);

foreach ($cases as $i => $case) {
    list($input, $expected, $has_leak) = $case;
    if ((bool) wordiva_text_has_leaked_task_payload($input) !== $has_leak) {
        fwrite(STDERR, "FAIL detect case {$i}\n");
        exit(1);
    }
    $got = wordiva_strip_leaked_task_payloads($input);
    if ($got !== $expected) {
        fwrite(STDERR, "FAIL strip case {$i}\n  expected: {$expected}\n  got:      {$got}\n");
        exit(1);
    }
}
if (!wordiva_title_is_leaked_task('%task routed by content-calendar-dispatch')) {
    fwrite(STDERR, "FAIL title leak detection\n");
    exit(1);
}
if (wordiva_title_is_leaked_task('B2B Content Calendar: Build an Automated Plan')) {
    fwrite(STDERR, "FAIL false-positive title leak\n");
    exit(1);
}
echo 'ok: ' . count($cases) . " cases\n";

#!/usr/bin/env python3
"""Build the Sep 14 WordPress edits from captured live content, without publishing."""
import hashlib
import html
import json
from pathlib import Path

ROOT = Path(__file__).resolve().parent
OUTPUT = ROOT / "updates" / "2026-09-14"
OUTPUT.mkdir(parents=True, exist_ok=True)
SNAPSHOT = json.loads((ROOT / "backups" / "2026-09-14-posts.json").read_text())
POSTS = {post["ID"]: post for post in SNAPSHOT["posts"]}
CONTENT = {
    post_id: (ROOT / "backups" / f"{post_id}.2026-09-14.post_content.html").read_text()
    for post_id in POSTS
}


def paragraph(text):
    return f"<!-- wp:paragraph -->\n<p>{text}</p>\n<!-- /wp:paragraph -->\n\n"


def heading(text):
    return f'<!-- wp:heading -->\n<h2 class="wp-block-heading">{text}</h2>\n<!-- /wp:heading -->\n\n'


def link(post_id, text):
    return f'<a href="{POSTS[post_id]["url"]}">{text}</a>'


def before(post_id, marker, addition):
    assert CONTENT[post_id].count(marker) == 1, (post_id, marker)
    CONTENT[post_id] = CONTENT[post_id].replace(marker, addition + marker, 1)


def details(question, answer):
    return (
        '<!-- wp:details -->\n<details class="wp-block-details"><summary>'
        + html.escape(question)
        + '</summary><!-- wp:paragraph -->\n<p>'
        + answer
        + '</p>\n<!-- /wp:paragraph --></details>\n<!-- /wp:details -->'
    )


def faq_group(title, pairs):
    return (
        heading(title)
        + '<!-- wp:group {"className":"wordiva-faq","layout":{"type":"constrained"}} -->\n'
        + '<div class="wp-block-group wordiva-faq">'
        + "\n\n".join(details(*pair) for pair in pairs)
        + '</div>\n<!-- /wp:group -->\n\n'
    )


def seo(title, description, faq=None):
    payload = {
        '_wordiva_seo_title': title,
        '_wordiva_seo_description': description,
    }
    if faq is not None:
        payload['_wordiva_enable_faq_schema'] = faq
    assert len(title + ' | Wordiva') <= 60, (title, len(title + ' | Wordiva'))
    assert 20 <= len(description) <= 155, (description, len(description))
    return payload


# WP-I: restore the ranking query in the title and stop this URL cannibalizing
# "seo blog automation for content teams" (lock that query on post 188).
CONTENT[177] = CONTENT[177].replace(
    '>SEO blog automation for content teams</a>',
    '>blog SEO automation workflow</a>',
    1,
)
assert 'SEO blog automation for content teams' not in CONTENT[177]
assert 'blog SEO automation workflow' in CONTENT[177]

# WP-J: calendar already targets "b2b content calendar"; add the exact-query FAQ.
extend_marker = '<!-- /wp:details --></div>\n<!-- /wp:group -->'
assert CONTENT[231].count(extend_marker) == 1
CONTENT[231] = CONTENT[231].replace(
    extend_marker,
    '<!-- /wp:details -->\n\n'
    + details(
        'What is a B2B content calendar for SEO?',
        'A B2B content calendar is the weekly plan that maps each article to a buyer pain, '
        'a primary keyword, and a publish date so the blog can rank for terms like '
        'b2b content calendar without ad-hoc topic selection. Keep one owner, one status '
        'column, and a human approval step before anything is scheduled in the CMS.',
    )
    + '</div>\n<!-- /wp:group -->',
    1,
)

# P2: ROI title/meta plus cluster links and visible FAQs (schema toggle is already on).
before(
    245,
    heading('Common mistakes that distort B2B content ROI'),
    paragraph(
        'Keep the quality bar in the '
        + link(177, 'guide to automate blog content without losing quality')
        + '. For the search production workflow, use '
        + link(188, 'SEO blog automation for content teams')
        + '.'
    ),
)
before(
    245,
    heading('Turn content from sporadic effort into a measurable growth engine'),
    faq_group(
        'B2B Content Marketing ROI FAQ',
        [
            (
                'What is content marketing ROI for B2B?',
                'It is the business return from blog and related content: pipeline influence, '
                'qualified demo or pricing visits, sales-assisted deals, and the time saved by '
                'a repeatable publishing system. Pageviews alone are not ROI.',
            ),
            (
                'Which KPIs should a B2B blog track?',
                'Track impressions, click-through rate, ranking movement, assisted conversions, '
                'and time-to-publish per approved article. Compare similar posts over the same '
                'window, then refresh or consolidate pages that attract the wrong audience.',
            ),
            (
                'How do you connect blog content to pipeline?',
                'Give every article a commercial next step, internal links to comparison or '
                'product pages, and a monthly review of which URLs appear in demo journeys. '
                'Use assists, not last click, so educational posts are not marked as failures.',
            ),
        ],
    ),
)

META = {
    177: seo(
        'Automate Blog Content Without Losing Quality',
        'Automate blog content without losing quality: 7 steps for brand intelligence, keyword strategy, briefs, drafting, SEO, and human review.',
    ),
    222: seo(
        'AI Content Marketing Automation for B2B',
        'AI content marketing automation for B2B: 6 tasks to automate first, 4 that stay human, and a weekly model a lean team can start.',
    ),
    211: seo(
        'B2B Blog Strategy: 2-Hour Founder Workflow',
        'Build a consistent B2B blog strategy in 2 hours a week with a lean workflow for topics, SEO, publishing, and AI automation.',
    ),
    245: seo(
        'B2B Content Marketing ROI: Blog KPIs',
        'Measure B2B content marketing ROI with blog KPIs, pipeline influence, dashboards, and refresh decisions a lean team can run.',
        faq='1',
    ),
}

manifest = {'date': '2026-09-14', 'posts': []}
for post_id in (177, 222, 188, 231, 245, 211):
    post = POSTS[post_id]
    old = (ROOT / 'backups' / f'{post_id}.2026-09-14.post_content.html').read_bytes()
    new = CONTENT[post_id].encode()
    filename = f'{post_id}.post_content.html'
    (OUTPUT / filename).write_bytes(new)
    for needle in ('content-calendar-dispatch', 'hellomedspa', '%task', '+task', 'task (routed'):
        assert needle.lower() not in CONTENT[post_id].lower(), (post_id, needle)
    manifest['posts'].append({
        'ID': post_id,
        'slug': post['slug'],
        'url': post['url'],
        'before_sha256': hashlib.sha256(old).hexdigest(),
        'after_sha256': hashlib.sha256(new).hexdigest(),
        'before_meta': post['meta'],
        'meta_updates': META.get(post_id, {}),
        'file': filename,
        'faq_count': CONTENT[post_id].count('<summary>'),
    })
(OUTPUT / 'manifest.json').write_text(json.dumps(manifest, indent=2, ensure_ascii=False) + '\n')
print('Built post revisions and a guarded deployment manifest in', OUTPUT)

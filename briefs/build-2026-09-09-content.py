#!/usr/bin/env python3
"""Build the Sep 9 WordPress edits from the captured live content, without publishing."""
import hashlib
import html
import json
from pathlib import Path

ROOT = Path(__file__).resolve().parent
OUTPUT = ROOT / "updates" / "2026-09-09"
OUTPUT.mkdir(parents=True, exist_ok=True)
SNAPSHOT = json.loads((ROOT / "backups" / "2026-09-09-posts.json").read_text())
POSTS = {post["ID"]: post for post in SNAPSHOT["posts"]}
CONTENT = {
    post_id: (ROOT / "backups" / f"{post_id}.2026-09-09.post_content.html").read_text()
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


def extend_faq(post_id, question, answer):
    marker = '<!-- /wp:details --></div>\n<!-- /wp:group -->'
    assert CONTENT[post_id].count(marker) == 1
    CONTENT[post_id] = CONTENT[post_id].replace(
        marker, '<!-- /wp:details -->\n\n' + details(question, answer)
        + '</div>\n<!-- /wp:group -->', 1
    )


# WP-I: preserve the Sep 8 title experiment; supply useful in-article routes and
# answer the quality long-tail with an editorial checklist rather than a promise.
before(177, heading('Why basic AI writing tools create generic blog content'), paragraph(
    'Use this seven-step guide for the quality controls. For the search workflow, follow '
    + link(188, 'SEO blog automation for content teams') + '. To choose your first '
    'automations, see ' + link(222, 'which B2B content marketing tasks to automate')
    + ', then put approved topics into the ' + link(231, '13-column B2B content calendar') + '.'
))
extend_faq(177, 'How do you automate content creation without looking cheap?',
    'Start with a brief that names the reader, their problem, your point of view, and the evidence '
    'the article needs. Give the draft approved product facts and a real example. Before publishing, '
    'verify claims, remove filler and repeated phrases, and check the voice against your own writing. '
    'Keep a human approval step; hold any draft that lacks useful detail or reliable sources.')

# WP-J: answer-first B2B automation summary and visible FAQs; use the existing
# six-task/four-review-step title because that experiment started only yesterday.
CONTENT[222] = heading('AI content marketing automation for B2B: what to automate first') + paragraph(
    'Start with six repeatable tasks: keyword discovery and clustering, topic planning, content '
    'briefs, first drafts, on-page SEO checks, and metadata, formatting, and publishing. Keep '
    'positioning, product accuracy, proprietary insight, and final approval with a human reviewer. '
    'Begin with one article, check each handoff, and expand the workflow after it meets your quality standards.'
) + CONTENT[222]
before(222, heading('What Still Needs Human Review'), paragraph(
    'Turn these tasks into a weekly plan with the ' + link(231, 'B2B content calendar template')
    + '. Use the guide to ' + link(188, 'SEO blog automation for content teams')
    + ' for keyword-to-publishing handoffs, and the '
    + link(177, 'seven-step workflow to automate blog content without losing quality')
    + ' for editorial approval checks.'
))
before(222, heading('Final Takeaway: Use AI to Scale the System, Not Replace the Strategy'), faq_group(
    'B2B AI Content Marketing FAQ', [
        ('What is AI content marketing automation for B2B?',
         'It is a connected workflow that uses AI and software to help research topics, build briefs, '
         'draft articles, check SEO, and prepare approved content for publication. The team supplies '
         'buyer knowledge and product facts, approves priorities, and reviews the finished article.'),
        ('Which content marketing tasks should a small B2B team automate first?',
         'Start with keyword grouping, brief preparation, and metadata or formatting checks. These '
         'tasks have clear inputs and outputs that are easy to review. Add first drafts and scheduled '
         'publishing after the team has agreed on brand guidelines, source requirements, and an approval process.'),
        ('What should stay human in automated content marketing?',
         'Keep positioning, product claims, customer stories, original opinions, and final approval '
         'with people who know the business. AI can organize source material and suggest wording, '
         'but the reviewer must confirm that every example is real and every claim is supported.'),
        ('How can B2B teams measure whether content automation is working?',
         'Track time spent per approved article, revision rounds, publishing consistency, and factual '
         'corrections alongside organic impressions, clicks, and relevant conversions. Compare the same '
         'pages and similar time periods. More published articles alone do not show that the content '
         'is helping buyers or contributing to the business.'),
    ]
))

# Assign the new near-page-one query to its existing URL, preserving the slug/H1.
CONTENT[188] = heading('SEO blog automation for content teams: the short answer') + paragraph(
    'SEO blog automation for content teams connects keyword research, topic selection, briefs, '
    'drafting, editorial review, on-page checks, CMS publishing, and performance review. Assign '
    'an owner and an approval step to each handoff. Start with one article and use the eight-step '
    'workflow below to make delivery repeatable while keeping factual checks and brand decisions with your team.'
) + CONTENT[188]
before(188, heading('A Practical Blog SEO Automation Workflow'), paragraph(
    'Plan those handoffs in the ' + link(231, 'B2B content calendar template') + '. Agree on '
    + link(222, 'which AI content marketing tasks to automate') + ' before production, and use the '
    + link(177, 'blog automation quality checklist') + ' as the final editorial gate.'
))
before(188, heading('Final Thoughts: Build the System, Not Just the Article'), faq_group(
    'SEO Blog Automation for Content Teams FAQ', [
        ('How does SEO blog automation for content teams work?',
         'Use one approved brief to connect keyword research, topic selection, drafting, review, '
         'SEO checks, and publishing. Give each article an owner, status, and target date. Automate '
         'repeatable checks and handoffs, then have the owner approve the facts, examples, and final page.'),
        ('How do I automate publishing AI-optimized articles to my CMS?',
         'Use a publishing integration supported by your CMS to create a draft from an approved '
         'article. Map the title, body, slug, excerpt, author, images, categories, and SEO fields. '
         'Preview formatting, links, and metadata before scheduling. After publishing, confirm that '
         'the intended URL loads, the canonical is correct, and the article appears once. Route '
         'failed jobs to a reviewer before retrying so you do not create duplicate posts.'),
        ('Which SEO checks should happen before an automated post goes live?',
         'Confirm that the article answers the intended query and that claims have supporting sources. '
         'Check the title, description, headings, image descriptions, internal link destinations, '
         'canonical URL, and indexing settings. Read the CMS preview and approve the final version '
         'before the publishing step runs.'),
        ('Does automating blog SEO guarantee more traffic?',
         'No. Automation can make production more consistent, but traffic depends on relevance, '
         'content quality, competition, and whether search engines can discover and index the page. '
         'Monitor query-level impressions, clicks, click-through rate, and relevant conversions; '
         'use those results to decide which articles need revision.'),
    ]
))

# Calendar already has an answer-first definition and thirteen-field template.
CONTENT[231] = CONTENT[231].replace(
    '>blog SEO automation for lean teams</a>', '>SEO blog automation for content teams</a>', 1
)
extend_faq(231, 'How do I connect a B2B content calendar to CMS publishing?',
    'Use the calendar status to separate review from publishing: briefed, drafting, review, '
    'approved, scheduled, and published. Send only approved articles to your CMS integration, '
    'with a publish date and timezone, author, category, metadata, and images. Preview the draft '
    'before scheduling, then record the live URL in the calendar. If publishing fails, leave '
    'the article in review and check the CMS for an existing draft before retrying.')

# P3 light pass: retain the founder workflow; add one contextual paragraph.
before(211, heading('Step 4: Turn Strategy Into a Repeatable Editorial Cadence'), paragraph(
    'Keep the weekly plan in a ' + link(231, 'B2B content calendar') + ', use '
    + link(188, 'SEO blog automation for content teams') + ' to handle repeatable search checks, '
    'and follow the ' + link(177, 'blog content quality checklist')
    + ' when reviewing the draft. These guides expand the planning, SEO, and approval steps in this founder workflow.'
))

SEO = {
    '_wordiva_seo_title': 'SEO Blog Automation for Content Teams: 8 Steps',
    '_wordiva_seo_description': 'SEO blog automation for content teams: 8 steps from keyword research to CMS publishing, with editorial checks, internal links, and a practical FAQ.',
    '_wordiva_enable_faq_schema': '1',
}
assert len(SEO['_wordiva_seo_title'] + ' | Wordiva') <= 60
assert len(SEO['_wordiva_seo_description']) <= 155
manifest = {'date': '2026-09-09', 'posts': []}
for post_id in (177, 222, 188, 231, 211):
    post = POSTS[post_id]
    old = (ROOT / 'backups' / f'{post_id}.2026-09-09.post_content.html').read_bytes()
    new = CONTENT[post_id].encode()
    filename = f'{post_id}.post_content.html'
    (OUTPUT / filename).write_bytes(new)
    manifest['posts'].append({
        'ID': post_id, 'slug': post['slug'], 'url': post['url'],
        'before_sha256': hashlib.sha256(old).hexdigest(),
        'after_sha256': hashlib.sha256(new).hexdigest(),
        'before_meta': post['meta'], 'meta_updates': SEO if post_id == 188 else {},
        'file': filename, 'faq_count': CONTENT[post_id].count('<summary>'),
    })
(OUTPUT / 'manifest.json').write_text(json.dumps(manifest, indent=2, ensure_ascii=False) + '\n')
print('Built five post revisions and a guarded deployment manifest in', OUTPUT)

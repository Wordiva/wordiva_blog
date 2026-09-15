#!/usr/bin/env python3
"""Build the Sep 15 WordPress edits from captured live content, without publishing."""
import hashlib
import html
import json
from pathlib import Path

ROOT = Path(__file__).resolve().parent
OUTPUT = ROOT / "updates" / "2026-09-15"
OUTPUT.mkdir(parents=True, exist_ok=True)
SNAPSHOT = json.loads((ROOT / "backups" / "2026-09-15-posts.json").read_text())
POSTS = {post["ID"]: post for post in SNAPSHOT["posts"]}
CONTENT = {
    post_id: (ROOT / "backups" / f"{post_id}.2026-09-15.post_content.html").read_text()
    for post_id in POSTS
}
LEAK = (
    "content-calendar-dispatch",
    "hellomedspa",
    "%task",
    "+task",
    "task (routed",
    "brand-shop",
    "wolf pack",
    "virtual nurse",
)


def details(question, answer):
    return (
        '<!-- wp:details -->\n<details class="wp-block-details"><summary>'
        + html.escape(question)
        + "</summary><!-- wp:paragraph -->\n<p>"
        + answer
        + "</p>\n<!-- /wp:paragraph --></details>\n<!-- /wp:details -->"
    )


def seo(title, description, faq=None):
    payload = {
        "_wordiva_seo_title": title,
        "_wordiva_seo_description": description,
    }
    if faq is not None:
        payload["_wordiva_enable_faq_schema"] = faq
    assert len(title + " | Wordiva") <= 60, (title, len(title + " | Wordiva"))
    assert 20 <= len(description) <= 155, (description, len(description))
    return payload


def append_faq(post_id, question, answer):
    marker = "<!-- /wp:details --></div>\n<!-- /wp:group -->"
    assert CONTENT[post_id].count(marker) == 1, (post_id, marker)
    CONTENT[post_id] = CONTENT[post_id].replace(
        marker,
        "<!-- /wp:details -->\n\n" + details(question, answer) + "</div>\n<!-- /wp:group -->",
        1,
    )


# WP-I: restore the ranking query in the answer-first H2; keep "without looking cheap" in meta.
CONTENT[177] = CONTENT[177].replace(
    ">Summary: How to automate blog content the right way</h2>",
    ">Summary: How to automate blog content without losing quality</h2>",
    1,
)
assert CONTENT[177].count("without losing quality") >= 2
assert "SEO blog automation for content teams" not in CONTENT[177]

# WP-J: lock CMS-publish long-tail on the B2B automation URL (pos ~10.7).
append_faq(
    222,
    "How do I automate CMS publishing for AI content marketing?",
    "Use the CMS integration to create a draft from an approved article, mapping title, body, "
    "slug, excerpt, author, images, categories, and SEO fields. Preview formatting and links, "
    "then schedule. After publishing, confirm the live URL, canonical, and that the article "
    "appears once. That CMS step is how automated content marketing ships without duplicate posts.",
)

# WP-J: calendar titles/FAQ after scrub. Target rising "content marketing b2b calendar".
append_faq(
    231,
    "What is a content marketing B2B calendar?",
    "A content marketing B2B calendar is the same system as a B2B content calendar: a weekly "
    "plan that maps each article to a buyer pain, a primary keyword, and a publish date so "
    "content marketing stays tied to pipeline instead of ad-hoc topics. Keep one owner, one "
    "status column, and human approval before anything is scheduled in the CMS.",
)

META = {
    177: seo(
        "Automate Blog Content Without Losing Quality",
        "Automate blog content without losing quality: 7 steps so content creation does not look cheap, with briefs, SEO, and human review.",
    ),
    222: seo(
        "AI Content Marketing Automation for B2B",
        "AI content marketing automation for B2B: 6 tasks to automate first, CMS publishing, and 4 checks that stay human.",
    ),
    245: seo(
        "Content Marketing ROI for B2B: Blog KPIs",
        "Content marketing ROI for B2B: measure blog KPIs, pipeline influence, dashboards, and which articles a lean team should refresh.",
    ),
}

manifest = {"date": "2026-09-15", "posts": []}
for post_id in (177, 222, 188, 231, 245, 211):
    post = POSTS[post_id]
    old = (ROOT / "backups" / f"{post_id}.2026-09-15.post_content.html").read_bytes()
    new = CONTENT[post_id].encode()
    filename = f"{post_id}.post_content.html"
    (OUTPUT / filename).write_bytes(new)
    lower = CONTENT[post_id].lower()
    for needle in LEAK:
        assert needle.lower() not in lower, (post_id, needle)
        assert ")https:" not in lower
    manifest["posts"].append(
        {
            "ID": post_id,
            "slug": post["slug"],
            "url": post["url"],
            "before_sha256": hashlib.sha256(old).hexdigest(),
            "after_sha256": hashlib.sha256(new).hexdigest(),
            "before_meta": post["meta"],
            "meta_updates": META.get(post_id, {}),
            "file": filename,
            "faq_count": CONTENT[post_id].count("<summary>"),
        }
    )
(OUTPUT / "manifest.json").write_text(json.dumps(manifest, indent=2, ensure_ascii=False) + "\n")
print("Built post revisions and a guarded deployment manifest in", OUTPUT)

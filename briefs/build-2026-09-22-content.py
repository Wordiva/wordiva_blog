#!/usr/bin/env python3
"""Build the Sep 21–22 WordPress edits from captured live content, without publishing."""
import hashlib
import html
import json
from pathlib import Path

ROOT = Path(__file__).resolve().parent
OUTPUT = ROOT / "updates" / "2026-09-22"
OUTPUT.mkdir(parents=True, exist_ok=True)
SNAPSHOT = json.loads((ROOT / "backups" / "2026-09-22-posts.json").read_text())
POSTS = {post["ID"]: post for post in SNAPSHOT["posts"]}
CONTENT = {
    post_id: (ROOT / "backups" / f"{post_id}.2026-09-22.post_content.html").read_text()
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


# WP-I/WP-N2: flagship pos recovered 38.5 -> 29.6; promote it and stop the
# "seo blog automation for content teams" leak (4 imp at ~54). The only near-match
# on the flagship is the anchor text of its link to 188.
FLAG_ANCHOR = 'blog-seo-automation">blog SEO automation workflow</a>'
assert CONTENT[177].count(FLAG_ANCHOR) == 1
CONTENT[177] = CONTENT[177].replace(
    FLAG_ANCHOR, 'blog-seo-automation">8-step search-to-publish workflow</a>', 1
)
assert "seo automation" not in CONTENT[177].lower()
append_faq(
    177,
    "How do you keep quality high in automated content?",
    "Set the quality bar before the draft exists: a brief that names the reader, the problem, "
    "and the evidence the article needs. Check every draft against the same checklist for "
    "accuracy, specificity, voice, and links, and let one editor approve or hold it. Then track "
    "which posts earn clicks and refresh the weak ones instead of publishing more like them.",
)

# Inbound anchor from 188 to the flagship: use the flagship's ranking query.
INBOUND = 'automate-blog-content-without-losing-quality">blog automation quality checklist</a>'
assert CONTENT[188].count(INBOUND) == 1
CONTENT[188] = CONTENT[188].replace(
    INBOUND,
    'automate-blog-content-without-losing-quality">how to automate blog content without losing quality</a>',
    1,
)

# WP-J: rising deep query "how can we automate seo execution..." (50 / 15.6) on 188.
append_faq(
    188,
    "How can we automate SEO execution for a content team?",
    "Automate the repeatable steps: keyword clustering, briefs with search intent and headings, "
    "on-page checks for titles, meta descriptions, and internal links, and scheduled CMS "
    "publishing. Keep a human owner for topic priority, facts, and final approval. Review "
    "Search Console queries monthly to decide which posts to refresh next.",
)

# P2: ROI surging (169 / 21.1); add the rising founder query.
append_faq(
    245,
    "How should founders measure content marketing ROI?",
    "Track three numbers each month: qualified demo or pricing visits from blog sessions, deals "
    "where a blog URL appears in the buyer journey, and hours spent per published article. Compare "
    "them with what the content costs, and judge each article over about 90 days rather than the "
    "week it ships.",
)

# 222 and 231 titles/FAQs changed on Sep 15; left alone so Google can settle.
META = {
    177: seo(
        "Automate Blog Content Without Losing Quality",
        "Automate blog content without losing quality: keep quality high and automate content creation without looking cheap, with human review.",
    ),
}

manifest = {"date": "2026-09-22", "posts": []}
for post_id in (177, 222, 188, 231, 245, 211):
    post = POSTS[post_id]
    old = (ROOT / "backups" / f"{post_id}.2026-09-22.post_content.html").read_bytes()
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

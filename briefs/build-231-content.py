#!/usr/bin/env python3
"""Build the WP-B revision of post 231 (b2b-content-calendar-automated-plan)
from the backed-up post_content. Idempotent: re-running on already-updated
content is a no-op."""
import pathlib
import re
import sys

SRC = pathlib.Path(__file__).parent / "backups" / "231-b2b-content-calendar-automated-plan.post_content.html"
OUT = pathlib.Path(__file__).parent / "backups" / "231-new.post_content.html"

content = SRC.read_text()

ANSWER_FIRST = """<!-- wp:paragraph {"className":"wordiva-answer-first"} -->
<p class="wordiva-answer-first"><strong>A B2B content calendar</strong> is a planned publishing schedule that maps every article to a specific buyer pain, funnel stage, target keyword, and product narrative, then tracks it from idea to publish to refresh. Done well, it is less a spreadsheet of dates and more the operating system for a lean team’s blog: it decides what gets written, why, for whom, and what happens after it goes live. This guide shows how to build one and automate it.</p>
<!-- /wp:paragraph -->

"""

FAQ = """<!-- wp:heading -->
<h2 class="wp-block-heading">B2B Content Calendar FAQ</h2>
<!-- /wp:heading -->

<!-- wp:group {"className":"wordiva-faq","layout":{"type":"constrained"}} -->
<div class="wp-block-group wordiva-faq"><!-- wp:details -->
<details class="wp-block-details"><summary>What is a B2B content calendar?</summary><!-- wp:paragraph -->
<p>A B2B content calendar is a planned schedule of articles and other content where each entry is tied to a buyer segment, a pain point, a funnel stage, a primary keyword, and a product narrative. Unlike a simple editorial calendar, it stores the reasoning behind every piece so a lean team can publish consistently without re-deciding strategy each week.</p>
<!-- /wp:paragraph --></details>
<!-- /wp:details -->

<!-- wp:details -->
<details class="wp-block-details"><summary>How far ahead should a B2B content calendar be planned?</summary><!-- wp:paragraph -->
<p>Plan themes and pain clusters one quarter ahead, but lock specific article topics only one month at a time. Locking six months of titles usually goes stale as products, search intent, and sales conversations change. A monthly planning block plus a weekly review keeps the calendar accurate and realistic.</p>
<!-- /wp:paragraph --></details>
<!-- /wp:details -->

<!-- wp:details -->
<details class="wp-block-details"><summary>How many blog posts per week should a lean B2B team publish?</summary><!-- wp:paragraph -->
<p>One strong, well-optimized article per week is the sweet spot for most founders and small growth teams. It is enough to build compounding organic traffic and internal linking momentum while staying sustainable. Teams using an agentic AI workflow can scale to two to four per week without adding headcount.</p>
<!-- /wp:paragraph --></details>
<!-- /wp:details -->

<!-- wp:details -->
<details class="wp-block-details"><summary>What columns should a B2B content calendar template include?</summary><!-- wp:paragraph -->
<p>Keep it lean: publish date, status, ICP segment, pain cluster, funnel stage, primary keyword, search intent, article angle, product narrative, internal links, CTA, distribution notes, and a review date. Those thirteen fields capture strategy, workflow, and follow-up without turning the calendar into a 40-column spreadsheet.</p>
<!-- /wp:paragraph --></details>
<!-- /wp:details -->

<!-- wp:details -->
<details class="wp-block-details"><summary>Can a B2B content calendar be automated?</summary><!-- wp:paragraph -->
<p>Yes. AI agents can handle topic ideation from ICP pain points and keyword gaps, keyword mapping, brief creation, first drafts, SEO optimization, scheduling into your CMS, and flagging older posts for refresh. Humans stay responsible for positioning, proprietary insight, and final approval. Wordiva runs this entire loop as one agentic content workflow.</p>
<!-- /wp:paragraph --></details>
<!-- /wp:details --></div>
<!-- /wp:group -->

"""

# 1. Answer-first intro before the first heading.
if "wordiva-answer-first" not in content:
    content = ANSWER_FIRST + content

# 2. FAQ block before the closing "From Calendar to Content Engine" section.
if "wordiva-faq" not in content:
    marker = '<!-- wp:heading -->\n<h2 class="wp-block-heading">From Calendar to Content Engine</h2>'
    assert marker in content, "conclusion heading not found"
    content = content.replace(marker, FAQ + marker, 1)

# 3. Add the missing sibling link (automate-blog-content-without-losing-quality).
quality_url = "https://wordiva.ai/blog/ai-content-marketing/automate-blog-content-without-losing-quality"
if quality_url not in content:
    old = "They should not be copying metadata between tools or rebuilding the same article brief every week.</p>"
    new = ('They should not be copying metadata between tools or rebuilding the same article brief every week. '
           'For the guardrails that keep automated drafts specific and on-brand, see our guide on '
           f'<a href="{quality_url}">how to automate blog content without losing quality</a>.</p>')
    assert old in content, "anchor paragraph not found"
    content = content.replace(old, new, 1)

# 4. Make internal links crawlable: strip nofollow/noopener/noreferrer + target from wordiva.ai anchors.
def clean_anchor(m):
    tag = re.sub(r'\s+target="[^"]*"', "", m.group(0))
    tag = re.sub(r'\s+rel="[^"]*"', "", tag)
    return tag

content = re.sub(r'<a\s[^>]*href="https://wordiva\.ai/[^"]*"[^>]*>', clean_anchor, content)

OUT.write_text(content)
print(f"wrote {OUT} ({len(content)} bytes)")
print("internal links:", len(re.findall(r'href="https://wordiva\.ai/blog/', content)))
print("nofollow remaining on internal:", len(re.findall(r'<a[^>]*wordiva\.ai[^>]*nofollow', content)))
print("details blocks:", content.count("<!-- wp:details -->"))

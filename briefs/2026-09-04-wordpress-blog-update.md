# Wordiva Blog (WordPress) — SEO Update 2026-09-04

**Source brief:** `2026-09-04-wordpress-blog-seo-brief.md`
**Theme version shipped:** `wordiva-blog-theme` 2.1.0 (deployed to production, `main` pushed to `github.com/Wordiva/wordiva_blog`)
**Verification:** `./deploy/verify-phase.sh all` → 85 passed, 0 failed (phase 10 added for this brief)

## Task status

| Task | Status | Notes |
|---|---|---|
| WP-A Title & meta refresh | Done | New per-post `SEO Title` / `Meta Description` fields (post editor → SEO Checklist box, exposed via REST as `_wordiva_seo_title` / `_wordiva_seo_description`). Applied to 4 posts, see below. Slugs unchanged. |
| WP-B Calendar post boost | Done | Answer-first intro, 5-question FAQ (core/details accordion, `.wordiva-faq`), `FAQPage` JSON-LD live, 5 crawlable internal links (was 4, all `nofollow`+`target=_blank` — fixed). |
| WP-C Pagination canonicals | Done | `/blog/page/N` and every archive `/page/N` now self-canonical with `rel=prev/next`. Blog index moved to 12 posts/page so `/blog/page/2/` is now **404** (only 11 posts). Verified on `/author/rubia/page/2` (live paginated archive). |
| WP-D Schema pass | Done | `BlogPosting`, `BreadcrumbList`, `WebPage` were already emitted; `FAQPage` now parses `core/details` blocks (`?` headings still supported). `BlogPosting.description` uses the SEO meta description. |
| WP-E Taxonomy hygiene | Done | Tags with < 3 posts → `noindex, follow` and excluded from `wp-sitemap-taxonomies-post_tag-1.xml` (7 tags remain). Titles unified to `<Name> | Wordiva`; tag pages titled `Posts tagged “X”`. Category descriptions already unique via fallback map. |
| WP-F Analytics & sitemap | Verified, no change | Head emits only `G-QNTZ96XNJE`. `wp-sitemap-posts-post-1.xml` already listed all 11 posts with current `lastmod`. **Manual:** resubmit `https://wordiva.ai/blog/wp-sitemap.xml` in GSC and request indexing for the 4 posts below. |

## Titles / metas applied (WP-A)

| Post | `<title>` (chars) | Meta description (chars) |
|---|---|---|
| automate-blog-content-without-losing-quality | Automate Blog Content Without Losing Quality \| Wordiva (54) | 155 |
| blog-seo-automation | Blog SEO Automation for Lean Teams: Scale Traffic \| Wordiva (59) | 155 |
| ai-content-marketing-b2b-automation | B2B AI Content Marketing: What to Automate First \| Wordiva (58) | 155 |
| b2b-content-calendar-automated-plan | B2B Content Calendar: Automated Template & Plan \| Wordiva (57) | 153 |

Full copy: `briefs/seo-meta-2026-09-04.tsv`. Pre-edit content backup of post 231: `briefs/backups/231-*.html` (WP revision also retained).

## Site-wide title change

All pages now use `<title>… | Wordiva</title>` (previously WP default `… – Wordiva Blog`); `og:title` / `twitter:title` match. Monitor CTR in GSC over 2–4 weeks.

## Found during QA (fixed)

- Internal links in post bodies carried `rel="nofollow noopener noreferrer" target="_blank"`. Theme now strips these for `wordiva.ai` anchors at render time (`wordiva_make_internal_links_followable`) and the calendar post source was cleaned. **Content ops:** stop adding `nofollow` to internal links in the generator.
- `deploy.sh` / `verify-phase.sh` were reporting false failures (`echo | grep -q` under `pipefail` on 190 KB pages; stale asset/robots URLs). Fixed.

## Not done / follow-ups

- Rich Results Test and GSC URL Inspection require a logged-in browser — JSON-LD was validated structurally (all blocks parse; FAQPage has 5 Q&As). Please run https://search.google.com/test/rich-results on the calendar post.
- Light-theme FAQ accordion uses the shared `--w-*` tokens; only the dark theme was screenshot-verified.
- `index.php` still calls `update_post_meta` / `delete_post_meta` during front-end render (write-on-read). Recommend removing in a follow-up.
- Cadence (P2-9) and AEO answer blocks on the other long-tail posts (P2-10) remain content ops.
- wp-cli is now installed at `/home/ubuntu/bin/wp` on the server (no sudo, phar). Use with `--path=/var/www/html/wordivablog` from another cwd.

## Cross-links for the Next.js agent

- Homepage / `/features` → `https://wordiva.ai/blog/ai-content-marketing/b2b-content-calendar-automated-plan` anchor “B2B content calendar template”.

# Wordiva Blog (WordPress) — SEO Update

**Generated:** 2026-09-07 (Asia/Dubai)  
**Project:** `/blog/**` only (WordPress)  
**Companion:** `2026-09-07-nextjs-marketing-update.md`  
**Compared to:** `2026-09-04-wordpress-blog-seo-brief.md` / full brief  
**Windows:** Recent `2026-08-08` → `2026-09-04` · Prior `2026-07-11` → `2026-08-07`  
**Sources:** GSC `sc-domain:wordiva.ai` + `https://wordiva.ai/blog/` · GA4 `properties/541636789`

---

## TLDR (blog)

Flagship post **`automate-blog-content-without-losing-quality`** collapsed ~pos **16 → 42** while still earning the only content click. High-imp posts gained impressions with ~0 CTR. Fix/verify a **malformed blog href** that showed up as a concatenated landing path.

---

## Metric context (blog-relevant)

Domain GSC: impressions **282 → 548** (+94%), clicks **0 → 2**, avg pos **16 → 40.5** (worse — deep long-tail pages).  
Organic Search sessions (sitewide) **13 → 42** — some of that is blog landings.

---

## Page / query deltas (blog URLs)

| Page | Prior imp / pos | Recent imp / pos / clicks | Issue |
|------|-----------------|---------------------------|-------|
| `/blog/.../automate-blog-content-without-losing-quality` | 194 / **15.8** | 99 / **41.7** / 1 | **Position collapse** |
| `/blog/.../blog-seo-automation` | 58 / 23.7 | **139** / 36.7 / 0 | Imp up, rank slipped |
| `/blog/.../ai-content-marketing-b2b-automation` | — | **159** / 53.5 / 0 | New volume; deep SERP |
| `/blog/` | — | (in domain mix) | Archive |
| `/blog/.../b2b-content-calendar-automated-plan` | — | 20 / 29.9 / 0 | Entering index |

**Push queries (blog content owns these):**
- `b2b content calendar` — 7 imp, **pos ~11.4**
- `how do i automate publishing ai-optimized articles to my cms?` — 5 imp, **pos 11**
- `automated seo blog traffic` / `automated seo blog strategy` — pos ~20–22
- Rising deep: `ai content marketing automation`, `automated content marketing`, `content calendars for b2b marketing`

---

## Issues (WordPress)

### N1 — Broken concatenated landing URL (P0)

GA landing:
`/blog/ai-content-marketing/automate-blog-content-without-losing-quality)https:/wordiva.ai/blog/ai-content-marketing/blog-seo-automationarticle`

Likely malformed href in **WP content / related posts / theme**. Grep WP first; coordinate with Next if homepage cards also glue URLs.

### N2 — Flagship ranking regression (P0)

Recover `automate-blog-content-without-losing-quality` (title/meta, answer-first, FAQ schema, internal links within blog).

### N3 — Impression growth without CTR (P0)

Ship clickable titles on top impression posts before more indexation.

### Thin taxonomies / pagination (carry forward)

Pagination canonical on `/blog/page/N`, thin tags — still from Sep 4 WP brief if not shipped.

---

## Coding tasks (WordPress only)

### Task WP-H — Fix broken blog href (P0)

1. Grep WP theme/content for `)https:` and glued absolute URLs.
2. Fix links; optional redirect from malformed path → canonical post.
3. Success: bad landing gone from GA; malformed path not 200.

### Task WP-I — Recover flagship post (P0)

Post: `automate-blog-content-without-losing-quality`
1. Rewrite title + meta.
2. Answer-first H2 + FAQ JSON-LD.
3. Internal links from `/blog/` + related posts (homepage links → Next brief).
4. Success: pos toward ≤25 in 14–28d; CTR >1% on ≥50 imp.

### Task WP-J — Titles/on-page for remaining high-imp posts (P0)

- `blog-seo-automation` (139 imp)
- `ai-content-marketing-b2b-automation` (159 imp)
- `b2b-content-calendar-automated-plan` (push `b2b content calendar` ≤8)

### Out of scope

Main-site `/`, `/learn`, `/integrations`, `/terms`, GA app-path filters, Next hrefs — see companion brief.

---

*End — WordPress blog update 2026-09-07*

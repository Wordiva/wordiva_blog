# Wordiva Blog (WordPress) — SEO Update

**Generated:** 2026-09-09 (Asia/Dubai)  
**Project:** `/blog/**` only (WordPress)  
**Companion:** `2026-09-09-nextjs-marketing-update.md`  
**Compared to:** `2026-09-08-wordpress-blog-update.md`  
**Windows:** Recent `2026-08-10` → `2026-09-06` · Prior `2026-07-13` → `2026-08-09`  
**Sources:** GSC `sc-domain:wordiva.ai` + blog prefix · GA4 `properties/541636789`

---

## TLDR (blog)

Flagship still ~pos **42** (92 imp / 1 click). `ai-content-marketing-b2b-automation` climbed again to **194** impressions (0 clicks). New deep URL: `b2b-blog-strategy-founder-workflow` (8 imp). Strongest near-page push query is now **`seo blog automation for content teams`** (23 / ~11.3). Broken concatenated landing still absent in GA — keep verify-fixed.

---

## Page / query deltas (blog)

| Page | Prior imp / pos | Recent imp / pos / clicks | vs Sep 8 |
|------|-----------------|---------------------------|----------|
| `.../automate-blog-content-without-losing-quality` | 199 / 16.3 | 92 / **42.0** / 1 | Still collapsed |
| `.../blog-seo-automation` | 76 / 26.6 | **144** / 36.8 / 0 | +3 imp |
| `.../ai-content-marketing-b2b-automation` | — | **194** / 52.5 / 0 | **+15** vs Sep 8 |
| `.../b2b-content-calendar-automated-plan` | — | 20 / 29.9 / 0 | Flat |
| `.../b2b-blog-strategy-founder-workflow` | — | **8** / 60.9 / 0 | **New** in window |

**Push (≤~12):** `seo blog automation for content teams` (23 / **11.3**), CMS publish long-tail (6 / **11**), `b2b content calendar` (7 / 11.4), “automate content creation without looking cheap” (5 / **9.6**).  
**Deep rising:** ai content marketing automation (22 / 64), automated content marketing (14 / 44).

Organic Search landings that hit blog posts this window: b2b-automation (3), flagship + seo-automation + `/blog` (1 each).

---

## Issues (WordPress)

### WP-N1 status — Broken href

Not in GA landings today → **verify-fixed**, not closed. Grep WP first for `)https:`; assert malformed path ≠ 200. Shared bug: Next companion Task NX-M after WP.

### WP-N2 — Flagship + high-imp CTR (still open)

Recover flagship; ship titles/FAQ on b2b-automation (**194** and climbing) and blog-seo-automation; map push query “seo blog automation for content teams” onto seo-automation post; calendar post for query ≤12.

---

## Coding tasks (WordPress only)

### Task WP-M — Confirm broken href closed (P0, ~30 min)

Grep `)https:`; hit old path → 404/301 only.

### Task WP-I/J (carry, P0)

Priority order:
1. `automate-blog-content-without-losing-quality` (recover pos ~42)
2. `ai-content-marketing-b2b-automation` (**194** imp)
3. `blog-seo-automation` (144) — target “seo blog automation for content teams”
4. `b2b-content-calendar-automated-plan` + CMS FAQ for pos ~11 long-tails
5. Light check `b2b-blog-strategy-founder-workflow` (new, deep — P3)

### Out of scope

`/admin`/`/dashboard` GA filters, compare/plugin pages, homepage alts — companion Next brief.

---

*End — WordPress blog update 2026-09-09*

# Wordiva Blog (WordPress) — SEO Update

**Generated:** 2026-09-08 (Asia/Dubai)  
**Project:** `/blog/**` only (WordPress)  
**Companion:** `2026-09-08-nextjs-marketing-update.md`  
**Compared to:** `2026-09-07-wordpress-blog-update.md`  
**Windows:** Recent `2026-08-09` → `2026-09-05` · Prior `2026-07-12` → `2026-08-08`  
**Sources:** GSC `sc-domain:wordiva.ai` + blog prefix · GA4 `properties/541636789`

---

## TLDR (blog)

Flagship still ~pos **42**. `ai-content-marketing-b2b-automation` grew to **179** impressions (still 0 clicks). Broken concatenated landing **not** in GA this window — **verify** WP href fix stuck. CTR still near-zero on all high-imp posts.

---

## Page / query deltas (blog)

| Page | Prior imp / pos | Recent imp / pos / clicks | vs Sep 7 |
|------|-----------------|---------------------------|----------|
| `.../automate-blog-content-without-losing-quality` | 196 / 16.1 | 95 / **41.7** / 1 | Still collapsed |
| `.../blog-seo-automation` | 60 / 23.0 | **141** / 36.8 / 0 | Flat vs Sep 7 |
| `.../ai-content-marketing-b2b-automation` | — | **179** / 52.2 / 0 | **+20 imp** vs Sep 7 |
| `.../b2b-content-calendar-automated-plan` | — | 20 / 29.9 / 0 | Unchanged |

**Push:** `b2b content calendar` (~11.4), CMS publishing long-tails (~pos 9–11), automated SEO blog variants (~20–22).  
**Deep rising:** ai content marketing automation (21), automated content marketing (13).

Organic Search landings that hit blog posts this window: b2b-automation (3), flagship + seo-automation (1 each).

---

## Issues (WordPress)

### WP-N1 status — Broken href

Not in GA landings today → **verify-fixed**, not closed. Grep WP; assert malformed path ≠ 200.

### WP-N2 — Flagship + high-imp CTR (still open)

Recover flagship; ship titles on b2b-automation (growing) and blog-seo-automation; calendar post for query ≤8.

---

## Coding tasks (WordPress only)

### Task WP-M — Confirm broken href closed (P0, ~30 min)

Grep `)https:`; hit old path → 404/301 only.

### Task WP-I/J (carry, P0)

Priority order:
1. `automate-blog-content-without-losing-quality` (recover)
2. `ai-content-marketing-b2b-automation` (**179** imp)
3. `blog-seo-automation` (141)
4. `b2b-content-calendar-automated-plan`

### Out of scope

`/admin`/`/dashboard` GA filters, compare/plugin pages, homepage alts — companion Next brief.

---

*End — WordPress blog update 2026-09-08*

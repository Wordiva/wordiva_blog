# Wordiva Blog (WordPress) — SEO Update

**Generated:** 2026-09-10 (Asia/Dubai)  
**Project:** `/blog/**` only (WordPress)  
**Companion:** `2026-09-10-nextjs-marketing-update.md`  
**Compared to:** `2026-09-09-wordpress-blog-update.md`  
**Windows:** Recent `2026-08-11` → `2026-09-07` · Prior `2026-07-14` → `2026-08-10`  
**Sources:** GSC `sc-domain:wordiva.ai` + blog prefix · GA4 `properties/541636789`

---

## TLDR (blog)

Flagship still ~pos **43** (86 imp / 1 click). `ai-content-marketing-b2b-automation` **206** imp (+12 vs Sep 9). `blog-seo-automation` **158** (+14). Biggest move: `b2b-content-calendar-automated-plan` **35** imp / pos **~22.9** (was 20 / ~29.9). Push query **`seo blog automation for content teams`** still 23 / ~11.3. Broken concatenated landing still **0** in GA — keep verify-fixed.

---

## Page / query deltas (blog)

| Page | Prior imp / pos | Recent imp / pos / clicks | vs Sep 9 |
|------|-----------------|---------------------------|----------|
| `.../automate-blog-content-without-losing-quality` | 203 / 16.9 | 86 / **42.6** / 1 | Still collapsed (−6 imp) |
| `.../blog-seo-automation` | 78 / 27.0 | **158** / 37.7 / 0 | **+14** |
| `.../ai-content-marketing-b2b-automation` | — | **206** / 52.4 / 0 | **+12** |
| `.../b2b-content-calendar-automated-plan` | — | **35** / **22.9** / 0 | **+15** · pos improved |
| `.../b2b-blog-strategy-founder-workflow` | — | **11** / 62.6 / 0 | +3 |
| `/blog/` | 7 / 6.7 | 20 / 7.8 / 0 | Healthy hub |

**Push (≤~12):** `seo blog automation for content teams` (23 / **11.3**), CMS publish long-tail (6 / **11**), `b2b content calendar` (8 / **10.1**), “automate content creation without looking cheap” (5 / **9.6**).  
**Deep rising:** ai content marketing automation (23 / 64), automated content marketing (14 / 44), marketing content automation (14 / 82).

Organic Search landings that hit blog posts this window: b2b-automation (2), flagship + seo-automation (1 each). All-channel: calendar + b2b-automation at 9 sessions each.

---

## Issues (WordPress)

### WP-N1 status — Broken href

Malformed path **0** sessions recent → **verify-fixed**, not closed. Grep WP first for `)https:`; assert malformed path ≠ 200. Shared bug: Next companion Task NX-M after WP.

### WP-N2 — Flagship + high-imp CTR (still open)

Recover flagship; ship titles/FAQ on b2b-automation (**206**) and blog-seo-automation (**158**); map push query “seo blog automation for content teams” onto seo-automation post; calendar post now climbing — reinforce for query ≤12 (`b2b content calendar`).

---

## Coding tasks (WordPress only)

### Task WP-M — Confirm broken href closed (P0, ~30 min)

Grep `)https:`; hit old path → 404/301 only.

### Task WP-I/J (carry, P0)

Priority order:
1. `automate-blog-content-without-losing-quality` (recover pos ~43)
2. `ai-content-marketing-b2b-automation` (**206** imp)
3. `blog-seo-automation` (158) — target “seo blog automation for content teams”
4. `b2b-content-calendar-automated-plan` (**35** / ~23) — titles/FAQ for `b2b content calendar` (~10.1)
5. Light check `b2b-blog-strategy-founder-workflow` (11, deep — P3)

### Out of scope

`/admin`/`/dashboard` GA filters, compare/plugin/learn pages, homepage alts — companion Next brief.

---

*End — WordPress blog update 2026-09-10*

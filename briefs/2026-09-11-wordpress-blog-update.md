# Wordiva Blog (WordPress) — SEO Update

**Generated:** 2026-09-11 (Asia/Dubai)  
**Project:** `/blog/**` only (WordPress)  
**Companion:** `2026-09-11-nextjs-marketing-update.md`  
**Compared to:** `2026-09-10-wordpress-blog-update.md`  
**Windows:** Recent `2026-08-12` → `2026-09-08` · Prior `2026-07-15` → `2026-08-11`  
**Sources:** GSC `sc-domain:wordiva.ai` + blog prefix · GA4 `properties/541636789`

---

## TLDR (blog)

Biggest move: `b2b-content-calendar-automated-plan` **65** imp / pos **~18.6** (was 35 / ~22.9). `ai-content-marketing-b2b-automation` **224** (+18). Flagship still ~pos **43** (84 / 1 click). Push: **`seo blog automation for content teams`** → `blog-seo-automation` at **22** imp / pos **~8.7** (page-1 band). `b2b content calendar` still ~9 / **10.4**. Broken href still **0** in GA — keep verify-fixed.

---

## Page / query deltas (blog)

| Page | Prior imp / pos | Recent imp / pos / clicks | vs Sep 10 |
|------|-----------------|---------------------------|-----------|
| `.../automate-blog-content-without-losing-quality` | 201 / 17.4 | 84 / **42.5** / 1 | Still collapsed (−2) |
| `.../blog-seo-automation` | 83 / 28.2 | **158** / 37.6 / 0 | Flat |
| `.../ai-content-marketing-b2b-automation` | — | **224** / 52.0 / 0 | **+18** |
| `.../b2b-content-calendar-automated-plan` | — | **65** / **18.6** / 0 | **+30** · pos improved |
| `.../b2b-blog-strategy-founder-workflow` | — | **14** / 58.6 / 0 | +3 |
| `/blog/` | 6 / 7.0 | 21 / 7.7 / 0 | Healthy hub |

**Push (≤~12):** `seo blog automation for content teams` on `blog-seo-automation` (**22** / **~8.7**), `b2b content calendar` (9 / **10.4**), CMS publish long-tail (7 / **11**), “automate content creation without looking cheap” (5 / **9.6**).  
**Watch:** overall query pos for “seo blog automation for content teams” diluted to ~15.7 (4 imp still leak to flagship at ~54) — consolidate onto seo-automation post.  
**Deep rising:** ai content marketing automation (26 / 65), automated content marketing (15 / 44), marketing content automation (14 / 82).

Organic Search landings on blog: b2b-automation (2), flagship + seo-automation (1 each). All-channel: calendar + b2b-automation still 9 sessions each.

---

## Issues (WordPress)

### WP-N1 status — Broken href

Malformed path **0** sessions recent → **verify-fixed**, not closed. Grep WP first for `)https:`; assert malformed path ≠ 200. Shared bug: Next companion Task NX-M after WP.

### WP-N2 — Flagship + high-imp CTR (still open)

Recover flagship; ship titles/FAQ on b2b-automation (**224**) and blog-seo-automation (**158**); lock push query “seo blog automation for content teams” onto seo-automation (already **~8.7** there — reinforce); calendar post climbing hard — titles/FAQ for `b2b content calendar` (~10.4).

---

## Coding tasks (WordPress only)

### Task WP-M — Confirm broken href closed (P0, ~30 min)

Grep `)https:`; hit old path → 404/301 only.

### Task WP-I/J (carry, P0)

Priority order:
1. `automate-blog-content-without-losing-quality` (recover pos ~43)
2. `ai-content-marketing-b2b-automation` (**224** imp)
3. `blog-seo-automation` (158) — lock “seo blog automation for content teams” (page ~**8.7** on this URL); stop flagship cannibalizing that query
4. `b2b-content-calendar-automated-plan` (**65** / ~19) — titles/FAQ for `b2b content calendar` (~10.4)
5. Light check `b2b-blog-strategy-founder-workflow` (14, deep — P3)

### Out of scope

`/admin`/`/dashboard` GA filters, compare/plugin/learn pages, homepage alts — companion Next brief.

---

*End — WordPress blog update 2026-09-11*

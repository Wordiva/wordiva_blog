# Wordiva Blog (WordPress) — SEO Update

**Generated:** 2026-09-15 (Asia/Dubai)  
**Project:** `/blog/**` only (WordPress)  
**Companion:** `2026-09-15-nextjs-marketing-update.md`  
**Compared to:** `2026-09-14-wordpress-blog-update.md`  
**Windows:** Recent `2026-08-16` → `2026-09-12` · Prior `2026-07-19` → `2026-08-15`  
**Sources:** GSC `sc-domain:wordiva.ai` + blog prefix · GA4 `properties/541636789`

---

## TLDR (blog)

**P0 still open and worse:** task-title pollution on calendar post **~82** imp (was ~61) at pos **~8–10**. Do not celebrate the climb until scrubbed. Calendar `b2b-content-calendar-automated-plan` **313** / **12.6** (was 270 / 12.2). `ai-content-marketing-b2b-automation` **279** (+9). `blog-seo-automation` **185** / **32.9** (+12). ROI post **58** / **21.6** (was 40 / 18.3). Flagship **65** / **42.5** / 1 (soft −5). Push: **`seo blog automation for content teams`** → seo-automation (**23** / **~8.3**); **`b2b content calendar`** (**17** / **~9.8**). Broken href still **0** in GA — keep verify-fixed.

---

## Page / query deltas (blog)

| Page | Sep 14 (Aug15–Sep11) | Recent (Aug16–Sep12) | Delta |
|------|----------------------|----------------------|-------|
| `.../b2b-content-calendar-automated-plan` | 270 / 12.2 | **313** / **12.6** / 0 | **+43** · still near page-2 |
| `.../ai-content-marketing-b2b-automation` | 270 / 49.2 | **279** / 48.2 / 0 | +9 |
| `.../blog-seo-automation` | 173 / 34.2 | **185** / **32.9** / 0 | +12 · slight pos gain |
| `.../content-marketing-roi-b2b` | 40 / 18.3 | **58** / **21.6** / 0 | **+18** · pos slipped |
| `.../automate-blog-content-without-losing-quality` | 70 / 42.5 | 65 / **42.5** / 1 | −5 · still collapsed |
| `.../b2b-blog-strategy-founder-workflow` | 23 / 53.4 | **26** / 52.3 / 0 | +3 |
| `/blog/` | 22 / 7.5 | 22 / 7.5 / 0 | Flat hub |

**Push (≤~12):** `seo blog automation for content teams` on `blog-seo-automation` (**23** / **~8.3**); `b2b content calendar` (**17** / **~9.8**, was ~10.1); CMS publish long-tail (11 / **10.7** on b2b-automation); “automate content creation without looking cheap” (5 / **9.6** on flagship).  
**P0 pollution:** `%task` / `+task` / `content-calendar-dispatch` / hellomedspa / brand-shop / wolf pack / virtual nurse strings (**~82** combined, was ~61) all map to **calendar post** — rising, not clearing.  
**Deep rising:** ai content marketing automation (35 / 60), content marketing b2b calendar (30 / 25.5), automated content marketing (17 / 43).

Organic Search landings on blog: flagship (1 only). All-channel: calendar **9**, hub `/blog` **10**, roi **2**, flagship **2**.

---

## Issues (WordPress)

### WP-N3 — P0 open: task-title leak still growing

Pollution impressions **~61 → ~82**. Same calendar URL. Scrub still not reflected in GSC. Block celebrating calendar SEO until gone.

### WP-N1 status — Broken href

Malformed path **0** sessions → **verify-fixed**, not closed. Grep WP first for `)https:`. Shared: Next companion Task NX-M after WP.

### WP-N2 — Flagship + high-imp CTR (still open)

Recover flagship; titles/FAQ on b2b-automation (**279**) and blog-seo-automation (**185**); lock “seo blog automation for content teams” (~**8.3**); calendar titles/FAQ for `b2b content calendar` (~9.8) **only after** WP-N3; ROI (**58**) needs title/meta before pos slips further.

---

## Coding tasks (WordPress only)

### Task WP-P — Scrub task-title leak from calendar post (P0, ~1–2h) — STILL OPEN

1. Open `b2b-content-calendar-automated-plan`; grep content/revisions/custom fields for `content-calendar-dispatch`, `%task`, `+task`, `hellomedspa`, `brand-shop`, `wolf pack`, `virtual nurse`.
2. Strip leaked payload; keep intentional SEO copy only.
3. Re-save, purge cache, URL Inspection → request indexing.
4. Success: pollution queries fall over ~7–14d; calendar still ranks for `b2b content calendar`.

### Task WP-M — Confirm broken href closed (P0, ~30 min)

Grep `)https:`; hit old path → 404/301 only.

### Task WP-I/J (carry, P0)

Priority order:
1. **WP-P first** (pollution up to ~82)
2. `automate-blog-content-without-losing-quality` (recover pos ~43)
3. `ai-content-marketing-b2b-automation` (**279** imp)
4. `blog-seo-automation` (185) — lock “seo blog automation for content teams” (~**8.3**)
5. `b2b-content-calendar-automated-plan` (**313** / ~12.6) — titles/FAQ after scrub
6. `content-marketing-roi-b2b` (**58** / ~21.6) — title/meta + internal links (was P2; promote lightly)
7. Light check `b2b-blog-strategy-founder-workflow` (26, deep — P3)

### Out of scope

`/admin`/`/dashboard` GA filters, compare/plugin/learn pages, homepage alts — companion Next brief.

---

*End — WordPress blog update 2026-09-15*

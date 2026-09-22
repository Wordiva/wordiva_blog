# Wordiva Blog (WordPress) — SEO Update

**Generated:** 2026-09-22 (Asia/Dubai)  
**Project:** `/blog/**` only (WordPress)  
**Companion:** `2026-09-22-nextjs-marketing-update.md`  
**Compared to:** `2026-09-21-wordpress-blog-update.md`  
**Windows:** Recent `2026-08-23` → `2026-09-19` · Prior `2026-07-26` → `2026-08-22`  
**Sources:** GSC `sc-domain:wordiva.ai` + blog prefix · GA4 `properties/541636789`

---

## TLDR (blog)

**Biggest blog mover this window:** flagship `automate-blog-content-without-losing-quality` **32** / **29.6** (was 33 / 38.5) — impressions soft (−1) but **pos recovered ~9** (38.5→29.6). Calendar `b2b-content-calendar-automated-plan` **499** / **13.0** (was 480 / 13.0) — **+19** imp, still straddling page-2. Pollution **~109** flat (WP-P still open). `ai-content-marketing-b2b-automation` **372** / **42.7** (+9 · slight pos). ROI **169** / **21.1** (+2). `blog-seo-automation` **227** / **27.7** flat — still holds “seo blog automation for content teams” (**24** / **~7.2**; **4** imp leak to flagship at ~54). Push: **`b2b content calendar`** (**43** / **~9.3**). Hub `/blog/` **15** / 9.7. Organic Search → blog: **none**. Broken href still **0** in GA — keep verify-fixed.

---

## Page / query deltas (blog)

| Page | Sep 21 (Aug22–Sep18) | Recent (Aug23–Sep19) | Delta |
|------|----------------------|----------------------|-------|
| `.../b2b-content-calendar-automated-plan` | 480 / 13.0 | **499** / **13.0** / 0 | **+19** · still page-2 edge |
| `.../ai-content-marketing-b2b-automation` | 363 / 43.1 | **372** / **42.7** / 0 | **+9** · slight pos |
| `.../blog-seo-automation` | 227 / 27.7 | **227** / **27.7** / 0 | Flat |
| `.../content-marketing-roi-b2b` | 167 / 21.2 | **169** / **21.1** / 0 | +2 |
| `.../automate-blog-content-without-losing-quality` | 33 / 38.5 | **32** / **29.6** / 0 | −1 · **big pos recover** |
| `.../b2b-blog-strategy-founder-workflow` | 29 / 53.3 | **29** / 53.3 / 0 | Flat |
| `/blog/` | 15 / 9.7 | **15** / **9.7** / 0 | Flat |

**Push (≤~12):** `b2b content calendar` on calendar (**43** / **~9.3**, was ~41 / ~9.4); `seo blog automation for content teams` on `blog-seo-automation` (**24** / **~7.2**; **4** imp still leak to flagship at ~54). Flagship also has “how to keep quality high in automated content?” (1 / **1**) and “what's the best way to automate content creation without looking cheap?” (1 / **4**) — ride the pos recovery.  
**P0 pollution:** `%task` / `+task` / `content-calendar-dispatch` / hellomedspa / brand-shop / wolf pack strings (**~109** combined, flat vs Sep 21) — **all** map to **calendar post** only.  
**Deep rising:** “how can we automate seo execution…” on seo-automation (**50** / **15.6**).

All-channel blog landings: calendar **11**, ROI **11** (Organic Social 9), hub `/blog` **9**, flagship **3**, seo-automation **2**, founder-workflow **2**. Organic Search → blog: **0**.

---

## Issues (WordPress)

### WP-N3 — P0 open: task-title leak flat (not scrubbed)

Pollution impressions **~109** (unchanged vs Sep 18 and Sep 21). Same calendar URL only. Scrub still not reflected in GSC. Block celebrating calendar SEO until gone.

### WP-N1 status — Broken href

Malformed path **0** sessions → **verify-fixed**, not closed. Grep WP first for `)https:`. Shared: Next companion Task NX-M after WP.

### WP-N2 — Flagship + high-imp CTR (still open)

Flagship **pos recovered hard** to ~29.6 (from ~38.5 / ~46 earlier) — **promote now** while momentum holds. Titles/FAQ on b2b-automation (**372**) and blog-seo-automation (**227**); lock “seo blog automation for content teams” (~**7.2** on target); calendar titles/FAQ for `b2b content calendar` (~9.3) **only after** WP-N3; ROI (**169**) keep elevated while near page-2 (pos 21.1).

---

## Coding tasks (WordPress only)

### Task WP-P — Scrub task-title leak from calendar post (P0, ~1–2h) — STILL OPEN / FLAT

1. Open `b2b-content-calendar-automated-plan`; grep content/revisions/custom fields for `content-calendar-dispatch`, `%task`, `+task`, `hellomedspa`, `brand-shop`, `wolf pack`, `virtual nurse`.
2. Strip leaked payload; keep intentional SEO copy only.
3. Re-save, purge cache, URL Inspection → request indexing.
4. Success: pollution queries fall over ~7–14d; calendar still ranks for `b2b content calendar`.

### Task WP-M — Confirm broken href closed (P0, ~30 min)

Grep `)https:`; hit old path → 404/301 only.

### Task WP-I/J (carry, P0)

Priority order:
1. **WP-P first** (pollution ~109, flat — still blocking calendar wins)
2. `automate-blog-content-without-losing-quality` (**32** / **~29.6**) — **promote hard** on pos recovery (titles/FAQ/internal links)
3. `content-marketing-roi-b2b` (**169** / ~21.1) — keep elevated near page-2
4. `ai-content-marketing-b2b-automation` (**372** imp)
5. `blog-seo-automation` (227) — lock “seo blog automation for content teams” (~**7.2**); stop 4-imp leak to flagship
6. `b2b-content-calendar-automated-plan` (**499** / ~13.0) — titles/FAQ after scrub; stop page-2 drift
7. Light check `b2b-blog-strategy-founder-workflow` (29, deep — P3)

### Out of scope

`/admin`/`/dashboard` GA filters, compare/plugin/learn pages, homepage alts — companion Next brief.

---

*End — WordPress blog update 2026-09-22*

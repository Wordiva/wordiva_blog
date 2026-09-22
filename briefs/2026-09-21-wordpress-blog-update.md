# Wordiva Blog (WordPress) — SEO Update

**Generated:** 2026-09-21 (Asia/Dubai)  
**Project:** `/blog/**` only (WordPress)  
**Companion:** `2026-09-21-nextjs-marketing-update.md`  
**Compared to:** `2026-09-18-wordpress-blog-update.md`  
**Windows:** Recent `2026-08-22` → `2026-09-18` · Prior `2026-07-25` → `2026-08-21`  
**Sources:** GSC `sc-domain:wordiva.ai` + blog prefix · GA4 `properties/541636789`

---

## TLDR (blog)

**P0 still open / flat:** task-title pollution on calendar post **~109** imp (same as Sep 18) at pos **~7–10** — scrub still not reflected. Calendar `b2b-content-calendar-automated-plan` **480** / **13.0** (was 416 / 12.6) — **+64** imp, slight pos slip onto page-2. Biggest blog mover: ROI `content-marketing-roi-b2b` **167** / **21.2** (was 102 / 21.8) — **+65**. `ai-content-marketing-b2b-automation` **363** / **43.1** (+38 · pos gain). `blog-seo-automation` **227** / **27.7** (+11 · pos gain). Flagship **33** / **38.5** / 0 (−8 imp but **pos recovered** 46→38.5). Push: **`b2b content calendar`** (**41** / **~9.4**); **`seo blog automation for content teams`** → seo-automation (**24** / **~7.2**; query **25** / **~9.2** — 4 imp leak to flagship at ~54). Broken href still **0** in GA — keep verify-fixed. Hub `/blog/` **15** / 9.7. Organic Search landings on blog: **none** this window.

---

## Page / query deltas (blog)

| Page | Sep 18 (Aug19–Sep15) | Recent (Aug22–Sep18) | Delta |
|------|----------------------|----------------------|-------|
| `.../b2b-content-calendar-automated-plan` | 416 / 12.6 | **480** / **13.0** / 0 | **+64** · pos slip to page-2 |
| `.../ai-content-marketing-b2b-automation` | 325 / 45.2 | **363** / **43.1** / 0 | **+38** · pos gain |
| `.../blog-seo-automation` | 216 / 29.0 | **227** / **27.7** / 0 | **+11** · pos gain |
| `.../content-marketing-roi-b2b` | 102 / 21.8 | **167** / **21.2** / 0 | **+65** · biggest blog mover |
| `.../automate-blog-content-without-losing-quality` | 41 / 46.0 / 1 | **33** / **38.5** / 0 | **−8** · pos recovered |
| `.../b2b-blog-strategy-founder-workflow` | 29 / 53.3 | **29** / 53.3 / 0 | Flat |
| `/blog/` | 16 / 9.2 | **15** / **9.7** / 0 | −1 |

**Push (≤~12):** `b2b content calendar` on calendar (**41** / **~9.4**, was ~26 / ~9.6); `seo blog automation for content teams` on `blog-seo-automation` (**24** / **~7.2**; query **25** / **~9.2** — 4 imp leak to flagship at ~54); “automate content creation without looking cheap” (1 / **4** on flagship).  
**P0 pollution:** `%task` / `content-calendar-dispatch` / hellomedspa / brand-shop / wolf pack strings (**~109** combined, flat vs Sep 18) — **all** map to **calendar post** — open, not improving.  
**Deep rising:** content marketing b2b calendar (64 / 20.8), “how can we automate seo execution…” (50 / 15.6), content marketing roi for founders (6 / 19.2).

All-channel blog landings: ROI **11** (mostly Organic Social 9), calendar **11**, hub `/blog` **9**, flagship **3**, seo-automation **2**. Organic Search → blog: **0**.

---

## Issues (WordPress)

### WP-N3 — P0 open: task-title leak flat (not scrubbed)

Pollution impressions **~109** (unchanged vs Sep 18). Same calendar URL only. Scrub still not reflected in GSC. Block celebrating calendar SEO until gone.

### WP-N1 status — Broken href

Malformed path **0** sessions → **verify-fixed**, not closed. Grep WP first for `)https:`. Shared: Next companion Task NX-M after WP.

### WP-N2 — Flagship + high-imp CTR (still open)

Flagship imp still soft (**33**, −8) but **pos recovered** to ~38.5 — keep recovery push. Titles/FAQ on b2b-automation (**363**) and blog-seo-automation (**227**); lock “seo blog automation for content teams” (~**7.2** on target URL); calendar titles/FAQ for `b2b content calendar` (~9.4) **only after** WP-N3; ROI (**167**) elevate priority — title/meta + internal links while surging (watch pos 21.2).

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
2. `content-marketing-roi-b2b` (**167** / ~21.2) — **promote** while surging
3. `automate-blog-content-without-losing-quality` (recover from 33; ride pos ~38.5)
4. `ai-content-marketing-b2b-automation` (**363** imp)
5. `blog-seo-automation` (227) — lock “seo blog automation for content teams” (~**7.2**)
6. `b2b-content-calendar-automated-plan` (**480** / ~13.0) — titles/FAQ after scrub; stop page-2 drift
7. Light check `b2b-blog-strategy-founder-workflow` (29, deep — P3)

### Out of scope

`/admin`/`/dashboard` GA filters, compare/plugin/learn pages, homepage alts — companion Next brief.

---

*End — WordPress blog update 2026-09-21*

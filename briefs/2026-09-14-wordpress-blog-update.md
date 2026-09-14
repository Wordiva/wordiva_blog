# Wordiva Blog (WordPress) — SEO Update

**Generated:** 2026-09-14 (Asia/Dubai)  
**Project:** `/blog/**` only (WordPress)  
**Companion:** `2026-09-14-nextjs-marketing-update.md`  
**Compared to:** `2026-09-11-wordpress-blog-update.md`  
**Windows:** Recent `2026-08-15` → `2026-09-11` · Prior `2026-07-18` → `2026-08-14`  
**Sources:** GSC `sc-domain:wordiva.ai` + blog prefix · GA4 `properties/541636789`

---

## TLDR (blog)

**P0 new:** calendar post is ranking for leaked **`%task` / `+task` hellomedspa content-calendar-dispatch** strings (**~61** imp / pos **~8**). Treat as indexable customer-task pollution — strip/noindex before celebrating the climb. Biggest real move: `b2b-content-calendar-automated-plan` **270** imp / pos **~12.2** (was 65 / ~18.6). `ai-content-marketing-b2b-automation` **270** (+46). New post `content-marketing-roi-b2b` **40** / ~18.3. Flagship still ~pos **42.5** (70 / 1 click). Push still hot: **`seo blog automation for content teams`** → `blog-seo-automation` (**23** / **~8.6**); `b2b content calendar` (**15** / **10.1**). Broken href still **0** in GA — keep verify-fixed.

---

## Page / query deltas (blog)

| Page | Prior imp / pos | Recent imp / pos / clicks | vs Sep 11 |
|------|-----------------|---------------------------|-----------|
| `.../b2b-content-calendar-automated-plan` | — | **270** / **12.2** / 0 | **+205** · near page-2 |
| `.../ai-content-marketing-b2b-automation` | — | **270** / 49.2 / 0 | **+46** |
| `.../blog-seo-automation` | 93 / 30.2 | **173** / 34.2 / 0 | **+15** |
| `.../automate-blog-content-without-losing-quality` | 203 / 19.8 | 70 / **42.5** / 1 | Still collapsed (−14) |
| `.../content-marketing-roi-b2b` | — | **40** / **18.3** / 0 | **New** in mix |
| `.../b2b-blog-strategy-founder-workflow` | — | **23** / 53.4 / 0 | +9 |
| `/blog/` | 6 / 6.7 | 22 / 7.5 / 0 | Healthy hub |

**Push (≤~12):** `seo blog automation for content teams` on `blog-seo-automation` (**23** / **~8.6**), `b2b content calendar` (**15** / **10.1**), CMS publish long-tail (10 / **10.8** on b2b-automation), “automate content creation without looking cheap” (5 / **9.6**).  
**P0 pollution:** `%task` / `+task (routed by content-calendar-dispatch… hellomedspa…)` queries (**~61** combined) all map to **calendar post** — remove leaked task text from published HTML (or robots), then request reindex.  
**Deep rising:** ai content marketing automation (35 / 60), automated content marketing (17 / 43), marketing content automation (17 / 75).

Organic Search landings on blog: flagship (1 only). All-channel: calendar **9**, hub `/blog` **10**, roi post **2**, flagship **2**.

---

## Issues (WordPress)

### WP-N3 — NEW P0: task-title query leak on calendar post

GSC shows internal dispatcher strings (`%task` / `+task` / `content-calendar-dispatch` / hellomedspa titles) ranking on `b2b-content-calendar-automated-plan`. Almost certainly leaked task payload in post body, revision, or injected block. Strip before shipping more calendar SEO.

### WP-N1 status — Broken href

Malformed path **0** sessions recent → **verify-fixed**, not closed. Grep WP first for `)https:`; assert malformed path ≠ 200. Shared bug: Next companion Task NX-M after WP.

### WP-N2 — Flagship + high-imp CTR (still open)

Recover flagship; ship titles/FAQ on b2b-automation (**270**) and blog-seo-automation (**173**); lock push query “seo blog automation for content teams” onto seo-automation (~**8.6**); calendar now **270** / ~12 — titles/FAQ for `b2b content calendar` (~10.1) **after** WP-N3 cleanup.

---

## Coding tasks (WordPress only)

### Task WP-P — Scrub task-title leak from calendar post (P0, ~1–2h)

1. Open `b2b-content-calendar-automated-plan` in WP; grep content/revisions/custom fields for `content-calendar-dispatch`, `%task`, `+task`, `hellomedspa`.
2. Remove leaked payload; keep intentional SEO copy only.
3. Re-save, purge cache, URL Inspection → request indexing.
4. Success: those queries disappear from GSC over ~7–14d; calendar still ranks for `b2b content calendar`.

### Task WP-M — Confirm broken href closed (P0, ~30 min)

Grep `)https:`; hit old path → 404/301 only.

### Task WP-I/J (carry, P0)

Priority order:
1. **WP-P first** (pollution blocks celebrating calendar climb)
2. `automate-blog-content-without-losing-quality` (recover pos ~43)
3. `ai-content-marketing-b2b-automation` (**270** imp)
4. `blog-seo-automation` (173) — lock “seo blog automation for content teams” (~**8.6**)
5. `b2b-content-calendar-automated-plan` (**270** / ~12) — titles/FAQ for `b2b content calendar` (~10.1) after scrub
6. `content-marketing-roi-b2b` (**40** / ~18) — light title/meta + internal links (P2)
7. Light check `b2b-blog-strategy-founder-workflow` (23, deep — P3)

### Out of scope

`/admin`/`/dashboard` GA filters, compare/plugin/learn pages, homepage alts — companion Next brief.

---

*End — WordPress blog update 2026-09-14*

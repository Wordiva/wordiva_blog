# Wordiva Blog — Technical SEO Strategy Plan

**Property:** [https://wordiva.ai/blog](https://wordiva.ai/blog)  
**Theme:** `wordiva-blog-theme` (WordPress)  
**Prepared:** June 11, 2026  
**Scope:** Technical SEO, crawl/indexation, structured data, performance, and AI-search (GEO) readiness

---

## Executive Summary

Wordiva operates in a crowded AI content marketing market dominated by established players (Jasper, Writesonic, Surfer, Frase, Copy.ai) whose blogs publish 100–200+ articles with strong category architecture, author authority, and increasingly sophisticated schema for both Google and AI answer engines.

The Wordiva blog is **early-stage** (5 published posts) but sits on a theme with a solid SEO foundation (`inc/seo.php`, semantic templates, JSON-LD). Live audits reveal **critical gaps** that will limit growth regardless of content quality: broken entity signals, empty homepage metadata, 404 indexation issues, a non-standard sitemap, aggressive `robots.txt` rules, and missing AI-crawler/GEO infrastructure.

This plan prioritizes **10 technical strategies** in order of impact. Items 1–4 are foundational and should ship before scaling content volume. Items 5–8 support topical authority. Items 9–10 are amplifiers and measurement.

---

## Market Context

### Category & Positioning

| Segment | Leaders | Content angle |
|---------|---------|---------------|
| AI writing platforms | Jasper, Copy.ai, Writesonic | Templates, brand voice, workflows |
| SEO-first content tools | Surfer, Frase, Writesonic | SERP data, GEO/AEO, core updates |
| Editing / refinement | Wordtune, Grammarly | Rewriting, tone, clarity |
| Autonomous content engines | **Wordiva** (emerging) | Agentic AI, 24/7 publishing, B2B ops |

**Wordiva's differentiation:** An autonomous *content marketing engine* (strategy → draft → SEO → publish), not just a writing assistant. The blog should technically reinforce that story through case-study schema, product-adjacent how-to content, and entity links back to `wordiva.ai`.

### Search Landscape (2026)

1. **Traditional SEO** still matters: indexation, CWV, internal links, E-E-A-T.
2. **GEO (Generative Engine Optimization)** is a parallel channel: AI systems cite *passages* (200–400 tokens), not pages. Princeton GEO research (Aggarwal et al., KDD 2024) shows statistics (+40%), authoritative citations (+40%), and expert quotes (+28%) improve AI visibility.
3. **Structured data** is consumed by both Google rich results and LLM retrieval pipelines. Competitors ship dense JSON-LD graphs with stable `@id` references.
4. **Entity authority** — connecting blog, product site, authors, and social profiles via `sameAs` — is increasingly important post–Google May 2026 core update (competitors frame this as "trusting sources" over "ranking content").

---

## Competitor Analysis

### Benchmark Blogs

| Competitor | Blog URL | Est. scale | Technical strengths | Gaps Wordiva can exploit |
|------------|----------|------------|---------------------|--------------------------|
| **Writesonic** | writesonic.com/blog | 200+ posts | Category filters (AI Search, SEO, Product); `Blog` schema with `blogPost[]` array; `ItemList` on index; newsletter; GEO-first editorial | Heavy product CTAs; less B2B ops depth |
| **Frase** | frase.io/blog | 100+ posts | Granular taxonomy (GEO, AEO, AI Search); named authors; timely core-update coverage | Less autonomous-engine narrative |
| **Surfer** | surferseo.com/blog | Large library | Editor's picks, academy hub, community links, multi-format learning paths | Narrower to SEO practitioners |
| **Jasper** | jasper.ai/blog | Mature | Brand voice thought leadership; Surfer integration story | Less GEO-native |
| **Copy.ai** | copy.ai/blog | Large | GTM/workflow content; high publishing velocity | Pivoted away from long-form SEO |

### What Top Competitors Do That Wordiva Does Not (Yet)

| Capability | Writesonic | Frase | Wordiva (current) |
|------------|:----------:|:-----:|:-----------------:|
| Category/topic hub pages | ✅ | ✅ | ⚠️ Categories exist but no index UX |
| Author bylines + bios | ✅ | ✅ | ❌ Empty author in schema/meta |
| Blog index `blogPost[]` schema | ✅ | Partial | ❌ |
| Newsletter / RSS prominence | ✅ | ✅ | ⚠️ RSS linked, not surfaced |
| GEO/AI-search content pillar | ✅ | ✅ | ⚠️ 1 post touches B2B engine |
| `llms.txt` or AI crawler policy | ✅ (editorial) | Partial | ❌ |
| Stable `@id` entity graph | ✅ | Partial | ❌ |
| 100+ indexed URLs | ✅ | ✅ | ❌ (~5 posts) |

---

## Current State Audit (Live + Theme)

### Strengths (theme & deployment)

- JSON-LD for Organization, WebSite, Article, BreadcrumbList, Blog (`wordiva-blog-theme/inc/seo.php`)
- Open Graph + Twitter Cards on singles
- Semantic HTML on `single.php` (`itemscope`, Article microdata, reading time)
- Canonical URLs on posts
- CloudFront CDN, security headers, PWA manifest
- Related posts with `ItemList` schema
- Category-based URL structure (`/ai-content-marketing/build-ai-content-engine`)
- Featured image OG tags working on posts

### Critical Issues (fix first)

| Issue | Evidence | SEO impact |
|-------|----------|------------|
| Empty homepage meta description | `meta description=""` on `/blog/` | Poor CTR, weak relevance signal |
| Organization entity mismatch | Schema name = "Wordiva Blog", not "Wordiva" | Split entity; weak brand graph to `wordiva.ai` |
| Empty author signals | `"author":{"name":""}` in Article schema | E-E-A-T penalty; AI citation risk |
| 404 pages set to `index, follow` | `robots.txt` 404 returns indexable page with canonical to `/blog/` | Index bloat, crawl waste |
| Broken breadcrumb URL | `"item":"https://wordiva.ai/blog/blog/"` | Invalid structured data |
| Missing OG default image | `wordiva-og-default.jpg` referenced but not in theme assets | Broken social previews for pages without images |
| Non-standard sitemap | `?sitemap=xml` query param; `robots.txt` blocks `/*?*` | Sitemap may be uncrawlable |
| Aggressive robots rules | Disallow feeds, query strings, category pagination patterns | May block legitimate URLs |
| No root `robots.txt` for product site | `wordiva.ai/robots.txt` → 404 (Next.js) | No cross-property sitemap discovery |
| Duplicate canonical tags | Two `<link rel="canonical">` on posts | Conflicting signals |
| JS/CSS bloat | 5+ navigation scripts enqueued | INP/LCP risk at scale |
| No `llms.txt` | — | Missed AI discovery layer |
| Thin content volume | 5 posts | Cannot compete on long-tail yet |

---

## Prioritized Strategies (Top 10)

### Priority 1 — Crawl & Index Foundation

**Why first:** No amount of content or schema helps if crawlers cannot discover, parse, and index pages cleanly. Competitors with 200+ URLs win partly on *coverage*.

**Actions:**

1. **Ship a standard XML sitemap** at `https://wordiva.ai/blog/sitemap.xml` (use Yoast SEO, Rank Math, or WordPress 5.5+ native sitemaps). Deprecate `?sitemap=xml`.
2. **Rewrite `wordiva_robots_txt()` in `inc/seo.php`:**
   - Remove `Disallow: /*?*` (blocks sitemap and filtered views).
   - Stop disallowing `/feed/` (RSS aids discovery; competitors expose it).
   - Add explicit `Allow` for AI crawlers: `GPTBot`, `ChatGPT-User`, `ClaudeBot`, `PerplexityBot`, `Google-Extended`.
   - Point sitemap: `Sitemap: https://wordiva.ai/blog/sitemap.xml`.
3. **Add `robots.txt` at `wordiva.ai` root** (Next.js) referencing the blog sitemap and allowing crawlers on `/blog/`.
4. **Fix 404 handling:** emit `noindex, follow` on 404 templates; never canonical 404s to homepage.
5. **Resolve trailing-slash redirects** — ensure one canonical form (prefer no trailing slash to match current canonicals).
6. **Submit sitemap in Google Search Console** as a URL-prefix property for `/blog/` (or domain property).

**Theme files:** `inc/seo.php`, `404.php`  
**Success metrics:** 100% of published posts in sitemap; 0 indexed 404 URLs; GSC "Page indexing" errors → 0.

---

### Priority 2 — Unified Entity & Schema Graph

**Why second:** Writesonic and Frase tie blog content to a single brand entity with `@id`, `sameAs`, and `publisher` references. Wordiva currently publishes as "Wordiva Blog" — a disconnected entity from the product.

**Actions:**

1. **Rebrand schema Organization** to parent entity:
   ```json
   {
     "@type": "Organization",
     "@id": "https://wordiva.ai/#organization",
     "name": "Wordiva",
     "url": "https://wordiva.ai",
     "logo": "https://wordiva.ai/icon.png",
     "sameAs": [
       "https://twitter.com/wordiva",
       "https://linkedin.com/company/wordiva"
     ],
     "knowsAbout": ["AI content marketing", "content automation", "SEO"]
   }
   ```
2. **Model blog as `Blog` sub-entity** with `"isPartOf": {"@id": "https://wordiva.ai/#organization"}`.
3. **Upgrade Article → `BlogPosting`** with `@id` per URL; link `"publisher": {"@id": "https://wordiva.ai/#organization"}`.
4. **Fix breadcrumb** — replace hardcoded `/blog/blog/` with `get_permalink(get_option('page_for_posts'))` or `home_url('/blog/')`.
5. **Add `WebPage` + `mainEntity`** linking between post and BlogPosting `@id`.
6. **Cross-link in header/footer** — already links to `wordiva.ai`; add `rel="home"` on blog logo pointing to product site (present) and `rel="alternate"` between blog and main site where appropriate.

**Theme files:** `inc/seo.php`, `header.php`, `footer.php`  
**Success metrics:** Rich Results Test passes with 0 errors; Search Console enhancement reports show valid items.

---

### Priority 3 — E-E-A-T Author & Person Schema

**Why third:** Google's quality systems and AI citation models both favor identifiable experts. Live posts show empty `article:author` and `"name":""` in Person schema — a direct trust deficit vs Frase/Writesonic bylines.

**Actions:**

1. **Require author display name** on all posts (block publish if empty).
2. **Populate author profiles:** bio, headshot, job title, LinkedIn URL (user meta fields).
3. **Extend Person schema:**
   ```json
   {
     "@type": "Person",
     "@id": "https://wordiva.ai/blog/author/{slug}/#person",
     "name": "Rubia",
     "jobTitle": "Content Strategist",
     "sameAs": ["https://linkedin.com/in/..."],
     "worksFor": {"@id": "https://wordiva.ai/#organization"}
   }
   ```
4. **Surface visible bylines** on cards and singles (template already supports author block; ensure data exists).
5. **Create author archive pages** with `ProfilePage` schema and links to their posts.

**Theme files:** `inc/seo.php`, `single.php`, `template-parts/content-card.php`, `inc/post-meta.php`  
**Success metrics:** 100% of posts have non-empty author in schema; author pages indexed.

---

### Priority 4 — Core Web Vitals & Front-End Performance

**Why fourth:** CWV is a ranking multiplier (2026 benchmarks: LCP ≤ 2.5s, INP ≤ 200ms, CLS ≤ 0.1 at p75). Theme enqueues **six separate navigation JS files** plus social sharing — excessive for a content site.

**Actions:**

1. **Consolidate navigation JS** into one bundle (`navigation.js`); remove redundant `navigation-simple.js`, `navigation-scroll.js`, `mobile-menu-fix.js` after merge.
2. **Defer non-critical scripts**; keep only `main.js` globally, load sharing JS on singles only.
3. **Reduce CSS payloads** — merge `navigation.css`, `navigation-mobile-fix.css`, `accessibility.css` into built/minified bundle for production.
4. **Remove inline critical CSS duplication** if it overlaps with enqueued styles.
5. **Image pipeline:** WebP/AVIF via WordPress or CDN; explicit `width`/`height` on all images (CLS); `fetchpriority="high"` on LCP hero image only.
6. **Audit third-party scripts** (analytics, fonts) — self-host fonts or use `font-display: swap`.
7. **Monitor in GSC CWV report** and Lighthouse CI on deploy.

**Theme files:** `inc/enqueue-scripts.php`, `assets/js/*`, `assets/css/*`  
**Success metrics:** All blog templates "Good" in CrUX/GSC; Lighthouse Performance ≥ 90 mobile.

---

### Priority 5 — Topic Cluster Architecture & URL Taxonomy

**Why fifth:** Competitors organize content into hubs (Writesonic: AI Search, SEO, Product; Frase: GEO, AEO, Content Strategy). Wordiva has categories in URLs but no hub UX or index-level discovery.

**Actions:**

1. **Define 4–6 pillar categories** aligned to Wordiva ICP:
   - AI Content Marketing
   - B2B Content Operations
   - SEO & GEO Strategy
   - Brand Intelligence
   - Product & Updates
2. **Build `archive.php` category hubs** with unique H1, 150+ word intro copy, and curated post lists (not just reverse chronology).
3. **Add category navigation** to blog index (filter chips like Writesonic).
4. **Implement consistent slug pattern:** `/blog/{category}/{post-slug}` (already in use — maintain).
5. **Internal link rules:** every post links to 2+ sibling posts + 1 pillar hub.
6. **Add `CollectionPage` schema** on category archives.

**Theme files:** `archive.php`, `index.php`, `inc/seo.php`  
**Success metrics:** Category pages indexed; impressions per hub in GSC within 90 days of content scale-up.

---

### Priority 6 — Blog Index Schema Enrichment

**Why sixth:** Writesonic's blog homepage ships a `Blog` node with 10+ embedded `BlogPosting` objects plus an `ItemList` of 20 items — giving crawlers and LLMs a machine-readable map of fresh content. Wordiva's blog index schema has empty `description` and no `blogPost` array.

**Actions:**

1. **Populate Blog schema on index:**
   - `description`: "Insights on AI-powered content marketing, B2B growth, and autonomous publishing from Wordiva."
   - `blogPost[]`: latest 10 posts with headline, url, datePublished, image, author.
2. **Add `ItemList` schema** for visible post grid (pattern from Writesonic).
3. **Fix homepage meta title/description** in WordPress Settings → General (currently empty tagline → empty OG description).
4. **Add `BlogPosting` preview cards** with `itemprop` alignment to JSON-LD.

**Theme files:** `inc/seo.php`, `index.php`  
**Success metrics:** Valid Blog + ItemList in Rich Results Test; improved homepage CTR in GSC.

---

### Priority 7 — GEO & AI Crawler Readiness

**Why seventh:** As an AI content platform, Wordiva should *practice what it sells*. GEO is not a replacement for SEO but a high-growth parallel channel. Writesonic and Frase already dominate "AI search visibility" queries.

**Actions:**

1. **Publish `/llms.txt`** at `wordiva.ai/llms.txt` (and optionally `/blog/llms.txt`) listing:
   - Product pages
   - Top 10 blog posts with one-line descriptions
   - Documentation / pricing / about
2. **Allow AI crawlers** in robots.txt (see Priority 1).
3. **Content structure guidelines** (editorial, supported by theme):
   - Question-format H2s
   - 40–60 word "answer capsules" after headings
   - Statistics with named sources
   - FAQ sections on pillar posts → `FAQPage` schema (only when genuine)
4. **Ensure server-side rendering** — WordPress already SSRs; avoid client-only content blocks.
5. **Bing Webmaster Tools** — secondary pipeline for ChatGPT citation source data.

**Theme files:** new `llms.txt` (deployed at web root), `inc/seo.php` (FAQ schema helper)  
**Success metrics:** Brand cited in ChatGPT/Perplexity for 3+ target queries; monitor via manual prompts or Otterly/Writesonic-style tools.

---

### Priority 8 — Internal Linking & Content Graph

**Why eighth:** With low URL count, every link matters. Theme already has related posts (`single.php`) — extend into a deliberate graph.

**Actions:**

1. **Add visible breadcrumbs** on singles and archives (schema exists but UI may be missing) — match BreadcrumbList exactly.
2. **Contextual in-content links** — auto-suggest related posts in sidebar or after H2 sections.
3. **Pillar ↔ cluster linking:** hub pages link down; all cluster posts link up to hub.
4. **Link to product** with descriptive anchors ("Wordiva content engine", not "click here") from relevant posts.
5. **HTML sitemap page** at `/blog/sitemap/` for users and crawlers.
6. **Fix duplicate canonical** — ensure theme + SEO plugin don't double-output.

**Theme files:** `single.php`, new `template-parts/breadcrumbs.php`, `inc/helper-functions.php`  
**Success metrics:** Avg internal links/post ≥ 5; reduced orphan pages in Screaming Frog crawls.

---

### Priority 9 — Rich Results & Social Preview Hardening

**Why ninth:** Amplifies click-through once indexation and entities are fixed.

**Actions:**

1. **Add missing `wordiva-og-default.jpg`** (1200×630) to `assets/images/`.
2. **Validate all OG images** — fix `og:image:type` when PNG served as `image/jpeg`.
3. **Add `og:image:alt`** for accessibility and social platforms.
4. **Implement `FAQPage` schema** on posts with FAQ blocks (Gutenberg FAQ or custom block).
5. **Consider `HowTo` schema** for tutorial posts (e.g., "Build an AI Content Engine").
6. **Add `speakable` CSS selector** markup for voice/AI excerpt identification (experimental, low effort).
7. **Newsletter capture** on blog index (competitor pattern) — indirect SEO via engagement signals.

**Theme files:** `inc/seo.php`, `assets/images/`, `inc/post-meta.php`  
**Success metrics:** Social debuggers (Facebook, Twitter, LinkedIn) show correct previews; FAQ rich results where eligible.

---

### Priority 10 — Measurement, Benchmarking & Iteration Loop

**Why tenth:** Technical SEO without measurement drifts. Competitors publish weekly and track GEO KPIs (citation share, AI visibility).

**Actions:**

1. **Google Search Console** — domain + `/blog/` monitoring; weekly index coverage review.
2. **GA4** — blog-specific content groups; track scroll depth, CTA clicks to `wordiva.ai/register`.
3. **Core Web Vitals** — monthly CrUX check per template (home, single, archive).
4. **Competitive crawl** — quarterly Screaming Frog on Writesonic, Frase, Surfer blogs; diff schema and hub structure.
5. **AI citation tracking** — manual or tool-based prompts for 20 target queries (e.g., "AI content engine B2B", "agentic content marketing").
6. **Failed search log** — theme already tracks `wordiva_failed_searches` option; review monthly for content gaps.
7. **Automate audits** — Lighthouse CI in `deploy/deploy.sh` pipeline.

**Success metrics:** Monthly dashboard: indexed pages, avg position (top 20 queries), CWV status, AI citation count, organic signups attributed to blog.

---

## Implementation Roadmap

| Phase | Timeline | Strategies | Effort |
|-------|----------|------------|--------|
| **Phase 0 — Firefighting** | Week 1–2 | #1 Crawl foundation, #2 Entity graph (critical fixes), homepage meta | 3–5 dev days |
| **Phase 1 — Trust signals** | Week 3–4 | #3 Authors, #6 Blog index schema, #9 OG image/social | 3–4 dev days |
| **Phase 2 — Performance** | Week 5–6 | #4 CWV / JS consolidation | 2–3 dev days |
| **Phase 3 — Scale-ready** | Week 7–10 | #5 Topic hubs, #7 GEO/llms.txt, #8 Internal linking | 4–6 dev days + editorial |
| **Phase 4 — Ongoing** | Continuous | #10 Measurement, content velocity (target 2–4 posts/month → 8+) | Recurring |

---

## Content Velocity Note (Technical Dependency)

Technical SEO unlocks discovery, but **competitors win on volume + topical breadth**. Writesonic publishes multiple GEO/AI-search articles per week; Wordiva has 5 posts total. After Phase 0–1, editorial should target:

1. **Pillar:** "AI Content Engine" (owned term — already started)
2. **GEO cluster:** "How to get cited in ChatGPT/Perplexity" (Frase/Writesonic battleground)
3. **B2B ops cluster:** Manual content cost, capacity, automation ROI
4. **Comparison cluster:** "Wordiva vs Jasper/Copy.ai" (high intent, later stage)

Technical theme support: category hubs, author pages, FAQ/HowTo schema, and fast CWV must be in place *before* scaling to 50+ URLs.

---

## Quick-Win Checklist (Theme Repo)

- [ ] Fix `wordiva_robots_txt()` — remove `/*?*` and feed blocks
- [ ] Add `wordiva-og-default.jpg` asset
- [ ] Fix breadcrumb `/blog/blog/` bug in `inc/seo.php`
- [ ] Set `noindex` on 404 in `seo.php` or `404.php`
- [ ] Change Organization schema from "Wordiva Blog" → "Wordiva" with `@id`
- [ ] Populate WordPress tagline / blog description (fixes empty homepage meta)
- [ ] Consolidate navigation JS in `enqueue-scripts.php`
- [ ] Add author validation before publish
- [ ] Deploy `llms.txt` at site root
- [ ] Install or enable native WordPress sitemap; update robots reference

---

## Appendix: Key Theme SEO Files

| File | Role |
|------|------|
| `inc/seo.php` | Meta tags, JSON-LD, sitemap, robots.txt |
| `single.php` | Article microdata, related posts ItemList |
| `index.php` | Blog homepage, featured post logic |
| `inc/enqueue-scripts.php` | Asset loading (performance lever) |
| `inc/post-meta.php` | Featured post, reading time meta |
| `404.php` | Error handling (indexation) |
| `archive.php` | Category/tag hubs |

---

## References

- [Writesonic Blog](https://writesonic.com/blog) — schema benchmark (Blog + ItemList + blogPost array)
- [Frase Blog](https://www.frase.io/blog) — GEO/AEO taxonomy model
- [Surfer Blog](https://surferseo.com/blog) — content hub + academy pattern
- [Modern Technical SEO Checklist 2026](https://horatos.ai/insights/modern-technical-seo-checklist)
- [GEO: Generative Engine Optimization (Princeton / Aggarwal et al.)](https://arxiv.org/abs/2311.09735)
- [Google Search Central — Core Web Vitals](https://developers.google.com/search/docs/appearance/core-web-vitals)
- [llms.txt specification](https://llmstxt.org/)

---

*This document is a living strategy. Revisit after each major theme deploy, Google core update, or competitor schema change.*

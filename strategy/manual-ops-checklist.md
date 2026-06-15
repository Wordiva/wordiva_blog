# Manual SEO Operations Checklist — Wordiva Blog

**Blog:** https://wordiva.ai/blog  
**Main site:** https://wordiva.ai  
**Companion:** [technical-seo-strategy.md](./technical-seo-strategy.md)

---

## Search Console and Bing

- [ ] Submit `https://wordiva.ai/sitemap.xml` in Google Search Console (main site repo)
- [ ] Submit `https://wordiva.ai/blog/wp-sitemap.xml` in Google Search Console
- [ ] Verify both properties in Bing Webmaster Tools
- [ ] Request indexing for homepage and top 5 posts after major SEO deploy

## Analytics

- [ ] Set GA4 Measurement ID in **Appearance → Customize → SEO & Marketing** (blog theme)
- [ ] Confirm gtag loads only when ID is set (view page source)
- [ ] Link GA4 property to Search Console

## Monthly maintenance

- [ ] Screaming Frog crawl of `/blog/` — fix 4xx, redirect chains, missing meta
- [ ] Core Web Vitals review in GSC + Lighthouse mobile on a sample post
- [ ] Review `wordiva_failed_searches` in WP admin (Tools) for content gaps
- [ ] Re-run `./deploy/verify-phase.sh all` after theme updates

## Content velocity

Target **2 posts/week** across:

- Agentic AI
- WordPress automation
- GEO / AEO
- B2B SaaS content marketing
- Wordiva story / product updates

## Per-post editorial checklist

Before publish:

- [ ] Target keyword in slug and H1
- [ ] Featured image 1200×630 with descriptive alt text
- [ ] 3–5 internal links (blog posts + product pages on wordiva.ai)
- [ ] FAQ section with **Enable FAQPage schema** checked when ≥2 Q&A pairs
- [ ] Answer-capsule H2 structure for GEO (direct answer under question headings)
- [ ] Author display name filled; bio and job title when possible
- [ ] Wrap key summary paragraphs in `.wordiva-speakable` class if using speakable optimization

## Backfill internal links (5 existing posts)

Add 3–5 contextual internal links each:

1. `/blog/ai-content-marketing/build-ai-content-engine`
2. `/blog/wordiva-story/the-hidden-cost-of-manual-content-marketing-its-more-than-you-think`
3. `/blog/content-marketing/no-1-struggle-for-small-businesses-were-fixing-that`
4. `/blog/content-marketing/the-vision-a-24-7-content-marketing-engine-that-never-sleeps`
5. `/blog/wordiva-story/introducing-wordiva-where-words-meet-confidence`

Link to category hubs, related posts, and product URLs (`/compare`, `/integrations/wordpress`, `/learn/generative-engine-optimization`).

## AI citation monitoring

Track visibility for queries such as:

- “agentic AI content marketing”
- “WordPress blog automation AI”
- “generative engine optimization guide”
- “AI writing tool comparison”
- “automated content marketing for SaaS”

(See [technical-seo-strategy.md](./technical-seo-strategy.md) for full prompt list.)

## Main site (Next.js repo)

Implement per [wordiva-main-site-seo.md](./wordiva-main-site-seo.md):

- Root `robots.txt` referencing blog sitemap
- Root `sitemap.xml` for marketing pages
- Root `llms.txt`
- Shared Organization JSON-LD (`@id: https://wordiva.ai/#organization`)
- `noindex` on `/dashboard` and site 404

## Deploy verification

After each theme deploy:

```bash
cd /path/to/blog_theme
./deploy/deploy.sh
./deploy/verify-phase.sh all
```

Then complete [deploy/visual-checklist.md](../deploy/visual-checklist.md) at mobile and desktop widths.

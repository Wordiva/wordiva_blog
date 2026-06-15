# Visual QA Checklist — Wordiva Blog SEO Phases

Run after each deploy against production (`https://wordiva.ai/blog/`).

## Viewports

| Viewport | Size |
| -------- | ---- |
| Mobile | 375×812 |
| Desktop | 1280×800 |

## Baseline URLs (every phase)

- https://wordiva.ai/blog/
- https://wordiva.ai/blog/ai-content-marketing/build-ai-content-engine
- https://wordiva.ai/blog/category/ai-content-marketing
- https://wordiva.ai/blog/author/rubia
- https://wordiva.ai/blog/this-url-does-not-exist
- https://wordiva.ai/blog/?s=test

## Checklist

### Header and navigation

- [ ] Logo aligned; no overlap with nav links
- [ ] Mobile hamburger opens and closes; menu fully visible
- [ ] No body scroll lock bugs when menu open
- [ ] Desktop nav links clickable

### Breadcrumbs (Phase 1+)

- [ ] Single post: Home → Blog → Category → Title
- [ ] Category archive: Home → Blog → Category name
- [ ] No duplicate breadcrumb bars
- [ ] Mobile: breadcrumbs truncate gracefully (no horizontal scroll)

### Homepage (Phase 4–5)

- [ ] Hero title and subtitle visible
- [ ] Category chips wrap on mobile
- [ ] RSS link visible in hero
- [ ] Featured post and card grid aligned
- [ ] Newsletter block (if Customizer URL set) readable on mobile

### Single post (Phase 7–8)

- [ ] Title, author avatar, featured image render
- [ ] Content width comfortable on desktop
- [ ] “Explore this topic” block visible
- [ ] Product links block spacing OK
- [ ] Sticky CTA bottom-right; does not cover footer or nav
- [ ] Share buttons styled correctly
- [ ] Related posts grid matches index cards

### Archives

- [ ] Category H1 is category name only (not “Category: …”)
- [ ] Intro paragraph readable
- [ ] Post grid matches index style

### Footer (Phase 7+)

- [ ] Compare, Integrations, RSS links present and clickable
- [ ] Social icons visible
- [ ] Links wrap correctly on mobile

### Global

- [ ] No horizontal scrollbar on any page
- [ ] No red console JS errors
- [ ] Tab focus rings visible on interactive elements
- [ ] Link colors distinguishable from body text

## Optional evidence

Save screenshots to `deploy/screenshots/phase-N-{mobile,desktop}-{page}.png` (gitignored).

# Wordiva Blog Theme — Premium Redesign Upgrade

Upgrade the WordPress theme at `blog_theme/wordiva-blog-theme` to match the new wordiva.ai premium design: dark-by-default (with light toggle), Space Grotesk display type, indigo/aurora/glass styling, the two-layer mega menu, and the 6-column marketing footer — with a full restyle of all templates.

## Decisions (confirmed)
- **Dark default + light toggle**, sharing the `wordiva-theme` localStorage key. Since the blog lives at `wordiva.ai/blog` (same origin as the main site), the preference carries over automatically.
- **Hardcoded mega-menu** links in PHP mirroring `nav-menu.ts`, all URLs derived from the existing `wordiva_get_main_site_url()` Customizer setting.
- **Full restyle**: header, footer, cards, single post, archive, search, comments, 404.

## Current vs Target
| | Current theme | Target (main site parity) |
|---|---|---|
| Mode | Light only (`#fff !important` inline) | Dark default, light via `[data-theme]` toggle |
| Nav | Flat: Features / How it works / Pricing / Blog | 2-layer: Product · Solutions · Resources · Compare · Pricing + mega panels |
| Footer | Logo + socials + flat link row | 6 columns (Brand/Product/Compare/Use cases/Benefits/Resources) + legal row |
| Type | System font stack | Space Grotesk (display) + system (body) |
| Palette | Electric blue `#2f80ff` | Indigo ramp (`#4f46e5` family) + slate surfaces |

## Implementation

### 1. Design tokens (`style.css` `:root`, `assets/css/critical.css`)
- Replace the token block with **semantic dual-theme tokens**: `--w-bg`, `--w-surface`, `--w-surface-2`, `--w-border`, `--w-text`, `--w-text-2`, `--w-text-3`, `--w-brand` (indigo-600), `--w-brand-hover`, plus glass/shadow/radius tokens. Dark values on `:root`, light overrides under `[data-theme="light"]`.
- **Alias the old vars** (`--wordiva-electric-blue → var(--w-brand)`, `--wordiva-charcoal-dark → var(--w-text)`, `--wordiva-white → var(--w-bg)`, `--wordiva-surface*`, `--wordiva-border-light`, `--wordiva-text-*`, `--wordiva-gray-*`) so the ~2,400 existing rules recolor automatically; then sweep remaining hardcoded hexes (`#fff`, `#f8fafc`, `#e2e8f0`, `rgba(43,43,43,…)` shadows) to tokens.
- Add `.font-display` (Space Grotesk) for headings/logo, subtle aurora radial background on body, `.w-glass` card utility. Motion gated behind `prefers-reduced-motion`.

### 2. Theme toggle + FOUC guard
- `inc/enqueue-scripts.php`: print an early `wp_head` inline script (priority 0) reading `localStorage['wordiva-theme']` (default **dark**) and setting `data-theme` on `<html>` — same logic as `layout.tsx`.
- **Remove** the `body … background-color:#FFFFFF !important` inline CSS.
- Enqueue Space Grotesk from Google Fonts (preconnects already exist).
- `header.php`: sun/moon toggle button next to Sign In; handler added to `assets/js/navigation.js` (toggle + persist + swap icon). Mirror the main site's header-variant toggle styling.

### 3. Mega menu (`header.php`, new `inc/nav-links.php`, `assets/css/navigation.css`)
- New `inc/nav-links.php`: `wordiva_get_nav_menu()` returning the same structure as `buildNavMenu()` — Product (Features ×2 + Integrations ×3 + WP plugin), Solutions (Use cases ×9 + Benefits ×4), Resources (Guides ×7 + Blog/Plugin), Compare (×9), Pricing — with descriptions for feature/integration items; plus `wordiva_get_footer_links()` mirroring `MarketingFooter.tsx`. All URLs = `wordiva_get_main_site_url() . '/path'`; Blog links stay local.
- Desktop: pure CSS hover/focus-within mega panels (same pattern as `MarketingHeader.tsx`) with **opaque panel background** (lesson from the Next.js fix: no nested backdrop-blur), `z-index` above content, chevron rotation.
- Mobile: drawer becomes grouped accordion (top-level entries expand to grouped links); reuse existing toggle JS, add accordion handling.
- Keep breadcrumbs, skip link, ARIA attributes, keyboard nav (extend arrow-key logic to panel links).

### 4. Footer (`footer.php`)
- Rebuild as the 6-column grid from `MarketingFooter.tsx`: brand blurb + 5 social icons (existing SVGs), Product / Compare / Use cases / Benefits / Resources columns from `wordiva_get_footer_links()`, then a bottom bar: © line + Features / Pricing / Get Started / Privacy / Terms / Cookies / Acceptable Use.
- Keep `wordiva_render_logo()`, theme-mod social URL overrides, RSS link (move into Resources).

### 5. Full template restyle (CSS-led, minimal markup churn)
- **Cards** (`template-parts/content-card.php`, `content-excerpt.php`): dark glass cards, 1px slate border, indigo hover border + lift, rounded-2xl, category chip pills.
- **Single** (`single.php`): reading surface on `--w-surface`, updated meta/author box/TOC/share buttons/related cards; keep all schema markup untouched.
- **Archive/index/search** (`archive.php`, `index.php`, `search.php`, `searchform.php`): hero strips with gradient headline accent, restyled filters/pagination/search field.
- **Misc**: `comments.php`, `404.php`, `page.php`, `page-contact.php` forms, `sticky-cta.php` (indigo pill), `template-parts/product-links.php`.
- **Copy rule**: generic CTA copy says “your blog website”; WordPress naming stays only where it is genuinely about WordPress (plugin links, integration links).
- Inline styles in `enqueue-scripts.php` (chips, newsletter, product links) re-tokened.

### 6. Housekeeping
- `functions.php`: login accent → indigo; block-pattern colors updated.
- Version bump to **2.0.0** in `style.css` header, `readme.txt`; note in `README.md`.
- `inc/customizer.php`: register the few new theme mods only if needed (base URL already exists).

## Verification
- `php -l` on every touched PHP file.
- Static review of panel z-index/opacity and toggle script.
- If you have a local WP (or staging), smoke-test: home, single post, category, search, 404, both themes, mobile drawer. I can also produce a deployable zip at the end.

## Out of scope
- Wordiva Article Publisher plugin, wp-admin styling beyond login accent, content changes to posts.

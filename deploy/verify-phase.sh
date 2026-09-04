#!/usr/bin/env bash
set -euo pipefail

# Verify SEO implementation phases against production
# Usage: ./deploy/verify-phase.sh <0-9|all>

BASE_URL="${WORDIVA_BLOG_URL:-https://wordiva.ai/blog}"
ROOT_URL="${WORDIVA_ROOT_URL:-https://wordiva.ai}"
SAMPLE_POST="${WORDIVA_SAMPLE_POST:-${BASE_URL}/ai-content-marketing/build-ai-content-engine}"
AUTHOR_URL="${WORDIVA_AUTHOR_URL:-${BASE_URL}/author/rubia}"
FAKE_404="${BASE_URL}/this-url-does-not-exist-seo-test"
SEARCH_URL="${BASE_URL}/?s=test"

PASS=0
FAIL=0

log_pass() { printf '  ✓ %s\n' "$*"; PASS=$((PASS + 1)); }
log_fail() { printf '  ✗ %s\n' "$*" >&2; FAIL=$((FAIL + 1)); }

assert_http_ok() {
  local url="$1" label="$2"
  local code
  code="$(curl -sS -o /dev/null -w '%{http_code}' -L --max-time 30 "$url" || echo "000")"
  if [[ "$code" =~ ^2 ]]; then
    log_pass "${label} (HTTP ${code})"
  else
    log_fail "${label} expected 2xx, got HTTP ${code} — ${url}"
  fi
}

assert_body_contains() {
  local url="$1" pattern="$2" label="$3"
  local body
  body="$(curl -sS -L --max-time 30 "$url" || true)"
  if grep -Eq "$pattern" <<< "$body"; then
    log_pass "$label"
  else
    log_fail "$label — pattern not found: ${pattern}"
  fi
}

assert_body_not_contains() {
  local url="$1" pattern="$2" label="$3"
  local body
  body="$(curl -sS -L --max-time 30 "$url" || true)"
  if grep -Eq "$pattern" <<< "$body"; then
    log_fail "$label — unexpected pattern: ${pattern}"
  else
    log_pass "$label"
  fi
}

assert_canonical_count() {
  local url="$1" expected="$2" label="$3"
  local body count
  body="$(curl -sS -L --max-time 30 "$url" || true)"
  count="$(grep -c 'rel="canonical"' <<< "$body" || true)"
  if [[ "$count" -eq "$expected" ]]; then
    log_pass "$label (${count} canonical tag(s))"
  else
    log_fail "$label — expected ${expected}, found ${count}"
  fi
}

phase_0() {
  printf '\n=== Phase 0: Crawl and index foundation ===\n'
  assert_http_ok "${BASE_URL}/" "Blog home"
  assert_body_contains "${BASE_URL}/" 'meta name="description" content="[^"]{20,}' "Meta description populated"
  assert_http_ok "${ROOT_URL}/robots.txt" "robots.txt (domain root)"
  local robots
  robots="$(curl -sS -L --max-time 30 "${ROOT_URL}/robots.txt" || true)"
  if grep -qF 'Disallow: /*?' <<< "$robots"; then
    log_fail "robots.txt must not contain Disallow: /*?*"
  else
    log_pass "No aggressive /*?* disallow"
  fi
  if grep -q 'OAI-SearchBot' <<< "$robots"; then
    log_pass "OAI-SearchBot in robots.txt"
  else
    log_fail "OAI-SearchBot missing from robots.txt"
  fi
  if grep -qE 'Disallow: /blog' <<< "$robots"; then
    log_fail "robots.txt must not disallow /blog"
  else
    log_pass "robots.txt does not block /blog"
  fi
  assert_http_ok "${BASE_URL}/wp-sitemap.xml" "wp-sitemap.xml"
  assert_body_contains "${FAKE_404}" 'noindex, nofollow' "404 noindex,nofollow"
  assert_body_not_contains "${FAKE_404}" 'rel="canonical"[^>]+/blog/?"' "404 has no blog-home canonical"
  assert_body_contains "${SEARCH_URL}" 'noindex' "Search results noindex"
}

phase_1() {
  printf '\n=== Phase 1: Entity and schema graph ===\n'
  assert_body_contains "${BASE_URL}/" '"Wordiva\.ai"' "Organization name in JSON-LD"
  assert_body_contains "${BASE_URL}/" 'wordiva_ai\.png' "Organization logo URL"
  assert_body_contains "${BASE_URL}/" '"@id":"https://wordiva\.ai/#organization"' "Organization @id"
  assert_body_not_contains "${SAMPLE_POST}" 'blog/blog' "No /blog/blog/ in breadcrumbs"
  assert_body_contains "${SAMPLE_POST}" '"@type":"BlogPosting"' "BlogPosting schema on sample post"
  assert_canonical_count "${SAMPLE_POST}" 1 "Single canonical tag"
}

phase_2() {
  printf '\n=== Phase 2: Authors (E-E-A-T) ===\n'
  assert_body_contains "${SAMPLE_POST}" '"author":\{"@type":"Person",("@id":"[^"]+",)?"name":"[^"]+' "Author Person in schema"
  assert_http_ok "${AUTHOR_URL}" "Author archive"
  assert_body_contains "${AUTHOR_URL}" 'ProfilePage|"@type":"Person"' "Author ProfilePage or Person schema"
}

phase_3() {
  printf '\n=== Phase 3: Performance ===\n'
  local home_body
  home_body="$(curl -sS -L --max-time 30 "${BASE_URL}/" || true)"
  if grep -q 'social-sharing\.js' <<< "$home_body"; then
    log_fail "social-sharing.js should not load on homepage"
  else
    log_pass "social-sharing.js absent on homepage"
  fi
  for asset in navigation.js main.js; do
    assert_http_ok "${ROOT_URL}/wp-content/themes/wordiva-blog-theme/assets/js/${asset}" "JS asset ${asset}"
  done
  assert_http_ok "${ROOT_URL}/wp-content/themes/wordiva-blog-theme/assets/css/navigation.css" "navigation.css"
}

phase_4() {
  printf '\n=== Phase 4: Topic hubs ===\n'
  for slug in agentic-ai ai-content-marketing content-marketing wordiva-story; do
    assert_http_ok "${BASE_URL}/category/${slug}" "Category /${slug}"
    assert_body_contains "${BASE_URL}/category/${slug}" 'CollectionPage' "CollectionPage on /${slug}"
  done
  assert_body_contains "${BASE_URL}/" 'wordiva-category-chip|/category/' "Category chips on index"
}

phase_5() {
  printf '\n=== Phase 5: Blog index schema ===\n'
  assert_body_contains "${BASE_URL}/" '"@type":"Blog"' "Blog schema on homepage"
  assert_body_contains "${BASE_URL}/" '"blogPost":\[' "blogPost array on homepage"
  assert_body_contains "${BASE_URL}/" '"@type":"ItemList"' "ItemList on homepage"
  assert_body_contains "${BASE_URL}/" 'property="og:description" content="[^"]{20,}' "OG description on homepage"
}

phase_6() {
  printf '\n=== Phase 6: GEO and llms.txt ===\n'
  local llms
  llms="$(curl -sS -L --max-time 30 "${BASE_URL}/llms.txt" || true)"
  if head -1 <<< "$llms" | grep -q '# Wordiva'; then
    log_pass "llms.txt H1"
  else
    log_fail "llms.txt must start with # Wordiva"
  fi
  if grep -q '## Blog' <<< "$llms"; then
    log_pass "llms.txt Blog section"
  else
    log_fail "llms.txt missing ## Blog section"
  fi
}

phase_7() {
  printf '\n=== Phase 7: Internal linking and CTAs ===\n'
  assert_body_contains "${SAMPLE_POST}" 'utm_source=blog(&amp;|&#038;|&)utm_medium=organic' "Sticky/product CTA UTM params"
  assert_body_contains "${BASE_URL}/" '/compare' "Footer Compare link"
  assert_body_contains "${BASE_URL}/" '/integrations/wordpress' "Footer Integrations link"
  assert_body_contains "${BASE_URL}/" '/feed/' "Footer RSS link"
  assert_body_contains "${SAMPLE_POST}" 'wordiva-product-links|/compare|/integrations/wordpress|generative-engine-optimization' "Product links block on single"
}

phase_8() {
  printf '\n=== Phase 8: Rich results and social ===\n'
  assert_body_contains "${SAMPLE_POST}" 'og:image:alt' "og:image:alt on sample post"
  assert_body_contains "${SAMPLE_POST}" 'og:image:type' "og:image:type on sample post"
  assert_body_contains "${SAMPLE_POST}" 'wordiva-speakable' "Speakable CSS class on content"
}

phase_9() {
  printf '\n=== Phase 9: Final ops gate ===\n'
  phase_0
  phase_1
  phase_2
  phase_3
  phase_4
  phase_5
  phase_6
  phase_7
  phase_8
  log_pass "Phase 9 runs full regression (GA4 is Customizer-dependent — check manually if ID set)"
}

assert_title_length() {
  local url="$1" max="$2" label="$3"
  local title len
  title="$(curl -sS -L --max-time 30 "$url" | grep -oE '<title>[^<]*</title>' | head -1 | sed -E 's/<\/?title>//g' | sed -e 's/&#8211;/–/g' -e 's/&amp;/\&/g' -e 's/&#038;/\&/g')"
  len="$(printf '%s' "$title" | wc -m | tr -d ' ')"
  if [[ -n "$title" && "$len" -le "$max" ]]; then
    log_pass "$label (${len} chars: ${title})"
  else
    log_fail "$label — ${len} chars > ${max}: ${title}"
  fi
}

assert_meta_description_length() {
  local url="$1" max="$2" label="$3"
  local desc len
  desc="$(curl -sS -L --max-time 30 "$url" | grep -oE '<meta name="description" content="[^"]*"' | head -1 | sed -E 's/^.*content="//; s/"$//')"
  len="$(printf '%s' "$desc" | wc -m | tr -d ' ')"
  if [[ -n "$desc" && "$len" -le "$max" ]]; then
    log_pass "$label (${len} chars)"
  else
    log_fail "$label — ${len} chars > ${max}: ${desc}"
  fi
}

phase_10() {
  printf '\n=== Phase 10: 2026-09-04 SEO brief (pagination, titles, tags, FAQ) ===\n'
  local page2_code page2_body
  page2_code="$(curl -sS -o /dev/null -w '%{http_code}' --max-time 30 "${BASE_URL}/page/2/" || echo "000")"
  if [[ "$page2_code" == "404" ]]; then
    log_pass "/page/2/ returns 404 (no thin pagination)"
  else
    page2_body="$(curl -sS -L --max-time 30 "${BASE_URL}/page/2/" || true)"
    if grep -q 'rel="canonical" href="[^"]*/page/2/\?"' <<< "$page2_body"; then
      log_pass "/page/2/ is self-canonical (HTTP ${page2_code})"
    else
      log_fail "/page/2/ (HTTP ${page2_code}) canonical is not self-referencing"
    fi
    assert_body_contains "${BASE_URL}/page/2/" 'rel="prev"' "/page/2/ has rel=prev"
  fi
  assert_canonical_count "${BASE_URL}/" 1 "Blog home single canonical"
  assert_body_contains "${BASE_URL}/" 'rel="canonical" href="https://wordiva\.ai/blog/"' "Blog home canonical"
  assert_body_not_contains "${BASE_URL}/" 'rel="next"' "Blog home has no rel=next when single page"
  assert_body_contains "${BASE_URL}/" '<title>[^<]*\| Wordiva</title>' "Title suffix | Wordiva on home"
  assert_body_not_contains "${BASE_URL}/" 'Wordiva \| Wordiva' "No doubled brand in title"

  for slug in \
    ai-content-marketing/automate-blog-content-without-losing-quality \
    ai-content-marketing/blog-seo-automation \
    ai-content-marketing/ai-content-marketing-b2b-automation \
    ai-content-marketing/b2b-content-calendar-automated-plan; do
    local url="${BASE_URL}/${slug}"
    assert_title_length "$url" 60 "Title <= 60 on ${slug##*/}"
    assert_meta_description_length "$url" 155 "Meta description <= 155 on ${slug##*/}"
    assert_body_contains "$url" '<title>[^<]*\| Wordiva</title>' "Title suffix on ${slug##*/}"
    assert_canonical_count "$url" 1 "Single canonical on ${slug##*/}"
    assert_body_contains "$url" '"@type":"BlogPosting"' "BlogPosting on ${slug##*/}"
    assert_body_contains "$url" '"@type":"BreadcrumbList"' "BreadcrumbList on ${slug##*/}"
  done
  assert_body_contains "${BASE_URL}/ai-content-marketing/b2b-content-calendar-automated-plan" '"@type":"FAQPage"' "FAQPage on calendar post"
  assert_body_contains "${BASE_URL}/ai-content-marketing/b2b-content-calendar-automated-plan" 'wordiva-faq' "FAQ UI block on calendar post"

  assert_body_contains "${BASE_URL}/tag/${WORDIVA_THIN_TAG:-seo-planning}/" 'noindex, follow' "Thin tag archive noindex"
  assert_body_contains "${BASE_URL}/tag/${WORDIVA_HEALTHY_TAG:-blog-automation}/" 'content="index, follow' "Healthy tag archive indexable"
  assert_body_not_contains "${BASE_URL}/wp-sitemap-taxonomies-post_tag-1.xml" "/tag/${WORDIVA_THIN_TAG:-seo-planning}" "Thin tag absent from sitemap"
  assert_body_contains "${BASE_URL}/category/ai-content-marketing/" '<title>Ai Content Marketing \| Wordiva</title>|<title>AI Content Marketing \| Wordiva</title>' "Category title format"

  assert_body_contains "${BASE_URL}/" 'G-QNTZ96XNJE' "GA4 G-QNTZ96XNJE present"
  assert_body_not_contains "${BASE_URL}/" 'G-REEGXK3HRN' "Stale GA4 ID absent"
  assert_body_contains "${BASE_URL}/feed/" '<item>' "RSS feed still serves items"
}

run_phase() {
  case "$1" in
    0) phase_0 ;;
    1) phase_1 ;;
    2) phase_2 ;;
    3) phase_3 ;;
    4) phase_4 ;;
    5) phase_5 ;;
    6) phase_6 ;;
    7) phase_7 ;;
    8) phase_8 ;;
    9) phase_9 ;;
    10) phase_10 ;;
    all)
      for p in 0 1 2 3 4 5 6 7 8 10; do
        run_phase "$p"
      done
      ;;
    *)
      echo "Usage: $0 <0-10|all>" >&2
      exit 2
      ;;
  esac
}

main() {
  if [[ $# -lt 1 ]]; then
    echo "Usage: $0 <0-10|all>" >&2
    exit 2
  fi
  run_phase "$1"
  printf '\nResults: %d passed, %d failed\n' "$PASS" "$FAIL"
  if [[ "$FAIL" -gt 0 ]]; then
    exit 1
  fi
}

main "$@"

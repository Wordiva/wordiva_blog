#!/usr/bin/env python3
"""Check actual article content, metadata and FAQ JSON-LD on the Sep 9 URLs."""
import html
import json
import os
import re
import subprocess
from concurrent.futures import ThreadPoolExecutor
from html.parser import HTMLParser
from pathlib import Path
from urllib.parse import urljoin, urlsplit

BASE = os.environ.get('WORDIVA_BLOG_URL', 'https://wordiva.ai/blog').rstrip('/')
BUNDLE = Path(__file__).resolve().parents[1] / 'briefs' / 'updates' / '2026-09-15'
MANIFEST = json.loads((BUNDLE / 'manifest.json').read_text())
QUERY = 'seo blog automation for content teams'
LEAK = (
    'content-calendar-dispatch', 'hellomedspa', '%task', '+task', 'task (routed',
    'brand-shop', 'wolf pack', 'virtual nurse',
)
VOID = {'area', 'base', 'br', 'col', 'embed', 'hr', 'img', 'input', 'link', 'meta', 'param', 'source', 'track', 'wbr'}


def normalize(value):
    return ' '.join(html.unescape(value).split())


class Page(HTMLParser):
    def __init__(self):
        super().__init__(convert_charrefs=True)
        self.stack = []
        self.links = []
        self.body_links = []
        self.metas = {}
        self.canonicals = []
        self.title = ''
        self.headings = []
        self.h1s = []
        self.body_text = []
        self.faqs = []
        self.scripts = []
        self.faq = None
        self.heading = None
        self.script = None
        self.in_title = False
        self.in_h1 = False
        self.has_body = False

    def handle_starttag(self, tag, attrs):
        attrs = dict(attrs)
        in_body = any(item[1] for item in self.stack) or attrs.get('itemprop') == 'articleBody'
        self.has_body |= in_body
        if tag not in VOID:
            self.stack.append((tag, in_body))
        if tag == 'a':
            self.links.append(attrs)
            if in_body:
                self.body_links.append(attrs)
        elif tag == 'meta':
            self.metas.setdefault(attrs.get('name', attrs.get('property')), []).append(attrs.get('content', ''))
        elif tag == 'link' and 'canonical' in attrs.get('rel', '').split():
            self.canonicals.append(attrs.get('href', ''))
        elif tag == 'title':
            self.in_title = True
        elif tag == 'h1':
            self.h1s.append('')
            self.in_h1 = True
        elif tag == 'h2' and in_body:
            self.heading = ''
        elif tag == 'details' and in_body:
            self.faq = {'question': '', 'answer': ''}
        elif tag == 'script' and attrs.get('type') == 'application/ld+json':
            self.script = ''

    def handle_startendtag(self, tag, attrs):
        self.handle_starttag(tag, attrs)
        if tag not in VOID:
            self.handle_endtag(tag)

    def handle_data(self, value):
        if self.in_title:
            self.title += value
        if self.in_h1:
            self.h1s[-1] += value
        if self.script is not None:
            self.script += value
        if any(item[1] for item in self.stack):
            self.body_text.append(value)
            if self.heading is not None:
                self.heading += value
            if self.faq is not None:
                key = 'question' if any(item[0] == 'summary' for item in self.stack) else 'answer'
                self.faq[key] += value

    def handle_endtag(self, tag):
        if tag == 'title':
            self.in_title = False
        elif tag == 'h1':
            self.in_h1 = False
        elif tag == 'h2' and self.heading is not None:
            self.headings.append(normalize(self.heading))
            self.heading = None
        elif tag == 'details' and self.faq is not None:
            self.faqs.append({key: normalize(value) for key, value in self.faq.items()})
            self.faq = None
        elif tag == 'script' and self.script is not None:
            self.scripts.append(self.script)
            self.script = None
        for index in range(len(self.stack) - 1, -1, -1):
            if self.stack[index][0] == tag:
                del self.stack[index:]
                break


def fetch(url):
    result = subprocess.run(
        ['curl', '-sS', '-L', '--max-time', '30', '-w', '\n%{http_code}', url],
        capture_output=True, text=True, check=True,
    )
    body, status = result.stdout.rsplit('\n', 1)
    if status != '200' or not body.strip():
        raise ValueError(f'{url}: expected nonempty HTTP 200, got {status}')
    page = Page()
    page.feed(body)
    return page


def schema_nodes(value):
    if isinstance(value, dict):
        yield value
        for child in value.values():
            yield from schema_nodes(child)
    elif isinstance(value, list):
        for child in value:
            yield from schema_nodes(child)


def check_post(row):
    url = row['url']
    page = fetch(url)
    assert page.has_body and normalize(' '.join(page.body_text)), 'Missing article body'
    assert page.canonicals == [url], f'Canonical mismatch: {page.canonicals}'
    assert len(page.h1s) == 1 and normalize(page.h1s[0]), 'Expected one populated H1'
    robots = ','.join(page.metas.get('robots', [])).lower()
    assert 'noindex' not in robots and re.search(r'\bindex\b', robots), 'Post is not indexable'
    assert len(page.metas.get('description', [])) == 1, 'Expected one description'
    assert 20 <= len(page.metas['description'][0]) <= 155, 'Description length'
    assert 20 <= len(normalize(page.title)) <= 60 and page.title.endswith(' | Wordiva'), 'Title length/suffix'
    body = normalize(' '.join(page.body_text)).lower()
    assert not any(needle in body for needle in LEAK), 'Leaked task payload in article'
    assert not any(needle in page.title.lower() for needle in LEAK), 'Leaked task payload in title'
    for field, actual in [('_wordiva_seo_title', normalize(page.title).removesuffix(' | Wordiva')),
                          ('_wordiva_seo_description', page.metas['description'][0])]:
        expected = row['meta_updates'].get(field)
        if expected is None:
            expected = next(iter(row['before_meta'][field]), None)
        if expected is not None:
            assert actual == expected, f'{field} differs from reviewed copy'
    for anchor in page.links:
        href = html.unescape(anchor.get('href', ''))
        path_part = re.split(r'[?#]', href, maxsplit=1)[0]
        assert not re.search(r'\)https?:', href, re.I), 'Glued href'
        assert len(re.findall(r'https?:', path_part, re.I)) <= 1, 'Double-scheme href'
    contextual = set()
    for anchor in page.body_links:
        target = urlsplit(urljoin(url, anchor.get('href', '')))
        if target.netloc != urlsplit(BASE).netloc:
            continue
        assert 'nofollow' not in anchor.get('rel', '').lower().split(), 'Internal article link has nofollow'
        if re.search(r'/blog/[a-z0-9-]+/[a-z0-9-]+/?$', target.path):
            if target.path.rstrip('/') != urlsplit(url).path.rstrip('/'):
                contextual.add(target.path.rstrip('/'))
    assert len(contextual) >= 3, f'Only {len(contextual)} contextual post links (navigation excluded)'
    nodes = [node for script in page.scripts for node in schema_nodes(json.loads(script))]
    assert any(node.get('@type') == 'BlogPosting' for node in nodes), 'Missing BlogPosting'
    assert any(node.get('@type') == 'BreadcrumbList' for node in nodes), 'Missing BreadcrumbList'
    posting = next(node for node in nodes if node.get('@type') == 'BlogPosting')
    expected_title = row['meta_updates'].get('_wordiva_seo_title') or next(iter(row['before_meta']['_wordiva_seo_title']), None)
    if expected_title:
        assert posting.get('headline') == expected_title, 'BlogPosting headline should match SEO title'
    schemas = [node for node in nodes if node.get('@type') == 'FAQPage']
    if row['faq_count']:
        assert len(schemas) == 1, 'Expected exactly one FAQPage'
        expected = [{'question': normalize(pair['name']), 'answer': normalize(pair['acceptedAnswer']['text'])}
                    for pair in schemas[0]['mainEntity']]
        assert len(page.faqs) == row['faq_count'], 'Visible FAQ count mismatch'
        assert expected == page.faqs, 'FAQ schema differs from visible answers'
    else:
        assert not schemas, 'Unexpected FAQ schema on founder post'
    if row['ID'] == 188:
        assert QUERY in page.title.lower() and QUERY in page.metas['description'][0].lower(), 'Query missing from metadata'
        assert QUERY in page.headings[0].lower(), 'Query missing from answer-first H2'
        assert 'How do I automate publishing AI-optimized articles to my CMS?' in [faq['question'] for faq in page.faqs], 'CMS FAQ missing'
    if row['ID'] == 177:
        assert page.headings[0].startswith('Summary:'), 'Flagship summary missing'
        assert 'without losing quality' in page.headings[0].lower(), 'Flagship query missing from summary H2'
        assert 'without losing quality' in page.title.lower(), 'Flagship query missing from title'
        assert 'look cheap' in page.metas['description'][0].lower(), 'Cheap-quality query missing from description'
        assert QUERY not in body, 'Flagship still cannibalizes the SEO automation query'
        assert any('without looking cheap' in faq['question'] for faq in page.faqs), 'Quality FAQ missing'
    if row['ID'] == 222:
        assert page.headings[0].startswith('AI content marketing automation for B2B:'), 'B2B summary missing'
        assert 'ai content marketing automation' in page.title.lower(), 'Rising automation query missing from title'
        assert any('CMS publishing' in faq['question'] for faq in page.faqs), 'CMS publish FAQ missing'
        assert 'cms publishing' in page.metas['description'][0].lower(), 'CMS publish missing from description'
    if row['ID'] == 231:
        assert any('calendar to CMS publishing' in faq['question'] for faq in page.faqs), 'Calendar CMS FAQ missing'
        assert any('b2b content calendar' in faq['question'].lower() for faq in page.faqs), 'Calendar query FAQ missing'
        assert any('content marketing b2b calendar' in faq['question'].lower() for faq in page.faqs), 'Rising calendar FAQ missing'
    if row['ID'] == 245:
        assert page.title.lower().startswith('content marketing roi for b2b'), 'ROI query should lead the title'
        assert any('content marketing ROI' in faq['question'] for faq in page.faqs), 'ROI FAQ missing'
    return f'{row["ID"]}: metadata, canonical, indexability, {len(contextual)} contextual links, {len(page.faqs)} matching FAQs'


def main():
    failures = []
    with ThreadPoolExecutor(max_workers=5) as pool:
        jobs = [(row, pool.submit(check_post, row)) for row in MANIFEST['posts']]
        for row, job in jobs:
            try:
                print('PASS', job.result())
            except Exception as error:
                failures.append(row['ID'])
                print('FAIL', row['ID'], str(error))
    try:
        home = fetch(BASE + '/')
        slug = next(row['slug'] for row in MANIFEST['posts'] if row['ID'] == 177)
        flagship = f'{BASE}/ai-content-marketing/{slug}'
        assert any(urljoin(BASE + '/', a.get('href', '')).rstrip('/') == flagship
                   and 'nofollow' not in a.get('rel', '').lower().split()
                   for a in home.links), 'Crawlable flagship link absent'
        print('PASS blog index has a crawlable flagship link')
    except Exception as error:
        failures.append('index')
        print('FAIL blog index:', str(error))
    raise SystemExit(bool(failures))


if __name__ == '__main__':
    main()

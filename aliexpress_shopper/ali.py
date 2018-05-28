import random
from urllib.parse import urljoin
import re

import requests
from bs4 import BeautifulSoup

sess = requests.session()

blacklist = [
    'weight loss',
    'fat burning',
]

root = 'https://www.aliexpress.com/'

def rel2abs(page, dom=root):
    return urljoin(dom, page)

def els2attrs(els, attr='href', listify=True, process=None):
    if process is not None:
        fn = lambda e: process(e.attrs[attr])
    else:
        fn = lambda e: e.attrs[attr]

    mapped = map(fn, els)

    if listify:
        return list(mapped)
    else:
        return mapped

def get(url, dat, resultsKey = 'results'):
    global sess
    ret = sess.get(url, params=dat)
    if ret.ok:
        jret = ret.json()
        if 'results' in jret:
            return jret[resultsKey]
        else:
            raise ValueError(jret)
    else:
        raise ValueError(ret.text)

def recommend(amount=20, imageSize='1280x1280', item='', category='', company='', shop='', recType='', scenario='', domain='https://gpsfront.aliexpress.com', page='getI2iRecommendingResults.do', resultsKey='results', **kwargs):
    url = urljoin(domain, page)

    dat = {
        'recommendType': recType,
        'scenario': scenario,
        'limit': amount,
        'imageSize': imageSize,
        'currentItemList': item,
        'categoryId': category,
        'companyId': company,
        'shopId': shop,
    }

    dat.update(kwargs)
    return get(url, dat, resultsKey=resultsKey)

def gps(**kwargs):
    # like recommend() for the queryGpsProductAjax.do endpoint
    return recommend(page='queryGpsProductAjax.do', resultsKey='gpsProductDetails', **kwargs)

def searchResults(url):
    """
    given a search page url, returns a list of product urls on that page
    """
    global sess, root

    req = sess.get(url)
    if not req.ok:
        raise ValueError(req.status_code)

    pg = BeautifulSoup(req.text, 'html.parser')
    links = pg.findAll('a', { 'class': 'product' })
    return els2attrs(ls, lambda href: urljoin(root, href), listify=True)

def search(page, domain=root, **kwargs):
    return searchResults(urljoin(domain, page), **kwargs)

# recommended products from a given product

def searches(item, **kwargs):
    """
    recommended searches for a given product
    """
    global sess

    dat = {
        'productId': item
    }
    req = sess.get(rel2abs('/seo/detailCrosslinkAjax.htm'), params=dat)

    if not req.ok:
        raise ValueError(req)

    pg = BeautifulSoup(req.text, 'html.parser')

    return els2attrs(pg.findAll('a'))

def thisSeller(**kwargs):
    return recommend(recType='toMine', scenario='pcDetailBottomMoreThisSeller')

def otherSeller(**kwargs):
    """
    requires: item, shop, or company
    """
    return recommend(recType='toOtherSeller', scenario='pcDetailBottomMoreOtherSeller', **kwargs)

def topSelling(**kwargs):
    """
    requires: company
    """
    return recommend(scenario='pcDetailLeftTopSell', **kwargs)

def trending(**kwargs):
    return recommend(scenario='pcDetailLeftTrendProduct', **kwargs)

# seed functions; product lists from nothing

def flashDeals(**kwargs):
    return gps(widget_id='5063238', **kwargs)

def under5(**kwargs):
    return gps(widget_id='5295841', **kwargs)

def moreToLove(**kwargs):
    return gps(widget_id='5347592', **kwargs)

# processing fns; non-generating

def next(item, store, company, **kwargs):
    """
    next product to "browse" from a given one
    """
    types = [thisSeller, otherSeller, topSelling, trending]
    fn = random.choice(types)
    return random.choice(fn(item=item, store=store, company=company, **kwargs))

def url(item):
    """
    gets an absolute url for a given item id
    """
    global sess
    dat = {
        'searchText': item
    }
    req = sess.get(rel2abs('/wholesale'), params=dat, allow_redirects=False)

    if 'location' in req.headers:
        return req.headers['location']
    else:
        return req.url

def info(item):
    """
    product info from a url
    """
    global sess

    if not item.startswith('http'):
        item = url(item)

    pg = sess.get(item)
    if not pg.ok:
        raise ValueError('requesting page failed')
    pg = pg.text

    def getParam(name, pat=r'\d+'):
        nonlocal pg
        match = re.search(f'window\\.runParams\\.{name}="({pat})";', pg)
        if match:
            return match.group(1)
        else:
            return None

    def tagContents(tag, attribs='', inner='[^<]+'):
        """
        it's parsing, baby
        finds the inside (matching `inner`) of a tag
            <{tag} {attribs}>{inner}</{tag}>
        """
        nonlocal pg

        search = '<' + tag
        if attribs:
            search += ' ' + attribs
        search += f'>({inner})</{tag}>'

        match = re.search(search, pg)
        if match:
            return match.group(1)
        else:
            return None

    ret = {}

    ret['item']    = getParam('productId')
    ret['shop']    = getParam('shopId')
    ret['company'] = getParam('companyId')

    ret['category']       = getParam('categoryId')
    ret['topCategory']    = getParam('topCategoryId')
    ret['secondCategory'] = getParam('secondLevelCategoryId')
    ret['orderCount']     = getParam('productTradeCount')
    ret['adminSeq']       = getParam('adminSeq')

    ret['title'] = tagContents('h1', 'class="product-name" itemprop="name"')
    ret['price'] = getParam('baseCurrencySymbol')
    if ret['price']:
        ret['price'] += getParam('minPrice')
    return ret

def main():
    pass

if __name__ == '__main__':
    main()

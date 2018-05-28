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

# TODO put this in a sep. file?
keywords = [
    'Soil',
    'Hygrometer',
    'Humidity',
    'Detection',
    'Module',
    'Moisture',
    'Water',
    'Sensor',
    'Soil',
    'moisture',
    'Arduino',
    'shoes',
    'sweater',
    'dress',
    'enamel pin',
    'patch',
    'technology',
    'pen',
    'cute',
    'backpack',
    'stylish',
    'component',
]

root = 'https://www.aliexpress.com/'

# in general, `item` denotes a dict with productId, etc. keys, and `productId`
# denotes a unique aliexpress-supplied key for a product

def rel2abs(page, dom=root):
    return urljoin(dom, page)

def els2attrs(els, attr='href', listify=True, process=None):
    def fn(e):
        nonlocal process, attr
        if attr in e:
            ret = e.attrs[attr]
        else:
            raise ValueError(attr + ' not in ' + e)
        if process is not None:
            ret = process(ret)
        return ret

    mapped = map(fn, els)

    if listify:
        return list(mapped)
    else:
        return mapped

def identity(item, fetchInfo=True):
    keep = {'productId', 'shopId', 'companyId'}
    # dict subset; https://stackoverflow.com/a/5352630/5719760
    ret = {k: str(item[k]) for k in item.keys() & keep}
    if fetchInfo:
        ret.update(info(ret['item']))
    return ret

def get(url, dat, resultsKey = 'results'):
    global sess
    ret = sess.get(url, params=dat)
    if ret.ok:
        jret = ret.json()
        if resultsKey in jret:
            return jret[resultsKey]
        else:
            raise ValueError(jret)
    else:
        raise ValueError(ret.text)

def recommend(limit=20, imageSize='1280x1280', productId='', categoryId='', companyId='', shopId='', recommendType='', scenario='', domain='https://gpsfront.aliexpress.com', page='getI2iRecommendingResults.do', resultsKey='results', **kwargs):
    url = urljoin(domain, page)

    dat = {
        'recommendType': recommendType,
        'scenario': scenario,
        'limit': limit,
        'imageSize': imageSize,
        'currentItemList': productId,
        'categoryId': categoryId,
        'companyId': companyId,
        'shopId': shopId,
    }

    dat.update(kwargs)
    return get(url, dat, resultsKey=resultsKey)

def gps(**kwargs):
    # like recommend() for the queryGpsProductAjax.do endpoint
    return recommend(page='queryGpsProductAjax.do', resultsKey='gpsProductDetails', **kwargs)

def searchResults(url, params):
    """
    given a search page url, returns a list of product urls on that page
    """
    global sess, root

    req = sess.get(url, params=params)
    if not req.ok:
        raise ValueError(req.status_code)

    pg = BeautifulSoup(req.text, 'html.parser')
    links = pg.findAll('a', { 'class': 'product' })
    return els2attrs(links, process=lambda href: urljoin(root, href), listify=True)

def stringSearch(query):
    dat = {
        'SearchText': query
    }
    return searchResults(rel2abs('wholesale'), dat)

# recommended products from a given product

def searches(productId):
    """
    recommended searches for a given product
    """
    global sess

    dat = {
        'productId': productId
    }
    req = sess.get(rel2abs('/seo/detailCrosslinkAjax.htm'), params=dat)

    if not req.ok:
        raise ValueError(req)

    pg = BeautifulSoup(req.text, 'html.parser')

    return els2attrs(pg.findAll('a'))

def thisSeller(**kwargs):
    return recommend(recommendType='toMine', scenario='pcDetailBottomMoreThisSeller')

def otherSeller(**kwargs):
    """
    requires: item, shop, or company
    """
    return recommend(recommendType='toOtherSeller', scenario='pcDetailBottomMoreOtherSeller', **kwargs)

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

def search(keywords=keywords):
    items = stringSearch(random.choice(keywords))
    return info(random.choice(items))

def seed():
    fns = [flashDeals, under5, moreToLove, search]
    items = random.choice(fns)()
    return random.choice(items)

# processing fns; non-generating

def nextDirect(productId, shopId, companyId, **kwargs):
    """
    next product to "browse" from a given one via direct product-to-product links
    """
    types = [thisSeller, otherSeller, topSelling, trending]
    fn = random.choice(types)
    return random.choice(fn(productId=productId, shopId=shopId, companyId=companyId, **kwargs))

def nextIndirect(productId):
    """
    next product to "browse" from a given one via searches
    """
    searchUrls = searches(productId)
    items = searchResults(random.choice(searchUrls))
    return info(random.choice(productId))

def url(productId):
    """
    gets an absolute url for a given item id
    """
    global sess
    dat = {
        'searchText': productId
    }
    req = sess.get(rel2abs('/wholesale'), params=dat, allow_redirects=False)

    if 'location' in req.headers:
        return req.headers['location']
    else:
        return req.url

def info(productIdOrUrl):
    """
    product info from a url
    """
    global sess

    if not productIdOrUrl.startswith('http'):
        productIdOrUrl = url(productIdOrUrl)

    pg = sess.get(productIdOrUrl)
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

    ret['productId'] = getParam('productId')
    ret['shopId']    = getParam('shopId')
    ret['companyId'] = getParam('companyId')

    ret['categoryId']            = getParam('categoryId')
    ret['topCategoryId']         = getParam('topCategoryId')
    ret['secondLevelCategoryId'] = getParam('secondLevelCategoryId')
    ret['orderCount']            = getParam('productTradeCount')
    ret['adminSeq']              = getParam('adminSeq')

    ret['title'] = tagContents('h1', 'class="product-name" itemprop="name"')
    ret['price'] = getParam('baseCurrencySymbol')
    if ret['price']:
        ret['price'] += getParam('minPrice')
    return ret

def main():
    pass

if __name__ == '__main__':
    main()

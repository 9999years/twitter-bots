import random
from urllib.parse import urljoin
import re

import requests

blacklist = [
    'weight loss',
    'fat burning',
]

# widgets:
# 5295841 items under $5

def get(url, dat):
    ret = requests.get(url, params=dat)
    if ret.ok:
        jret = ret.json()
        if 'results' in jret:
            return jret['results']
        else:
            raise ValueError(jret)
    else:
        raise ValueError(ret.text)

def recommend(amount=20, imageSize='1280x1280', item='', category='', company='', shop='', recType='', scenario='', domain='https://gpsfront.aliexpress.com', page='getI2iRecommendingResults.do', **kwargs):
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
    return get(url, dat)

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

def next(item, store, company, **kwargs):
    types = [thisSeller, otherSeller, topSelling, trending]
    fn = random.choice(types)
    return random.choice(fn(item=item, store=store, company=company, **kwargs))

def url(item):
    dat = {
        'searchText': item
    }
    req = requests.get('https://www.aliexpress.com/wholesale', params=dat, allow_redirects=False)

    if 'location' in req.headers:
        return req.headers['location']
    else:
        return req.url

def info(item):
    """
    product info from a url
    """

    if not item.startswith('http'):
        item = url(item)

    pg = requests.get(item)
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

    ret = {}

    ret['item']    = getParam('productId')
    ret['shop']    = getParam('shopId')
    ret['company'] = getParam('companyId')

    ret['category']       = getParam('categoryId')
    ret['topCategory']    = getParam('topCategoryId')
    ret['secondCategory'] = getParam('secondLevelCategoryId')
    ret['orders']         = getParam('productTradeCount')

    def tagContents(tag, attribs='', inner='[^<]+'):
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

    ret['title'] = tagContents('h1', 'class="product-name" itemprop="name"')
    # ret['price'] = getParam('baseCurrencySymbol') + getParam('minPrice')
    return ret

def main():
    pass

if __name__ == '__main__':
    main()

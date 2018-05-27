import requests
import random
from urllib.parse import urljoin

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

def seed():
    ret = random.choice(recommended())
    return { 'id': ret['productId'],
        'title': ret['productTitle'],
        'price': (ret['minPrice'], ret['maxPrice']),
        'img': ret['productImage'],
        'url': ret['productDetailUrl'],
    }

def main():
    pass

if __name__ == '__main__':
    main()

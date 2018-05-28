import ali

import random
import time

from tabulate import tabulate

def chance(probablilty):
    return random.random() >= probablilty

def thread(item=None, limit=10, sleep=True):
    if item is None:
        item = ali.seed()
    else:
        item = ali.info(item)

    yield item

    for i in range(limit):
        if chance(0.8):
            item = ali.nextDirect(**ali.identity(item))
        else:
            item = ali.nextIndirect(item['productId'])

        yield item

        if sleep:
            time.sleep(random.randrange(1, 3))

def printThread():
    with open('thread.html', 'w') as f:
        f.write('''<!doctype html>
<style>
body {
	font: 18px sans-serif;
	max-width: 1200px;
}

img {
	max-width: 400px;
}

.item {
	width: 400px;
	float: left;
}
</style>
''')
        for item in thread():
            item.update(ali.info(item['productId']))
            print(tabulate(item.items(), tablefmt='plain'))
            print('-' * 76)
            f.write('<div class="item"><a href="{productDetailUrl}">{title}</a>\n'.format_map(item))
            f.write('<br><img src="{productImage}"></div>\n'.format_map(item))

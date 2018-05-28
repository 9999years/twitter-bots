import ali

import random
import time

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
            item = ali.nextIndirect(item)

        yield item

        if sleep:
            time.sleep(random.randrange(1, 3))

import bittrex import bittrex
import poloniex import poloniex
import json
import os
import time

#price to arbit - approx 5 dollars
bitcoinOrderSize = .005

poloniex = poloniex("BD6OKZA9-9RQGEHT7-SNE6BOYF-4769IWGK","556ffedb14bd297b644ee7f423ac85ff497a15daef500ddf051a43ddf99a6e99e3de9a7e4f3708fb1fdadd95e8451c70b8e92cb794bd41692d7e6c9e3f4b5a5c")
bittrex = bittrex('5aebf1e6eb7c4e8495a0e3a71941f53c', 'fa17f67627bb4315919bfc290bd9cc15')

print(bittrex.getmarkets())
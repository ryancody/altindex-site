import bittrex import bittrex
import poloniex import poloniex
import json
import os
import time

#price to arbit - approx 5 dollars
bitcoinOrderSize = .005

poloniex = poloniex("OMITTED","OMITTED")
bittrex = bittrex('OMITTED', 'OMITTED')

print(bittrex.getmarkets())

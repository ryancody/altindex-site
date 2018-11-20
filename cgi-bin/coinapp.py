import coinmarketcap
import json
import coinFuncs
import os
import datetime
import sqlite3
#import jsLiteral
import create_CSV
import get_icarus
import create_JSON
import pytz
import requests
import time

from keys import *

#Changelog
#v2 - change time to log epoch time instead of separate date and time strings
#   - using index2 now instead of index1
#   - using spreadsheet to calculate tc index as well as icarus
#   - no longer output data as json

now = datetime.datetime.now()
epoch = time.time()

os.environ['TZ'] = 'US/Eastern'
time.tzset()

localtime = time.strftime("%Y-%m-%d %H:%M:%S", time.localtime())
localtime = localtime.split(' ')

market_cap_name = 'market_cap_usd'
symbol_name = 'symbol'

#ping tc sheet to get divisor and symbols
tcDocID = TC_DOC_ID
response = requests.get('https://docs.google.com/spreadsheet/ccc?key=' + tcDocID + '&output=csv')

tcAltSymbols = []
tcDivisor = 1

response = response.content.splitlines()

for i in xrange(0,len(response)):
    if(i == 0):
        tcDivisor = float(response[i])
    else:
        tcAltSymbols.append(response[i])

print('collected tc data: ')
print(tcAltSymbols)
print('tc divisor: ' + str(tcDivisor))
#end tc symbol data collection

connect = sqlite3.connect(DB_LOCATION)
table_name = "index2"

def get_latest_stats():
    data = coinmarketcap.Market()

    stats = data.stats()

    stats = str(stats)
    stats = json.loads(stats)

    return stats


def get_latest_ticker():
    data = coinmarketcap.Market()

    ticker = data.ticker("?limit=2000")

    ticker = str(ticker)
    ticker = json.loads(ticker)

    return ticker

def sum_all_market_caps(dict):
    total = 0.0

    for i in tcAltSymbols:
        print("working on " + i)
        curEntry = coinFuncs.get_entry_from_symbol(i, ticker)
        #print("increasing total to " + str(total))
        print(curEntry)
        print(curEntry[symbol_name] + " " + curEntry[market_cap_name])
        print("\n")
        total += float(curEntry[market_cap_name])

    return total

#ping server for latest
stats = get_latest_stats()
ticker = get_latest_ticker()

market_cap = sum_all_market_caps(ticker)
index = market_cap / tcDivisor

#print date time for current.txt
day = '%02d' % now.day
month = '%02d' % now.month

hour = '%02d' % now.hour
minute = '%02d' % now.minute
second = '%02d' % now.second

date = str(now.year) + "-" + month + "-" + day
time = hour + ":"+ minute + ":"+ second

outString = date + " - " + time + " - $" + str(index)

#calculate icarus
icarus_price = get_icarus.calculate_icarus(ticker)

print('\ncurrent icarus price per share is $' + str(icarus_price))
#end icarus calculation

btcPrice = coinFuncs.get_price_usd_from_symbol('btc',ticker)
ethPrice = coinFuncs.get_price_usd_from_symbol('eth',ticker)

#write to current.txt for PHP read
with open(os.path.join(CURRENT_DATA_LOCATION,"current.txt"), "w") as text_file:
    text_file.write(localtime[0] + ";" + localtime[1] + ";" + str(round(index,2)) + ";" + str(round(icarus_price,2)) + ";" + str(round(float(btcPrice),2)) + ";" + str(round(float(ethPrice),2)))

#write to sqLite DB
with connect:
	cursor = connect.cursor()
	cursor.execute("INSERT INTO {tn} (epoch, tc, ica, btc, eth, totalmarketcap, divisor, dt) VALUES (?, ?, ?, ?, ?, ?, ?, ?);".\
        format(tn=table_name), (epoch, index, icarus_price, btcPrice, ethPrice, market_cap, tcDivisor, date + ' ' + time ))
	connect.commit()

#get data from sqLite DB and write to json
#nthItem = 1
#with connect:
#    cursor = connect.cursor()
#    cursor.execute("SELECT * FROM {tn} WHERE (id % {nth} = 0) OR id = (SELECT MAX(id) from {tn})".\
#        format(tn=table_name, nth=nthItem))
#    databaseData = cursor.fetchall()
#    jstring = json.dumps(databaseData)

#parse for JSON use
#create_JSON.create(databaseData)

#create JSON literal for chart data
#jsLiteral.create(databaseData)
#create_CSV.create(databaseData)

print("\nrunning coinapp")
print("T&C Alt 20 Index for " + outString)
print("\n")

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

from keys import *

now = datetime.datetime.now()

quitCommand = 'quit'

market_cap_name = 'market_cap_usd'
symbol_name = 'symbol'

tcAltSymbols = ['xmr', 'dash', 'steem', 'maid', 'xem', 'dgd', 'waves', 'fct', 'lsk', 'xlm', 'emc', 'bts', 'xcp', 'amp', 'nxt', 'sc', 'sjcx']
tcDivisor = 2888074.0

connect = sqlite3.connect(DB_LOCATION)
table_name = "index1"

def get_latest_stats():
    data = coinmarketcap.Market()

    stats = data.stats()

    stats = str(stats)
    stats = json.loads(stats)

    return stats


def get_latest_ticker():
    data = coinmarketcap.Market()

    ticker = data.ticker()

    ticker = str(ticker)
    ticker = json.loads(ticker)

    return ticker

def sum_all_market_caps(dict):
    total = 0.0

    for i in tcAltSymbols:
        #print("working on " + i)
        curEntry = coinFuncs.get_entry_from_symbol(i, ticker)
        #print("increasing total to " + str(total))
        total += float(curEntry[market_cap_name])

    return total


def run_coin():
    userInput = ""

    while userInput != quitCommand:
        print()
        print("You can type 'printall' 'help' or 'quit'")
        print()
        userInput = input("Command: ")
        userInput = userInput.lower()

        os.system('cls')
        if userInput == 'printall':
            coinFuncs.print_all(ticker)
        elif userInput == 'help':
            print("You can type 'printall' 'help' or 'quit'")
        else:
            userInput == 'help'

#ping server for latest
stats = get_latest_stats()
ticker = get_latest_ticker()

market_cap = sum_all_market_caps(ticker)
index = market_cap / tcDivisor

#put date and time in sqlite friendly format
day = '%02d' % now.day
month = '%02d' % now.month

hour = '%02d' % now.hour
minute = '%02d' % now.minute
second = '%02d' % now.second

date = str(now.year) + "-" + month + "-" + day
time = hour + ":"+ minute + ":"+ second

outString = date + " - " + time + " - $" + str(index)

#calculate icarus
icarus_shares = 791.3519
icarus = get_icarus.get_icarus_dict()
icarus_price = 0
for symbol in icarus:
    #print('current symbol ' + symbol)
    if(symbol.lower() == 'usd'):
        icarus_price += float(icarus[symbol])
    else:
        icarus_price = icarus_price + float(coinFuncs.get_price_usd_from_symbol(symbol,ticker)) * float(icarus[symbol])

print('\ncurrent icarus total is $' + str(icarus_price))

icarus_price = icarus_price / icarus_shares

print('\ncurrent icarus share is $' + str(icarus_price))

btcPrice = coinFuncs.get_price_usd_from_symbol('btc',ticker)
ethPrice = coinFuncs.get_price_usd_from_symbol('eth',ticker)

#write to current.txt for PHP read
with open(os.path.join(CURRENT_DATA_LOCATION,"current.txt"), "w") as text_file:
    text_file.write(date + ";" + time + ";" + str(round(index,2)) + ";" + str(round(icarus_price,2)) + ";" + str(round(float(btcPrice),2)) + ";" + str(round(float(ethPrice),2)))

#write to sqLite DB
with connect:
	cursor = connect.cursor()
	cursor.execute("INSERT INTO {tn} (date, time, tc, ica, btc, eth, totalmarketcap, divisor) VALUES (?, ?, ?, ?, ?, ?, ?, ?);".\
        format(tn=table_name), (date, time, index, icarus_price, btcPrice, ethPrice, market_cap, tcDivisor ))
	connect.commit()

#get data from sqLite DB and write to json
nthItem = 1
with connect:
    cursor = connect.cursor()
    cursor.execute("SELECT * FROM {tn} WHERE (id % {nth} = 0) OR id = (SELECT MAX(id) from {tn})".\
        format(tn=table_name, nth=nthItem))
    databaseData = cursor.fetchall()
    jstring = json.dumps(databaseData)

#parse for JSON use
create_JSON.create(databaseData)

#create JSON literal for chart data
#jsLiteral.create(databaseData)
#create_CSV.create(databaseData)

print("\nrunning coinapp")
print("T&C Alt 20 Index for " + outString)
print("\n")

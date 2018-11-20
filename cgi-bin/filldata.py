import numpy
import os
import sqlite3
import csv
import time
import urllib2
import coinmarketcap
import json

from keys import *

table2 = sqlite3.connect(DB_LOCATION)
curs2 = table2.cursor()

ind2 = curs2.execute("SELECT * FROM index2 WHERE id < 1348")

ind2 = ind2.fetchall()

print(len(ind2))

startDate = '7-3-2016'
pattern = '%m-%d-%Y'
startEpoch = int(time.mktime(time.strptime(startDate, pattern)))

endDate = '12-7-2016'
endEpoch = int(time.mktime(time.strptime(endDate, pattern)))

print ('dates from ' + str(startEpoch) + ' to ' + str(endEpoch))
print (' or ' + time.strftime(pattern, time.localtime(startEpoch)) + ' to ' + time.strftime(pattern, time.localtime(endEpoch))) 

allCoins = ['monero','dash','nem','maidsafecoin','synereo','siacoin','storjcoin-x','bitshares','nxt','factom','stellar','counterparty','emercoin','steem','digixdao','lisk','waves']
key = 'market_cap_by_available_supply'

divisor = 2978018.96

coinData = []

for c in allCoins:
    print('downloading ' + c + ' data')
    data = coinmarketcap.Market()

    data = data.datapoints(c)

    data = json.loads(data)
    data = data[key]

    coinData.append(data)

epochRange = endEpoch - startEpoch
epochDayLength = 24 * 60 * 60

count = 0
maxDays = epochRange / epochDayLength
while(count < maxDays):
    index = 0
    totalmarketcap = 0

    curEpoch = startEpoch + count * epochDayLength
    curDay = time.strftime("%Y-%m-%d", time.localtime(curEpoch))

    minEpoch = curEpoch
    maxEpoch = curEpoch + epochDayLength

    print('working on ' + curDay + ' data')

    for c in coinData:
        for d in c:
            epoch = d[0] / 1000
            if epoch > minEpoch and epoch < maxEpoch:
                if d[1] > 0:
                    day = time.strftime("%Y-%m-%d", time.localtime(epoch))
                    print(' - - adding market cap value of ' + str(d[1]) + ' for ' + curDay)
                    totalmarketcap = totalmarketcap + d[1]
                else:
                    print("FOUND ZERO ERROR")
                break

    print('--Total Market Cap for ' + curDay + ' is $' + str(totalmarketcap) + '--')
    index = totalmarketcap / divisor
    print('--T&C for ' + curDay + ' is ' + str(index) + '--')
    print('\n')

    for i in ind2:
        if i[1] > minEpoch and i[1] < maxEpoch:
            tc = i[2]
            row = i[0]
            print('updating index2 for ' + curDay + '\n' )
            curs2.execute("UPDATE index2 SET tc = ? WHERE id = ?;", (index, row))
            curs2.execute("UPDATE index2 SET totalmarketcap = ? WHERE id = ?;", (totalmarketcap, row))
            curs2.execute("UPDATE index2 SET divisor = ? WHERE id = ?;", (divisor, row))

    count = count + 1


table2.commit()

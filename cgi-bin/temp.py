import numpy
import os
import sqlite3
import csv
import time

from keys import *

#dates = []
#with open('eth history.csv') as csvfile:
#    reader = csv.reader(csvfile, delimiter=',')
#    for row in reader:
#        count = 0
#        for col in row:
#            if count == 0:
#                dates.append(col)
#            count += 1

#print(dates)

#data = numpy.genfromtxt('eth history.csv', delimiter=',')

#for row in range(0,len(data)):
#    for col in range(1,len(data[row])):
#        if numpy.isnan(data[row][col]):
#            data[row][col] = 0

#newData = []

#cnt = 0
#for line in dates:
#    newline = [line, data[cnt][1]]
#    newData.append(newline)
#    cnt+=1

#for row in newData:
#    print(row)

ethData = []

table1 = sqlite3.connect(DB_LOCATION)
curs1 = table1.cursor()

table2 = sqlite3.connect(DB_LOCATION)
curs2 = table2.cursor()

table_name = 'index1'

ind1 = curs1.execute("SELECT * FROM index1 WHERE id >= 13929")
ind2 = curs2.execute("SELECT * FROM index2")

ind1 = ind1.fetchall()
ind2 = ind2.fetchall()

#for d in xrange(0,len(ethData)):
#    ethDate = ethData[d][0] / 1000
#    ethDate = time.strftime('%Y-%m-%d', time.localtime(ethDate))
#    print('matching ' + ethDate)
#    for j in ind2:
#        ind2day = j[8]
#        #print('looking for date match for ' + str(ind2day))
#        ind2day = ind2day.split(' ')
#        ind2day = ind2day[0]
#        if ethDate == ind2day:
#            changeID = j[0]
#            changeVal = ethData[d][1]
#            if changeVal > 0:
#                curs2.execute("UPDATE index2 SET eth = ? WHERE id = ?;", (changeVal, changeID))


print(len(ind1))
print(len(ind2))

for i in ind1:
    epoch = int(time.mktime(time.strptime(i[1] + ' ' + i[2], '%Y-%m-%d %H:%M:%S')))
    curs2.execute("INSERT INTO index2 (epoch, tc, ica, btc, eth, totalmarketcap, divisor, dt) VALUES (?,?,?,?,?,?,?,?)", (epoch,i[3],i[4],i[5],i[6],i[7],i[8],str(i[1]) + ' ' + str(i[2])))

#for i in ind1:
#    for j in ind2:
#        ind2day = j[8]
#        print('looking for date match for ' + str(ind2day))
#        ind2day = ind2day.split(' ')
#        ind2day = ind2day[0]
#        if i[1] == ind2day:
#            changeID = j[0]
#            changeVal = i[6]
#            if changeVal > 0:
#                curs2.execute("UPDATE index2 SET eth = ? WHERE id = ?;", (changeVal, changeID))

    #print(d)
    #epoch = time.strftime("%Y-%m-%d %H:%M:%S", time.gmtime(float(d[1]) / 1000))
    #epoch = int(time.mktime(time.strptime(d[1] + ' ' + d[2], '%Y-%m-%d %H:%M:%S')))
    #curs.execute("INSERT INTO index2 (epoch, tc, ica, btc, eth, totalmarketcap, divisor, dt) VALUES (?,?,?,?,?,?,?,?)", (epoch,d[3],d[4],d[5],d[6],d[7],d[8], str(d[1]) + ' ' + str(d[2])))
    #curs.execute("UPDATE index2 SET epoch = ? WHERE epoch = ?;", (newEpoch, oldEpoch))
    #curs.execute("UPDATE index2 SET dt = ? WHERE epoch = ?", (epoch,d[1]))

#curs.execute("ALTER TABLE {tn} ADD COLUMN '{cn}' {ct} DEFAULT '{df}'"\
#    .format(tn='index2', cn='dt', ct='TEXT', df=None))

#curs.execute("DELETE FROM index2 WHERE id > 1307")

table2.commit()

#	cursor.execute("INSERT INTO {tn} (date, time, tc, ica, btc, eth, totalmarketcap, divisor) VALUES (?, ?, ?, ?, ?, ?, ?, ?);".\
#        format(tn=table_name), (date, time, index, icarus_price, btcPrice, ethPrice, market_cap, tcDivisor ))
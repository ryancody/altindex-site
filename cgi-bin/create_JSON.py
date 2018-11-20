import json
import time
import os

from keys import *

dt='2014-12-11 00:00:00'
p='%Y-%m-%d %H:%M:%S'

        #dt = str(d[1]) + " " + str(d[2])
        #epoch = str(int(time.mktime(time.strptime(dt,p)) * 1000))
        #newRow = newRow.replace("DATE TIME", epoch)

os.environ['TZ']='UTC'

def create(databaseData):
    series = ['tcData.json', 'icaData.json', 'btcData.json', 'ethData.json']

    for s in series:
        index = series.index(s) + 3

        jsonData = []
        for r in databaseData:
            newRow = []
            colNum = 0
            for c in r:
                if colNum == 1:
                    dt = str(r[1]) + " " + str(r[2])
                    epoch = str(int(time.mktime(time.strptime(dt,p)) * 1000))
                    newRow.append( int(epoch) )
                elif colNum == index:
                    if float(c) == 0:
                        newRow.append(None)
                    else:
                        newRow.append(float(c))
                colNum = colNum + 1
            jsonData.append(newRow)

        with open(CHART_DATA_LOCATION + s, 'w') as outfile:
            json.dump(jsonData, outfile)
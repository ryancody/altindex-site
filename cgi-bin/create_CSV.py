import os
import time

from keys import *

header = '''<pre id="csv" style="display:none">Date,T&amp;C 20,Icarus Fund,BTC,ETH\n'''

rowItem = '''DATE TIME,INDEX,ICA,BTC,ETH'''

footer = '</pre>'


dt='2014-12-11 00:00:00'
p='%Y-%m-%d %H:%M:%S'

os.environ['TZ']='UTC'

def create(data):
    literal = str(header)

    for d in data:
        newRow = str(rowItem)
        #dt = str(d[1]) + " " + str(d[2])
        #epoch = str(int(time.mktime(time.strptime(dt,p)) * 1000))
        #newRow = newRow.replace("DATE TIME", epoch)
        newRow = newRow.replace("DATE",str(d[1]))
        newRow = newRow.replace("TIME",str(d[2]))
        newRow = newRow.replace("INDEX",str(d[3]))
        newRow = newRow.replace("ICA",str(d[4]))
        newRow = newRow.replace("BTC",str(d[5]))
        newRow = newRow.replace("ETH",str(d[6]))
        literal += newRow + '\n'

    literal += footer

    with open(os.path.join(CURRENT_DATA_LOCATION,"chartdatacsv.php"), "w") as text_file:
        text_file.write(literal)
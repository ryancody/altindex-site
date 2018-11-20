import requests
import coinFuncs

from keys import *

def get_icarus_dict():
    docID = ICARUS_DOC_ID
    response = requests.get('https://docs.google.com/spreadsheet/ccc?key=' + docID + '&output=csv')

    print('downloaded icarus data...')
    print(response.content)

    icarus = {}

    for line in response.content.splitlines():
        item = line.split(',')
        icarus[item[0]] = item[1]

    print('icarus data parsed to dictionary...')
    print(icarus)

    return icarus

def calculate_icarus(ticker):
    icarus = get_icarus_dict()
    icarus_shares = 0
    icarus_price = 0
    for symbol in icarus:
        #print('current symbol ' + symbol)
        if(symbol.lower() == 'usd'):
            icarus_price += float(icarus[symbol])
        elif(symbol.lower() == 'divisor'):
            icarus_shares = float(icarus[symbol])
        else:
            icarus_price = icarus_price + float(coinFuncs.get_price_usd_from_symbol(symbol,ticker)) * float(icarus[symbol])

    if(icarus_shares == 0):
        print('\mERROR: failed to pull icarus divisor')
        return 0

    print('\ncurrent icarus total value is $' + str(icarus_price))

    icarus_price = icarus_price / icarus_shares
    return icarus_price
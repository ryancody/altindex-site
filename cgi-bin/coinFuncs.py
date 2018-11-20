def get_entry_from_symbol(symbol, dict):
    for i in dict:
        #print("comparing " + symbol.lower() + " vs " + i["symbol"].lower())
        if i["symbol"].lower() == symbol.lower():
            return i

    print('couldnt find symbol ' + symbol)
    #print("was searching:")
    #print(str(dict))
    return "X"

def get_price_usd_from_symbol(symbol, dict):
    for i in dict:
        if i["symbol"].lower() == symbol.lower():
            return i['price_usd']
    print('couldnt find symbol ' + symbol)
    return 0

def print_all(dict):
    result = ""

    for d in dict:
        result += d['id'] + ", "

    print(result)
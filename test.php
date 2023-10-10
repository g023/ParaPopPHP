<?php

require_once('class.ppop.php');

$commands = [
    'php test.php +cmd=test_delay +delay=2 +arg1=1 +arg2=15 +arg3=3',
    'php test.php +cmd=test_delay +delay=3 +arg1=4 +arg2=10 +arg3=6',
    'php test.php +cmd=test_add +delay=2 +arg1=7 +arg2=5 +arg3=9',
    'ls -l',
];




class TestDelayCommand extends CommandPlugin
{
    public function execute($args)
    {
        $delay = isset($args['delay']) ? (int)$args['delay'] : 10;
        $id = uniqid();
        if (!empty($args['id'])) {
            $id = $args['id'];
        }
        $start = time();
        echo "\r\n\r\n=-=\r\n";
        echo "time start $id:" . time() . "\n\n";
        sleep($delay);
        $end = time();
        echo "test_delay\n";
        echo "time end $id:" . time() . "\n\n";
        echo "time total $id:" . ($end - $start) . "\n\n";
    }
}

class TestAddCommand extends CommandPlugin
{
    public function execute($args)
    {
        $sum = 0;
        if (!empty($args['arg1']) && !empty($args['arg2'])) {
            $sum = $args['arg1'] + $args['arg2'];
        }

        $result = ['add' => $sum];
        echo json_encode($result);
    }
}


# begin #

// create our plugin manager (a command handler to handle processes calling this script)
$ppPHPCommander = new ppPHPCommander();
// add our plugins
$ppPHPCommander->addPlugin('test_delay', new TestDelayCommand());
$ppPHPCommander->addPlugin('test_add', new TestAddCommand());


if (!empty($argv[1])) {
    // we are inside a process. Command process
    $args = [];
    foreach ($argv as $arg) {
        if (substr($arg, 0, 1) == '+') {
            $arg = substr($arg, 1);
            $arg = explode('=', $arg);
            $args[$arg[0]] = $arg[1];
        }
    }

    // if there is args use our ppPHPCommander to run an execute on each plugin
    if (!empty($args)) {
        $ppPHPCommander->execute($args);
    }

} else {
    // multiprocess
    $pPHP = new ppPHP();
    $pPHP->commands = $commands;

    // $pPHP->run(true); // stream out results
    $result = $pPHP->run(); // return results

    echo "\r\n\r\n";
    echo "result:\r\n";
    print_r($result);
    
}


?>
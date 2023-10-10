<?php
/*
    title: ParaPopPHP - A PHP library for running PHP processes in parallel using popen()
    version: 0.1 
    license: BSD 3-Clause License
    author: https://github.com/g023/
    url: https://github.com/g023/ParaPopPHP

    
    short title: ParaPopPHP - Parallel Processing PHP via popen()
    long title: ParaPopPHP - A PHP library for running PHP processes in parallel using popen()
    description: 
        ParaPopPHP is a PHP library for running PHP processes in parallel using popen() instead of
        the more common pcntl_fork() method.  This allows for parallel processing on Windows as well as Linux.
        ParaPopPHP is a single class that can be included in your project and used to run multiple PHP processes
        in parallel.  ParaPopPHP can also be used to run other programs in parallel as well.
    
    companion class: ppPHPCommander (BSD 3-Clause License as well)
    description:
        ppPHPCommander is a companion class for the ParaPopPHP library, designed to help you manage and execute plugins 
        for various commands that you may want to run in parallel using the `ppPHP` class. This class simplifies the 
        process of adding, associating, and executing plugins, allowing you to customize the behavior of individual commands.

    check out test.php for sample usage.
*/
class ppPHP
{
    public $commands;

    public function __construct()
    {
        $this->commands = [
            'php class.ppop.php +cmd=test_delay +delay=2 +arg1=1 +arg2=15 +arg3=3',
            'php class.ppop.php +cmd=test_delay +delay=3 +arg1=4 +arg2=10 +arg3=6',
            'php class.ppop.php +cmd=test_add +delay=2 +arg1=7 +arg2=5 +arg3=9',
            'ls -l',
        ];
    }

    public function run()
    {
        $processes = [];

        foreach ($this->commands as $command) {
            $processes[] = $this->launchProcess($command);
        }

        $processesFinished = 0;
        while ($processesFinished < count($processes)) {
            foreach ($processes as $process) {
                // if we have a resource to read
                if(is_resource($process))
                {
                    
                    $output = fread($process, 4096);
                    if (!empty($output)) {
                        echo $output . "\r\n";
                    }

                    if (feof($process)) {
                        pclose($process);
                        $processesFinished++;
                    }
                }
            }
        }
    }

    private function launchProcess($command)
    {
        if (substr(php_uname(), 0, 7) == "Windows") {
            $command = "start /B " . $command;
        } else {
            $command = $command . " > /dev/null 2>&1 &";
        }

        $process = popen($command, 'r');
        return $process;
    }
}

// a class to control all the plugins. Lets us add, and execute plugins
class ppPHPCommander
{
    public $plugins = [];

    public function __construct()
    {
        /*
        $this->plugins = [
            'test_delay' => new TestDelayCommand(),
            'test_add' => new TestAddCommand(),
        ];
        */
    }

    public function addPlugin($name, $plugin)
    {
        $this->plugins[$name] = $plugin;
    }

    public function execute($args)
    {
        if (!empty($args['cmd'])) {
            if (isset($this->plugins[$args['cmd']])) {
                $this->plugins[ $args['cmd'] ]->execute($args);
            }
        }
    }
}

// class CommandPlugin
// abstract our class
abstract class CommandPlugin
{
    abstract public function execute($args);
}
?>
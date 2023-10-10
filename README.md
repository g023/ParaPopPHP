# ParaPopPHP - A PHP library for running PHP processes in parallel using `popen()`

![GitHub](https://img.shields.io/github/license/g023/ParaPopPHP)
![GitHub release (latest by date)](https://img.shields.io/github/v/release/g023/ParaPopPHP)
![GitHub contributors](https://img.shields.io/github/contributors/g023/ParaPopPHP)
![GitHub issues](https://img.shields.io/github/issues/g023/ParaPopPHP)

## Introduction

ParaPopPHP is open-source software licensed under the BSD 3-Clause License.

ParaPopPHP is a PHP library for running PHP processes in parallel using `popen()` instead of the more common `pcntl_fork()` method. This allows for parallel processing on both Windows and Linux environments. ParaPopPHP provides a single class that can be included in your project, allowing you to run multiple PHP processes or other programs in parallel with ease.

## Features

- Run PHP processes in parallel using the `popen()` function.
- Cross-platform support for Windows and Linux.
- Easily integrate parallel processing into your PHP projects.
- Execute custom commands or scripts concurrently.

## Installation

ParaPopPHP can be included in your PHP project by simply including the `class.ppop.php` file.

```php
require_once('class.ppop.php');


## Usage
```php
$paraPop = new ppPHP();

// Define the commands to run in parallel
$paraPop->commands = [
    'php class.ppop.php +cmd=test_delay +delay=2 +arg1=1 +arg2=15 +arg3=3',
    'php class.ppop.php +cmd=test_delay +delay=3 +arg1=4 +arg2=10 +arg3=6',
    'php class.ppop.php +cmd=test_add +delay=2 +arg1=7 +arg2=5 +arg3=9',
    'ls -l',
];

// Run the commands concurrently
$paraPop->run();
```

# ppPHPCommander - Managing Plugins for ParaPopPHP

![GitHub](https://img.shields.io/github/license/g023/ParaPopPHP)
![GitHub release (latest by date)](https://img.shields.io/github/v/release/g023/ParaPopPHP)
![GitHub contributors](https://img.shields.io/github/contributors/g023/ParaPopPHP)
![GitHub issues](https://img.shields.io/github/issues/g023/ParaPopPHP)

## Introduction
The ppPHPCommander class is open-source software licensed under the BSD 3-Clause 

`ppPHPCommander` is a companion class for the ParaPopPHP library, designed to help you manage and execute plugins for various commands that you may want to run in parallel using the `ppPHP` class. This class simplifies the process of adding, associating, and executing plugins, allowing you to customize the behavior of individual commands.

## Features

- Easily manage and organize plugins for different commands.
- Associate plugins with specific command names.
- Execute custom logic or actions for each command using plugins.
- Seamlessly integrate with the ParaPopPHP library for parallel processing.

## Installation

The `ppPHPCommander` class is included in the ParaPopPHP library and does not require separate installation. To use it, simply include the `class.ppop.php` file and create an instance of the `ppPHPCommander` class.

```php
require_once('class.ppop.php');
$commander = new ppPHPCommander();

## Usage
```php
$commander = new ppPHPCommander();

/*
Adding Plugins
To add a new plugin to the ppPHPCommander instance, use the addPlugin() method. Provide a command name and an instance of a plugin class.
*/
// Create a plugin instance and associate it with the 'test_delay' command
$testDelayPlugin = new TestDelayCommand();
$commander->addPlugin('test_delay', $testDelayPlugin);

/*
Executing Commands
To execute a command using the ppPHPCommander class, call the execute() method and provide an associative array of arguments, including the cmd key representing the command to execute.
*/
$command = [
    'cmd' => 'test_delay',
    'arg1' => 'value1',
    'arg2' => 'value2',
];

$commander->execute($command);
```




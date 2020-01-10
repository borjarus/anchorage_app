#!/usr/bin/env php
<?php
require __DIR__.'/vendor/autoload.php';

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpClient\HttpClient;

(new Application('anchorage', '1.0.0'))
    ->register('anchorage')
        ->addArgument('name', InputArgument::REQUIRED, 'Repository name (owner/repo)')
        ->addOption('service', null, InputOption::VALUE_OPTIONAL, 'Service used to get last commit sha', 'github')
        ->setCode(function(InputInterface $input, OutputInterface $output) {
            // output arguments and options
            $name = $input->getArgument('name');
            $service = $input->getOption('service');
            dd($name, $service);
        })
    ->getApplication()
    ->setDefaultCommand('anchorage', true) // Single command application
    ->run();

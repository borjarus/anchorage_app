#!/usr/bin/env php
<?php
require __DIR__.'/vendor/autoload.php';

use Symfony\Component\Console\Application;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

(new Application('anchorage', '1.0.0'))
    ->register('anchorage')
        ->addArgument('repo', InputArgument::REQUIRED, 'Repository repo (owner/repo)')
        ->addOption('service', null, InputOption::VALUE_OPTIONAL, 'Service used to get last commit sha', 'github')
        ->setCode(function(InputInterface $input, OutputInterface $output) {
            // output arguments and options
            $formatter = $this->getHelper('formatter');

            $repo = $input->getArgument('repo');
            $explodedRepo = explode('/', $repo);
            if (is_array($explodedRepo) && count($explodedRepo) !== 2){
                echo "incorect form of repo name. should by like owner/repo\n";
                return;
            }
            list($owner,$repo) = $explodedRepo;

            $service = $input->getOption('service');

            if ($service !== 'github'){
                echo "Unknown service '{$service}'\n";
            } else {
                try {
                    $httpClient = HttpClient::create([]);
                    $response = $httpClient->request('GET', "https://api.github.com/repos/{$owner}/{$repo}/commits", []);
                    $code = $response->getStatusCode();
                    if ($code === 200){
                        $content = $response->toArray();
        
                        if (is_array($content) && count($content) > 0){
                            $firstFromContent = $content[0];
                            $sha = isset($firstFromContent['sha']) ? $firstFromContent['sha'] : '';
                            $output->writeln($sha);   
                        }
                    } else {
                        switch($code){
                            case 404:
                            $output->writeln('Repo doesn\'t exists');   
                            break;
                            default:
                            $output->writeln('Another problem!');   
                        }
                    }
                } catch (\Throwable $e) {
                    $errorMessages = ['Error!', $e->getMessage()];
                    $formattedBlock = $formatter->formatBlock($errorMessages, 'error');
                    $output->writeln($formattedBlock);
                }
            }
        })
    ->getApplication()
    ->setDefaultCommand('anchorage', true) // Single command application
    ->run();

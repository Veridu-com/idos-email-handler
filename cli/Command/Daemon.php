<?php
/*
 * Copyright (c) 2012-2016 Veridu Ltd <https://veridu.com>
 * All rights reserved.
 */
declare(strict_types = 1);

namespace Cli\Command;

use Cli\Utils\Mailer;
use Monolog\Handler\StreamHandler;
use Monolog\Logger as Monolog;
use Monolog\Processor\ProcessIdProcessor;
use Monolog\Processor\UidProcessor;
use Philo\Blade\Blade;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command definition for Daemon.
 */
class Daemon extends Command {
    /**
     * Application Settings
     *
     * @var array
     */
    private $settings;

    public function __construct(array $settings) {
        $this->settings = $settings;

        parent::__construct();
    }

    protected function configure() {
        $this
            ->setName('email:daemon')
            ->setDescription('idOS Email Service Daemon')
            ->addOption(
                'devMode',
                'd',
                InputOption::VALUE_NONE,
                'Development mode'
            )
            ->addOption(
                'logFile',
                'l',
                InputOption::VALUE_REQUIRED,
                'Path to log file'
            )
            ->addArgument(
                'functionName',
                InputArgument::REQUIRED,
                'Gearman Worker Function name'
            )
            ->addArgument(
                'serverList',
                InputArgument::REQUIRED | InputArgument::IS_ARRAY,
                'Gearman server host list (separate values by space)'
            );
    }

    /**
     * Command Execution.
     *
     * @param Symfony\Component\Console\Input\InputInterface   $input
     * @param Symfony\Component\Console\Output\OutputInterface $outpput
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        $logFile = $input->getOption('logFile') ?? 'php://stdout';
        $logger = new Monolog('Email');
        $logger->pushProcessor(new ProcessIdProcessor())
        $logger->pushProcessor(new UidProcessor())
        $logger->pushHandler(new StreamHandler($logFile, Monolog::DEBUG));

        $logger->debug('Initializing idOS E-mail Handler Daemon');

        // Development mode
        $devMode = ! empty($input->getOption('devMode'));
        if ($devMode) {
            $logger->debug('Running in developer mode');
            ini_set('display_errors', 'On');
            error_reporting(-1);
        }

        // Gearman Worker function name setup
        $functionName = $input->getArgument('functionName');
        if ((empty($functionName)) || (! preg_match('/^[a-zA-Z0-9\._-]+$/', $functionName))) {
            $functionName = 'idos-email';
        }

        // Server List setup
        $servers = $input->getArgument('serverList');

        $gearman = new \GearmanWorker();
        foreach ($servers as $server) {
            if (strpos($server, ':') === false) {
                $logger->debug(sprintf('Adding Gearman Server: %s', $server));
                $gearman->addServer($server);
            } else {
                $server    = explode(':', $server);
                $server[1] = intval($server[1]);
                $logger->debug(sprintf('Adding Gearman Server: %s:%d', $server[0], $server[1]));
                $gearman->addServer($server[0], $server[1]);
            }
        }

        // Run the worker in non-blocking mode
        $gearman->addOptions(\GEARMAN_WORKER_NON_BLOCKING);

        // 1 second I/O timeout
        $gearman->setTimeout(1000);

        $logger->debug('Registering Worker Function', ['function' => $functionName]);

        $blade  = new Blade(
            $this->settings['path']['views'] ?? __DIR__ . '/../../resources/emails',
            $this->settings['path']['cache'] ?? __DIR__ . '/../../resources/emails/cache'
        );
        $mailer = new Mailer($this->settings['mail']);

        /*
         * Payload content:
         *
         */
        $gearman->addFunction(
            $functionName,
            function (\GearmanJob $job) use ($logger, $blade, $mailer) {
                $logger->info('E-mail job added');
                $jobData = json_decode($job->workload(), true);
                if ($jobData === null) {
                    $logger->warning('Invalid Job Workload!');
                    $job->sendComplete('invalid');

                    return;
                }

                $init = microtime(true);

                $body = $blade
                    ->view()
                    ->make(
                        $jobData['templatePath'],
                        $jobData['variables']
                    )
                    ->render();
                $success = $mailer->send(
                    $jobData['subject'],
                    $jobData['from'],
                    $jobData['to'],
                    $body,
                    $jobData['bodyType']
                );

                // if e-mail was sent
                if ($success) {
                    $logger->debug(sprintf('Sent e-mail. %s', $body));

                    // send idOS task completed status
                    // $url = sprintf('%s/tasks/%s', $this->settings['idos']['baseUrl'], $jobData['task_id']);
                    // var_dump($url);
                    /*
                    $headers = [
                        'Content-Type' => 'application/json',
                        'Authorization' => sprintf('Bearer %s', $this->settings['idos']['jwt-token'])
                    ];
                    // TODO: "status": 0, 1, 2 OR "status": 'created', 'running', 'completed'?
                    $body = json_encode([
                        'status'  => 2
                    ]);
                    $request = new Request('PUT', $url, $headers, $body);
                    $resp = $http->request($request);
                             */
                } else {
                    $logger->debug(sprintf('Failed to send e-mail. %s', $body));
                }

                $logger->info('Job completed', ['time' => microtime(true) - $init]);
                $job->sendComplete('ok');
            }
        );

        $logger->debug('Entering Gearman Worker Loop');

        // Gearman's Loop
        while ($gearman->work()
                || ($gearman->returnCode() == \GEARMAN_IO_WAIT)
                || ($gearman->returnCode() == \GEARMAN_NO_JOBS)
                || ($gearman->returnCode() == \GEARMAN_TIMEOUT)
        ) {
            if ($gearman->returnCode() == \GEARMAN_SUCCESS) {
                continue;
            }

            if (! @$gearman->wait()) {
                if ($gearman->returnCode() == \GEARMAN_NO_ACTIVE_FDS) {
                    // No server connection, sleep before reconnect
                    $logger->debug('No active server, sleep before retry');
                    sleep(5);
                    continue;
                }

                if ($gearman->returnCode() == \GEARMAN_TIMEOUT) {
                    // Job wait timeout, sleep before retry
                    sleep(1);
                    if (! @$gearman->echo('ping')) {
                        $logger->debug('Invalid server state, restart');
                        exit;
                    }

                    continue;
                }
            }
        }

        $logger->debug('Leaving Gearman Worker Loop');
    }
}

<?php
/*
 * Copyright (c) 2012-2016 Veridu Ltd <https://veridu.com>
 * All rights reserved.
 */
declare(strict_types=1);

namespace App\Worker;

use GearmanJob;
use GearmanWorker;
use Philo\Blade\Blade;
use Swift_Message;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command definition for Process-based Daemon.
 */
class EmailDaemon extends Command {
    private $logger;
    private $mailer;
    private $blade;

    public function __construct(Mailer $mailer, Logger $logger, array $settings) {
        $views = __DIR__ . '/../../resources/emails';
        $cache = __DIR__ . '/../../resources/emails/cache';

        $this->mailer   = $mailer;
        $this->logger   = $logger;
        $this->settings = $settings;
        $this->blade    = new Blade($views, $cache);

        parent::__construct();
    }

    protected function configure() {
        $this
            ->setName('daemon:email')
            ->setDescription('idOS Email Service Daemon');
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
        // actually instatiates worker
        $worker = new GearmanWorker();
        $worker->addServer('localhost');

        $worker->addFunction('send_email', function (GearmanJob $job) {

            // job data
            $workload = $job->workload();
            $data = json_decode($workload);
            $serialized = json_encode([
                'templatePath'  => $data->templatePath,
                'to'            => $data->to,
                'from'          => $data->from
            ]);

            // email template variables
            $variables = (array) $data->variables;

            var_dump($variables);

            // sending e-mail 
            $message = new Swift_Message();
            $message
                ->setSubject($data->subject)
                ->setFrom($data->from)
                ->setTo($data->to)
                ->setBody(
                    $this->blade->view()->make($data->templatePath, $variables)->render(),
                    $data->bodyType
                );

            $this->logger->debug(sprintf('Trying to send e-mail. %s', $serialized));
            $success = (bool) $this->mailer->send($message);

            // if e-mail was sent
            if ($success) {
                $this->logger->debug(sprintf('Sent e-mail. %s', $serialized));

                // send idOS task completed status
                // $url = sprintf('%s/tasks/%s', $this->settings['idos']['baseUrl'], $data->task_id);
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
                $this->logger->debug(sprintf('Failed to send e-mail. %s', $serialized));
            }
        });

        while ($worker->work());
    }
}

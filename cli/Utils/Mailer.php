<?php
/*
 * Copyright (c) 2012-2016 Veridu Ltd <https://veridu.com>
 * All rights reserved.
 */
declare(strict_types = 1);

namespace Cli\Utils;

use Swift_Mailer;
use Swift_SmtpTransport;

/**
 * Command definition for Process-based Daemon.
 */
class Mailer {
    private $host;
    private $port;
    private $username;
    private $password;
    private $encryption;

    public function __construct(array $settings) {
        $this->host       = $settings['host'];
        $this->port       = $settings['port'];
        $this->username   = $settings['username'];
        $this->password   = $settings['password'];
        $this->encryption = $settings['encryption'];
    }

    /**
     * Send the email message.
     *
     * @param string $subject
     * @param string $from
     * @param string $to
     * @param string $body
     * @param string $bodyType
     *
     * @return bool
     */
    public function send(string $subject, string $from, string $to, string $body, string $bodyType) : bool {
        $transport = Swift_SmtpTransport::newInstance($this->host, $this->port)
            ->setUsername($this->username)
            ->setEncryption($this->encryption)
            ->setPassword($this->password);

        $mailer  = Swift_Mailer::newInstance($transport);
        $message = new \Swift_Message();
        $message
            ->setSubject($subject)
            ->setFrom($from)
            ->setTo($to)
            ->setBody($body, $bodyType);

        return (bool) $mailer->send($message);
    }
}

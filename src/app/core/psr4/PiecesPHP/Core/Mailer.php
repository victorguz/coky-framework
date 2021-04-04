<?php

/**
 * Mailer.php
 */
namespace PiecesPHP\Core;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PiecesPHP\Core\ConfigHelpers\MailConfig;

/**
 * Mailer - Enviar mails.
 *
 * @package     PiecesPHP\Core
 * @author      Vicsen Morantes <sir.vamb@gmail.com>
 * @copyright   Copyright (c) 2018
 * @extends <a target='blank' href='https://github.com/PHPMailer/PHPMailer'>\PHPMailer\PHPMailer\PHPMailer</a>
 */
class Mailer extends PHPMailer
{

    /**
     * Log
     *
     * @var array
     */
    protected $log = [];

    /**
     * @param bool $exceptions Establece si lanzará excepciones
     * @return void
     */
    public function __construct(bool $exceptions = true)
    {
        parent::__construct($exceptions);

        $this->CharSet = 'UTF-8';

        $mailConfig = new MailConfig;

        $this->SMTPDebug = $mailConfig->smtpDebug();
        $this->Host = $mailConfig->host();
        $this->Port = $mailConfig->port();
        $this->SMTPAutoTLS = $mailConfig->autoTls();
        $this->SMTPSecure = $mailConfig->protocol();
        $this->SMTPOptions = $mailConfig->smtpOptions();
        $this->SMTPAuth = $mailConfig->auth();
        $this->Username = $mailConfig->user();
        $this->Password = $mailConfig->password();

        if ($mailConfig->isSmtp()) {
            $this->isSMTP();
        }

        $this->Debugoutput = function ($str, $level) {

            if (!isset($this->log[$level])) {
                $this->log[$level] = [];
            }

            $this->log[$level][] = $str;

        };

    }

    /**
     * Devuelve el log
     *
     * @return array
     */
    public function log()
    {
        return $this->log;
    }

    /**
     * @inheritDoc
     */
    public function setFrom($address, $name = '', $uselessParam = true)
    {

        $domainsNotAllowedOtherFrom = [
            'yandex.com',
            'yandex.ru',
            'zoho.com',
        ];

        foreach ($domainsNotAllowedOtherFrom as $domain) {
            if (strpos($this->Host, $domain) !== false) {
                $address = $this->Username;
                $name = explode('@', $address)[0];
                break;
            }
        }

        return parent::setFrom($address, $name, $uselessParam);
    }

    /**
     * @param bool $sendmail
     * @return static
     */
    public function asGoDaddy(bool $sendmail = false)
    {
        if ($sendmail) {
            $this->isSendmail();
        } else {
            $this->isMail();
        }
        $this->SMTPAuth = false;
        $this->SMTPAutoTLS = false;
        $this->Host = 'localhost';
        $this->Port = 25;
        return $this;
    }

    /**
     * @return bool
     */
    public function checkSettedSMTP()
    {
        return self::checkSMTP($this->Host, $this->Port);
    }

    /**
     * @param string $host
     * @param integer $port
     * @return bool
     */
    public static function checkSMTP(string $host, int $port)
    {
        $smtp = new SMTP();
        return $smtp->connect($host, $port);
    }
}

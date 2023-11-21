<?php

declare(strict_types=1);

namespace app\modules\logger\factories;

use app\modules\logger\factories\concern\AbstractLoggerFactory;
use app\modules\logger\factories\concern\LogTypeInterface;
use app\modules\logger\models\LogMail;
use Exception;
use Yii;

class MailLogger extends AbstractLoggerFactory implements LogTypeInterface
{
    private const LOGGER_TYPE = 'mail';
    private const DEFAULT_SUBJECT = 'Logs';

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $config = Yii::$app->log[self::LOGGER_TYPE] ?? null;

        if (is_null($config)) {
            throw new Exception('Config not found');
        }
        $this->config = $config;
    }

    public function getInstance(): LogTypeInterface
    {
        return new self();
    }

    /**
     * @throws Exception
     */
    public function write(): void
    {
        if (empty($this->message)) {
            return;
        }

        $mail = new LogMail();
        $mailProperties = [
            'email' => $this->config['email'],
            'subject' => $this->config['subject'] ?? self::DEFAULT_SUBJECT,
            'body' => $this->message
        ];

        if ($mail->load($mailProperties) === false) {
            throw new Exception('Mail not sent: wrong data');
        }

        if ($mail->send() === false) {
            throw new Exception('Mail not sent');
        }

    }
}
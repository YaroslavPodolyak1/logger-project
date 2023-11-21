<?php

declare(strict_types=1);

namespace app\modules\logger;

use app\modules\logger\factories\concern\AbstractLoggerFactory;
use app\modules\logger\factories\concern\LogTypeInterface;
use Exception;

class Logger extends \yii\base\Module implements LoggerInterface
{
    protected string $type;

    public function init()
    {
        parent::init();

        $this->params['default_logger'] = \Yii::$app->log['default'];
    }

    /**
     * @throws Exception
     */
    public function send(string $message): void
    {
        $type = $this->getType();
        /** @var $logInstance AbstractLoggerFactory */
        $logInstance = new $type();

        if ($logInstance instanceof LogTypeInterface === false || $logInstance instanceof AbstractLoggerFactory === false) {
            throw new Exception('Invalid logger provider');
        }

        $logInstance->setMessage($message)->log();
    }

    /**
     * @throws Exception
     */
    public function sendByLogger(string $message, string $loggerType): void
    {
        $this->setType($loggerType);
        $this->send($message);
    }

    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @throws Exception
     */
    public function setType(string $type): void
    {
        $logConfig = \Yii::$app->log[$type] ?? $this->params['default_logger'];

        if (is_string($logConfig)) {
            $logConfig = \Yii::$app->log[$type][$logConfig];
        }

        $this->type = $logConfig['class'];
    }
}
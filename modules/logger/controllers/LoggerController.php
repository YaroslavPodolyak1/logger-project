<?php

declare(strict_types=1);

namespace app\modules\logger\controllers;

use app\modules\logger\Logger;
use yii\web\Controller;

class LoggerController extends Controller
{
    /**
     * Sends a log message to the default logger.
     * @throws \Exception
     */
    public function log()
    {
        Logger::getInstance()->send('message to the default logger');
    }

    /**
     * • Sends a log message to a special logger.
     *
     * @param string $type
     * @throws \Exception
     */
    public function logTo(string $type)
    {
        Logger::getInstance()->sendByLogger('log message to a special logger', $type);
    }

    /**
     * Sends a log message to all loggers.
     */
    public function logToAll()
    {
        Logger::getInstance()->sendByLogger('log message to a database', 'database');
        Logger::getInstance()->sendByLogger('log message to a file', 'file');
        Logger::getInstance()->sendByLogger('log message to a mail', 'mail');
    }
}
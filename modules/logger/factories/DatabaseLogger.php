<?php

declare(strict_types=1);

namespace app\modules\logger\factories;

use app\modules\logger\factories\concern\AbstractLoggerFactory;
use app\modules\logger\factories\concern\LogTypeInterface;
use app\modules\logger\models\Log;
use Exception;
use Yii;
use yii\base\InvalidConfigException;

class DatabaseLogger extends AbstractLoggerFactory implements LogTypeInterface
{
    private const LOGGER_TYPE = 'database';

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
     * @throws InvalidConfigException
     */
    public function write(): void
    {
        if (empty($this->message)) {
            return;
        }

        $log = new Log();
        $log->message = $this->message;
        $log->created_at = Yii::$app->formatter->asDate('now', 'yyyy-MM-dd');

        $log->save();
    }
}
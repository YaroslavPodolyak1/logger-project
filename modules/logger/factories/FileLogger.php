<?php

declare(strict_types=1);

namespace app\modules\logger\factories;

use app\modules\logger\factories\concern\AbstractLoggerFactory;
use app\modules\logger\factories\concern\LogTypeInterface;
use Exception;
use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\FileHelper;
use yii\log\LogRuntimeException;

class FileLogger extends AbstractLoggerFactory implements LogTypeInterface
{
    private int $dirMode = 0775;
    private const LOGGER_TYPE = 'file';

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
        if (trim($this->message) === '') {
            return;
        }
        if (!$this->existDir()) {
            $this->createDir();
        }

        if (($file = @fopen($this->getFilePath(), 'r+')) === false) {
            throw new \Exception('Can`t open file');
        }
        $writeResult = @fwrite($file, $this->message);
        if ($writeResult === false) {
            $error = error_get_last();
            throw new LogRuntimeException("Unable to export log through to file: " . $error['message']);
        }
        @fflush($file);
        @flock($file, LOCK_UN);
        @fclose($file);

    }

    private function existDir(): bool
    {
        return empty(FileHelper::findDirectories($this->config['path']));
    }

    /**
     * @throws Exception
     */
    private function createDir(): void
    {
        $created = FileHelper::createDirectory($this->config['path'], $this->dirMode);

        if (!$created) {
            throw new \Exception('Can`t created directory');
        }
    }

    /**
     * @throws InvalidConfigException
     */
    private function getFileName(): string
    {
        return 'log_' . Yii::$app->formatter->asDate('now', 'yyyy-MM-dd') . '.log';
    }

    /**
     * @throws InvalidConfigException
     */
    private function getFilePath(): string
    {
        return sprintf('%s/%s', rtrim($this->config['path']), $this->getFileName());
    }
}
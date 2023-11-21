<?php

declare(strict_types=1);

namespace app\modules\logger\factories\concern;

abstract class AbstractLoggerFactory
{
    protected string $message;
    protected array $config;

    abstract public function getInstance(): LogTypeInterface;

    public function log()
    {
        $this->getInstance()->write();
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }
}
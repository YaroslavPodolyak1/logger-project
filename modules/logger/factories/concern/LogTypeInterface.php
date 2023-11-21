<?php

declare(strict_types=1);

namespace app\modules\logger\factories\concern;

interface LogTypeInterface
{
    public function write(): void;
}
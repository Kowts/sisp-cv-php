<?php

declare(strict_types=1);

namespace yii\base;

interface BootstrapInterface
{
    /**
     * @param mixed $app
     */
    public function bootstrap($app): void;
}

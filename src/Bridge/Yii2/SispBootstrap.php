<?php

declare(strict_types=1);

namespace Kowts\Sisp\Bridge\Yii2;

use yii\base\BootstrapInterface;

final class SispBootstrap implements BootstrapInterface
{
    /**
     * @param mixed $app
     */
    public function bootstrap($app): void
    {
        if (! isset($app->sisp) && method_exists($app, 'set')) {
            $app->set('sisp', ['class' => SispComponent::class]);
        }
    }
}

<?php

declare(strict_types=1);

namespace Kowts\Sisp\Bridge\Yii2;

use Kowts\Sisp\Config\SispConfig;
use Kowts\Sisp\Sisp;
use Kowts\Sisp\SispFactory;
use yii\base\Component;

final class SispComponent extends Component
{
    /**
     * @var array<string,mixed>
     */
    public array $config = [];

    private ?Sisp $client = null;

    public function getClient(): Sisp
    {
        if ($this->client === null) {
            $this->client = SispFactory::create(SispConfig::fromArray($this->config));
        }

        return $this->client;
    }

    /**
     * @param mixed[] $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return $this->getClient()->{$name}(...$arguments);
    }
}

<?php

declare(strict_types=1);

namespace Kowts\Sisp\Bridge\Symfony;

use Symfony\Component\HttpKernel\Bundle\Bundle;

final class SispBundle extends Bundle
{
    public function getContainerExtension(): SispExtension
    {
        return new SispExtension();
    }
}

<?php

namespace Meals\Application\Component\Provider;

use Meals\Domain\Date\Date;

interface DateProviderInterface
{
    public function getDate(): Date;
}

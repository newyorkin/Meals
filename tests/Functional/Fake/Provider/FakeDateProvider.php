<?php

namespace tests\Meals\Functional\Fake\Provider;

use Meals\Application\Component\Provider\DateProviderInterface;
use Meals\Domain\Date\Date;

class FakeDateProvider implements DateProviderInterface
{
    /** @var Date */
    private $date;
    
    public function getDate(): Date
    {
        return $this->date;
    }
    
    public function setDate(Date $date)
    {
        $this->date = $date;
    }
}

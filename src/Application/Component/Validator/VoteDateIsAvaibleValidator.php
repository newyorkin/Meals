<?php

namespace Meals\Application\Component\Validator;

use Meals\Application\Component\Validator\Exception\PollIsNotActiveException;
use Meals\Domain\Date\Date;
use Meals\Domain\Date\Permission\Permission;

class VoteDateIsAvaibleValidator
{
    public function validate(Date $date): void
    {
        if (!in_array($date->getDay(), Permission::DAY_ACTIVE_POLL)) {
            throw new PollIsNotActiveException();
        }
        
        if (!in_array($date->getHour(), Permission::HOUR_ACTIVE_POLL)) {
            throw new PollIsNotActiveException();
        }
    }
}

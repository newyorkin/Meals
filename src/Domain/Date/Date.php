<?php

namespace Meals\Domain\Date;

use \DateTime;

class Date
{
    /** @var string */
    private $timestamp;
    /** @var string */
    private $day;
    /** @var string */
    private $hour;
    
    /**
     * Date constructor.
     *
     * @param DateTime $date
     */
    public function __construct(DateTime $date)
    {
        $this->timestamp = $date->getTimestamp();
        $this->day       = date('D', $this->timestamp);
        $this->hour      = date('H', $this->timestamp);
    }
    
    /**
     * @return string
     */
    public function getTimestamp(): string
    {
        return $this->timestamp;
    }
    
    /**
     * @return string
     */
    public function getDay(): string
    {
        return $this->day;
    }
    
    /**
     * @return string
     */
    public function getHour(): string
    {
        return $this->hour;
    }
}

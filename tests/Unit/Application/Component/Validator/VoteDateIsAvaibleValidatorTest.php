<?php

namespace tests\Meals\Unit\Application\Component\Validator;

use DateTime;
use Meals\Application\Component\Validator\Exception\PollIsNotActiveException;
use Meals\Application\Component\Validator\VoteDateIsAvaibleValidator;
use Meals\Domain\Date\Date;
use PHPUnit\Framework\TestCase;

class VoteDateIsAvaibleValidatorTest extends TestCase
{
    public function testSuccessful()
    {
        $testedDate = new Date(new DateTime('2021-10-11 07:01:01')); //понедельник, 7 утра
        
        $validator = new VoteDateIsAvaibleValidator();
        verify($validator->validate($testedDate))->null();
    }
    
    /**
     * @dataProvider dateDataProvider
     *
     * @param DateTime $dateTime
     */
    public function testFail(DateTime $dateTime)
    {
        $this->expectException(PollIsNotActiveException::class);
        
        $testedDate = new Date($dateTime);
        $validator  = new VoteDateIsAvaibleValidator();
        $validator->validate($testedDate);
    }
    
    public function dateDataProvider(): array
    {
        return [
            [ new DateTime('2021-10-11 22:02:21') ], //понедельник, позже 22
            [ new DateTime('2021-10-12 11:01:01') ], //вторник
            [ new DateTime('2021-10-17 11:01:01') ], //воскресение
        ];
    }
}

<?php

namespace Meals\Application\Feature\Poll\UseCase\EmployeeSetsActivePollResult;

use Meals\Application\Component\Provider\DateProviderInterface;
use Meals\Application\Component\Provider\EmployeeProviderInterface;
use Meals\Application\Component\Provider\PollProviderInterface;
use Meals\Application\Component\Provider\DishProviderInterface;
use Meals\Application\Component\Validator\PollIsActiveValidator;
use Meals\Application\Component\Validator\UserHasAccessToParticipationPollsValidator;
use Meals\Application\Component\Validator\VoteDateIsAvaibleValidator;
use Meals\Domain\Date\Date;
use Meals\Domain\Poll\PollResult;

class Interactor
{
    /** @var EmployeeProviderInterface */
    private $employeeProvider;
    /** @var PollProviderInterface */
    private $pollProvider;
    /** @var DateProviderInterface */
    private $dateProvider;
    /** @var UserHasAccessToParticipationPollsValidator */
    private $userHasAccessToPollsValidator;
    /** @var PollIsActiveValidator */
    private $pollIsActiveValidator;
    /** @var VoteDateIsAvaibleValidator */
    private $voteDateIsAvaibleValidator;
    /** @var DishProviderInterface */
    private $dishProvider;
    
    /**
     * Interactor constructor.
     *
     * @param EmployeeProviderInterface                  $employeeProvider
     * @param PollProviderInterface                      $pollProvider
     * @param DishProviderInterface                      $dishProvider
     * @param DateProviderInterface                      $dateProvider
     * @param UserHasAccessToParticipationPollsValidator $userHasAccessToPollsValidator
     * @param PollIsActiveValidator                      $pollIsActiveValidator
     * @param VoteDateIsAvaibleValidator                 $voteDateIsAvaibleValidator
     */
    public function __construct(
        EmployeeProviderInterface $employeeProvider,
        PollProviderInterface $pollProvider,
        DishProviderInterface $dishProvider,
        DateProviderInterface $dateProvider,
        UserHasAccessToParticipationPollsValidator $userHasAccessToPollsValidator,
        PollIsActiveValidator $pollIsActiveValidator,
        VoteDateIsAvaibleValidator $voteDateIsAvaibleValidator
    )
    {
        $this->employeeProvider              = $employeeProvider;
        $this->pollProvider                  = $pollProvider;
        $this->dishProvider                  = $dishProvider;
        $this->dateProvider                  = $dateProvider;
        $this->userHasAccessToPollsValidator = $userHasAccessToPollsValidator;
        $this->pollIsActiveValidator         = $pollIsActiveValidator;
        $this->voteDateIsAvaibleValidator    = $voteDateIsAvaibleValidator;
    }
    
    public function setPollResult(int $pollId, int $employeeId, int $dishId, Date $date): PollResult
    {
        $poll     = $this->pollProvider->getPoll($pollId);
        $employee = $this->employeeProvider->getEmployee($employeeId);
        $dish     = $this->dishProvider->getDish($dishId);
        $date     = $this->dateProvider->getDate($date);
        
        $this->userHasAccessToPollsValidator->validate($employee->getUser());
        $this->voteDateIsAvaibleValidator->validate($date);
        $this->pollIsActiveValidator->validate($poll);
        //todo: not sure first arg is pollId
        return new PollResult($poll->getId(), $poll, $employee, $dish, $employee->getFloor());


    }
}

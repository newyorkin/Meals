<?php

namespace tests\Meals\Functional\Interactor;

use DateTime;
use Exception;
use Meals\Application\Feature\Poll\UseCase\EmployeeSetsActivePollResult\Interactor;
use Meals\Application\Component\Validator\Exception\AccessDeniedException;
use Meals\Application\Component\Validator\Exception\PollIsNotActiveException;
use Meals\Domain\Date\Date;
use Meals\Domain\Dish\DishList;
use Meals\Domain\Employee\Employee;
use Meals\Domain\Menu\Menu;
use Meals\Domain\Poll\Poll;
use Meals\Domain\Poll\PollResult;
use Meals\Domain\Dish\Dish;
use Meals\Domain\User\Permission\Permission;
use Meals\Domain\User\Permission\PermissionList;
use Meals\Domain\User\User;
use tests\Meals\Functional\Fake\Provider\FakeEmployeeProvider;
use tests\Meals\Functional\Fake\Provider\FakePollProvider;
use tests\Meals\Functional\Fake\Provider\FakeDishProvider;
use tests\Meals\Functional\Fake\Provider\FakeDateProvider;
use tests\Meals\Functional\FunctionalTestCase;

class EmployeeSetsActivePollTest extends FunctionalTestCase
{
    public function testSuccessful()
    {
        $pollResult = $this->performTestMethod(
            $this->getEmployeeWithPermissions(),
            $this->getPoll(true),
            $this->getDish(),
            $this->getDate()
        );
        
        verify($pollResult->getDish())->equals($this->getDish());
        verify($pollResult->getEmployee())->equals($this->getEmployeeWithPermissions());
        verify($pollResult->getPoll())->equals($this->getPoll(true));
    }

    /**
     * @skip
     */
    public function testUserHasNotPermissions()
    {
        $this->expectException(AccessDeniedException::class);

        $pollResult = $this->performTestMethod(
            $this->getEmployeeWithNoPermissions(),
            $this->getPoll(true),
            $this->getDish(),
            $this->getDate()
        );
        verify($pollResult)->equals($pollResult);
    }

    public function testVoteInWrongTime()
    {
        $this->expectException(PollIsNotActiveException::class);
    
        $pollResult = $this->performTestMethod(
            $this->getEmployeeWithPermissions(),
            $this->getPoll(true),
            $this->getDish(),
            $this->getDateUnavaibleToVote()
        );
        verify($pollResult)->equals($pollResult);
    }

    /**
     *
     * Perform Poll\UseCase\EmployeeSetsActivePollResult->setPollResult
     *
     * @param Employee $employee
     * @param Poll     $poll
     * @param Dish     $dish
     * @param Date     $date
     *
     * @return PollResult
     * @throws Exception
     */
    private function performTestMethod(Employee $employee, Poll $poll, Dish $dish, Date $date): PollResult
    {
        $this->getContainer()->get(FakeEmployeeProvider::class)->setEmployee($employee);
        $this->getContainer()->get(FakePollProvider::class)->setPoll($poll);
        $this->getContainer()->get(FakeDishProvider::class)->setDish($dish);
        $this->getContainer()->get(FakeDateProvider::class)->setDate($date);

        return $this->getContainer()->get(Interactor::class)->setPollResult($poll->getId(), $employee->getId(), $dish->getId(), $date);
    }

    private function getEmployeeWithPermissions(): Employee
    {
        return new Employee(
            1,
            $this->getUserWithPermissions(),
            4,
            'Surname'
        );
    }

    private function getUserWithPermissions(): User
    {
        return new User(
            1,
            new PermissionList(
                [
                    new Permission(Permission::PARTICIPATION_IN_POLLS),
                ]
            ),
        );
    }

    private function getEmployeeWithNoPermissions(): Employee
    {
        return new Employee(
            1,
            $this->getUserWithNoPermissions(),
            4,
            'Surname'
        );
    }

    private function getUserWithNoPermissions(): User
    {
        return new User(
            1,
            new PermissionList([]),
        );
    }

    private function getPoll(bool $active): Poll
    {
        return new Poll(
            1,
            $active,
            new Menu(
                1,
                'title',
                new DishList([]),
            )
        );
    }

    private function getDish(): Dish
    {
        return new Dish(
            1,
            'Суп',
            'Гуляш'
        );
    }

    private function getDate(): Date
    {
        return new Date(
            new DateTime('2021-10-18 08:44')
        );
    }

    private function getDateUnavaibleToVote(): Date
    {
        return new Date(
            new DateTime('2021-10-17 05:44')
        );
    }
}

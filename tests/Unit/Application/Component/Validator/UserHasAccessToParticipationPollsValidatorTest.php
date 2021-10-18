<?php

namespace tests\Meals\Unit\Application\Component\Validator;

use Meals\Application\Component\Validator\Exception\AccessDeniedException;
use Meals\Application\Component\Validator\UserHasAccessToParticipationPollsValidator;
use Meals\Domain\User\Permission\Permission;
use Meals\Domain\User\Permission\PermissionList;
use Meals\Domain\User\User;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class UserHasAccessToParticipationPollsValidatorTest extends TestCase
{
    use ProphecyTrait;
    
    public function testSuccessful()
    {
        $permissionList = $this->prophesize(PermissionList::class);
        $permissionList->hasPermission(Permission::PARTICIPATION_IN_POLLS)->willReturn(true);
        
        $user = $this->prophesize(User::class);
        $user->getPermissions()->willReturn($permissionList->reveal());
        
        $validator = new UserHasAccessToParticipationPollsValidator();
        verify($validator->validate($user->reveal()))->null();
    }
    
    public function testFail()
    {
        $this->expectException(AccessDeniedException::class);
        
        $permissionList = $this->prophesize(PermissionList::class);
        $permissionList->hasPermission(Permission::PARTICIPATION_IN_POLLS)->willReturn(false);
        
        $user = $this->prophesize(User::class);
        $user->getPermissions()->willReturn($permissionList->reveal());
        
        $validator = new UserHasAccessToParticipationPollsValidator();
        $validator->validate($user->reveal());
    }
}

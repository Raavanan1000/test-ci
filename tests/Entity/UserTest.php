<?php

namespace App\Tests\Entity;

use App\Entity\TodoList;
use App\Entity\User;
use PHPUnit\Framework\TestCase;
use Carbon\Carbon;

class UserTest extends TestCase
{

    private User $user;

    protected function setUp(): void
    {
        $this->user = new User();

        $this->user->setEmail('unit@test.esgi');
        $this->user->setFirstName('first_name');
        $this->user->setLastName('last_name');
        $this->user->setDateNaissance(Carbon::now()->subYears(20));
        $this->user->setPassword(random_bytes(20));
        $this->user->setRoles([]);
        $this->user->setTodoList(new TodoList());
        parent::setUp();
    }


    public function testIsValidNominal()
    {
        $this->assertTrue($this->user->isValid());
    }


    public function testNotValidBadEmail()
    {
        $this->user->setEmail('noEmail');
        $this->assertFalse($this->user->isValid());
    }

    public function testNotValidEmptyEmail()
    {
        $this->user->setEmail('');
        $this->assertFalse($this->user->isValid());
    }


    public function testNotValidDueToFName()
    {
        $this->user->setFirstName('');
        $this->assertFalse($this->user->isValid());
    }

    public function testNotValidDueToLName()
    {
        $this->user->setLastName('');
        $this->assertFalse($this->user->isValid());
    }



    public function testNotValidBadPasswordTooSmall()
    {
        $this->user->setPassword('NoMdp');
        $this->assertFalse($this->user->isValid());
    }

    public function testNotValidBadPasswordTooBig()
    {
        $this->user->setPassword(random_bytes(50));
        $this->assertFalse($this->user->isValid());
    }



    public function testNotValidDueToBirthdayInFuture()
    {
        $this->user->setDateNaissance(Carbon::now()->addDecade());
        $this->assertFalse($this->user->isValid());
    }

    public function testNotValidDueToTooYoungUser()
    {
        $this->user->setDateNaissance(Carbon::now()->subDecade());
        $this->assertFalse($this->user->isValid());
    }


    public function testNotValidDateNull()
    {
        $user = new User();

        $user->setEmail('unit@test.esgi');
        $user->setFirstName('first_name');
        $user->setLastName('last_name');
        $user->setPassword(random_bytes(20));
        $user->setRoles([]);
        $user->setTodoList(new TodoList());
        $this->assertFalse($user->isValid());
    }

    public function testNotValidTodoListNull()
    {
        $user = new User();

        $user->setEmail('unit@test.esgi');
        $user->setFirstName('first_name');
        $user->setLastName('last_name');
        $this->user->setDateNaissance(Carbon::now()->subYears(20));
        $user->setPassword(random_bytes(20));
        $user->setTodoList(new TodoList());
        $this->assertFalse($user->isValid());
    }

}

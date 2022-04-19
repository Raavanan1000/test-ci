<?php

namespace App\Tests\Entity;

use App\Entity\Item;
use App\Entity\TodoList;
use App\Entity\EmailSenderService;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class TodoListTest extends TestCase
{
    private TodoList $todoList;
    private $emailSenderService;


    protected function setUp(): void
    {

        $this->emailSenderService = $this->getMockBuilder(EmailSenderService::class)
        ->onlyMethods(['sendEmail'])
        ->getMock();

        $this->todoList = new TodoList();
        $nbr_items = 7;
        for($i=0 ; $i<$nbr_items ; $i++){
            $this->todoList->addItem($this->createItem($i));
        }

        parent::setUp();
    }

    public function testIsValidNominal()
    {
        $this->assertTrue($this->todoList->isValid());
    }

    public function testNotValidDueToItemsTooBig()
    {
        $this->todoList->setEmailSenderService($this->emailSenderService);

        for($i=7 ; $i<12 ; $i++){
            $this->todoList->addItem($this->createItem($i));
        }

        $this->assertFalse($this->todoList->isValid());
    }


    public function testIsAddItemNominal()
    {
        $this->todoList->setEmailSenderService($this->emailSenderService);

        $item = $this->createItem(7);
        $this->todoList->addItem($item);
        $this->assertContains($item, $this->todoList->getItems());
    }

    public function testShouldSendEmailOnAddingEighthItem()
    {
        $this->todoList->setEmailSenderService($this->emailSenderService);

        $this->emailSenderService->expects($this->once())
            ->method('sendEmail');

        $item = $this->createItem(7);
        $this->todoList->addItem($item);
    }

    public function testIsAddItemNominal8()
    {
        $this->todoList->setEmailSenderService($this->emailSenderService);

        $item = $this->createItem(8);
        $this->todoList->addItem($item);

        $this->assertContains($item, $this->todoList->getItems());
    }

    public function testNotAddingItemDueToItemAlreadyExists()
    {
        $this->todoList->setEmailSenderService($this->emailSenderService);

        $item = $this->createItem(6);
        $this->todoList->addItem($item);
        $this->assertCount(7, $this->todoList->getItems());
    }

    public function testNotAddItemDueToFast()
    {
        $this->todoList->setEmailSenderService($this->emailSenderService);

        $item8 = $this->createItem(7);
        $this->todoList->addItem($item8);

        $item9 = $this->createItem(8);
        $item9->setDateAtCreated($item8->getDateAtCreated());
        $this->todoList->addItem($item9);

        $this->assertCount(8, $this->todoList->getItems());
    }

    private function createItem($i): Item{
        $item = new Item();
        $item->setName("name_item_{$i}");
        $item->setContent("content_item_{$i}");
        $item->setDateAtCreated(Carbon::now()->addMinutes(31*$i));
        $item->setTodoList($this->todoList);

        return $item;
    }


}
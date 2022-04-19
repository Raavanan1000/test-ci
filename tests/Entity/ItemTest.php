<?php

namespace App\Tests\Entity;

use App\Entity\Item;
use App\Entity\TodoList;
use PHPUnit\Framework\TestCase;
use Carbon\Carbon;

class ItemTest extends TestCase
{

    private Item $item;

    protected function setUp(): void
    {
        $this->item = new Item();
        $this->item->setName("name_item");
        $this->item->setContent("content_item");
        $this->item->setDateAtCreated(Carbon::now());
        $this->item->setTodoList(new TodoList());

        parent::setUp();
    }

    public function testIsValidNominal()
    {
        $this->assertTrue($this->item->isValid());
    }


    public function testNotValidDueToNameEmpty()
    {
        $this->item->setName('');
        $this->assertFalse($this->item->isValid());
    }

    public function testNotValidDueToContent()
    {
        $this->item->setContent(random_bytes(1050));
        $this->assertFalse($this->item->isValid());
    }

    public function testNotValidDueToDateNull()
    {
        $item = new Item();
        $item->setName("name_item");
        $item->setContent("content_item");
        $this->assertFalse($item->isValid());
    }

    public function testNotValidDueToTodoListNull()
    {
        $item = new Item();
        $item->setName("name_item");
        $item->setContent("content_item");
        $this->item->setDateAtCreated(Carbon::now());
        $this->assertFalse($item->isValid());
    }

}

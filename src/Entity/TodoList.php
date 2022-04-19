<?php

namespace App\Entity;

use App\Repository\ItemRepository;
use App\Repository\TodoListRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\EmailSenderService;
use Carbon\Carbon;

/**
 * @ORM\Entity(repositoryClass=TodoListRepository::class)
 */
class TodoList
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=Item::class, mappedBy="todoList", orphanRemoval=true)
     */
    private $items;

    /**
     * @ORM\OneToOne(targetEntity=User::class, mappedBy="todoList", cascade={"persist", "remove"})
     */
    private $userTodoList;

    private EmailSenderService $emailSenderService;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    public function isValid(): bool
    {
        return (count($this->items)>=0 && count($this->items)<=10);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Item[]
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(Item $item): self
    {
        if(!$this->items->isEmpty()) {
            if (!$this->items->contains($item)) {
                if (Carbon::parse($item->getDateAtCreated())->subMinute(30)->isAfter($this->items->last()->getDateAtCreated())) {
                    $this->items->add($item);
                    $item->setTodoList($this);
                }
            }
            // Envoie du  mail
            if (count($this->items)==8){
                //dump("send email");
                $this->emailSenderService->sendEmail("test@toto.fr", "2 Items restants");
            }
        }
        else{
            if(count($this->items) < 10){
                $this->items->add($item);
                $item->setTodoList($this);
            }
        }

        return $this;
    }

    public function removeItem(Item $item): self
    {
        if ($this->items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getTodoList() === $this) {
                $item->setTodoList(null);
            }
        }

        return $this;
    }

    public function getUserTodoList(): ?User
    {
        return $this->userTodoList;
    }

    public function setUserTodoList(User $userTodoList): self
    {
        // set the owning side of the relation if necessary
        if ($userTodoList->getTodoList() !== $this) {
            $userTodoList->setTodoList($this);
        }

        $this->userTodoList = $userTodoList;

        return $this;
    }

    public function setEmailSenderService(EmailSenderService $emailSenderService): self
    {
        $this->emailSenderService = $emailSenderService;
        return $this;
    }
}

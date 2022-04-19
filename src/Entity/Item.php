<?php

namespace App\Entity;

use App\Repository\ItemRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ItemRepository", repositoryClass=ItemRepository::class)
 */
class Item
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     */
    private $content;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $dateAtCreated;

    /**
     * @ORM\ManyToOne(targetEntity=TodoList::class, inversedBy="items")
     * @ORM\JoinColumn(nullable=false)
     */
    private $todoList;


    public function isValid(): bool
    {
        return !empty($this->name)
            && (strlen($this->content)<=1000)
            && !is_null($this->todoList)
            && !is_null($this->dateAtCreated);
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getDateAtCreated(): \DateTime
    {
        return $this->dateAtCreated;
    }

    public function setDateAtCreated(\DateTime $dateAtCreated): self
    {
        $this->dateAtCreated = $dateAtCreated;

        return $this;
    }

    public function getTodoList(): ?TodoList
    {
        return $this->todoList;
    }

    public function setTodoList(?TodoList $todoList): self
    {
        $this->todoList = $todoList;

        return $this;
    }

    public function __toString()
    {
        return $this->name. " " . $this->content . " " .  $this->dateAtCreated;
    }


}

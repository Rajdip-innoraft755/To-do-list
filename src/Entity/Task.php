<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $TaskContent = null;

    #[ORM\Column(length: 255)]
    private ?string $TaskAddTime = null;

    #[ORM\Column(length: 10)]
    private ?string $Done = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTaskContent(): ?string
    {
        return $this->TaskContent;
    }

    public function setTaskContent(string $TaskContent): self
    {
        $this->TaskContent = $TaskContent;

        return $this;
    }

    public function getTaskAddTime(): ?string
    {
        return $this->TaskAddTime;
    }

    public function setTaskAddTime(string $TaskAddTime): self
    {
        $this->TaskAddTime = $TaskAddTime;

        return $this;
    }

    public function getDone(): ?string
    {
        return $this->Done;
    }

    public function setDone(string $Done): self
    {
        $this->Done = $Done;

        return $this;
    }
}

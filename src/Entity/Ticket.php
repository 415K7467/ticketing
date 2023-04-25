<?php

namespace App\Entity;

use App\Repository\TicketRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TicketRepository::class)]
class Ticket
{
    const PRIORITY_HIGH = 1;
    const PRIORITY_MED = 2;
    const PRIORITY_LOW = 3;

    const STATUS_NEW = 1;
    const STATUS_INPROGRESS = 2;
    const STATUS_RESOLVED = 3;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $priority = null;

    #[ORM\Column(length: 255)]
    private ?int $status = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'tickets')]
    #[ORM\JoinColumn(name: 'author_id', referencedColumnName: 'id')]
    private ?User $author;

    #[ORM\Column(type: "datetime")]
    private DateTimeInterface|null $createDate;

    #[ORM\Column(type: "datetime", nullable: true)]
    private DateTimeInterface|null $updateDate = null;

    public function __construct()
    {
        $this->createDate = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPriority(): ?int
    {
        return $this->priority;
    }

    public function setPriority(int $priority): self
    {
        $this->priority = $priority;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getAuthor(): User
    {
        return $this->author;
    }

    public function setAuthor(User $author){
        $this->author=$author;
    }

    public function getCreateDate(): ?DateTimeInterface
    {
        return $this->createDate;
    }

    public function setCreateDate(DateTimeInterface $createDate): self
    {
        $this->createDate = $createDate;

        return $this;
    }

    public function getUpdateDate(): ?DateTimeInterface
    {
        return $this->updateDate;
    }

    public function setUpdateDate(DateTimeInterface $updateDate): self
    {
        $this->updateDate = $updateDate;

        return $this;
    }

    public function remove(TicketRepository $ticketRepository): void
    {
        $ticketRepository->remove($this);
    }

}

<?php
namespace App\Service;

use App\Entity\Ticket;
use App\Repository\TicketRepository;
use Doctrine\ORM\EntityManagerInterface;

class StatService
{
    private int $globalCount = 0;
    private int $priorityHigh = 0;
    private int $priorityMedium = 0;
    private int $priorityLow = 0;
    private int $statusNew = 0;
    private int $statusProgress = 0;
    private int $statusResolved = 0;

    public function __construct(private TicketRepository $ticketRepository)
    {
        $this->setAll();
    }

    public function getAll(): array
    {
        return [
            'global_count' => $this->globalCount,
            'priorityHigh' => $this->priorityHigh,
            'priorityMedium' => $this->priorityMedium,
            'priorityLow' => $this->priorityLow,
            'statusNew' => $this->statusNew,
            'statusProgress' => $this->statusProgress,
            'statusResolved' => $this->statusResolved,
        ];
    }

    public function setAll(): void
    {
        $ticketStats = $this->ticketRepository->getPriorityStatus();
        foreach ($ticketStats as $stat) {
            $this->globalCount += 1;
            switch ($stat['priority']) {
                case Ticket::PRIORITY_HIGH:
                    $this->priorityHigh++;
                    break;
                case Ticket::PRIORITY_MED:
                    $this->priorityMedium++;
                    break;
                case Ticket::PRIORITY_LOW:
                    $this->priorityLow += 1;
                    break;
            }
            switch ($stat['status']) {
                case Ticket::STATUS_NEW:
                    $this->statusNew += 1;
                    break;
                case Ticket::STATUS_INPROGRESS:
                    $this->statusProgress += 1;
                    break;
                case Ticket::STATUS_RESOLVED:
                    $this->statusResolved += 1;
                    break;
            }
        }
    }
}
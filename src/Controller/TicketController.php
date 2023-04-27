<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Form\LimitTicketType;
use App\Form\TicketType;
use App\Repository\TicketRepository;
use App\Service\StatService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use DateTime;
use Symfony\Component\Security\Core\Security;

class TicketController extends AbstractController
{
    /**
     * @var Security
     */
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route('/', name: 'app_ticket')]
    public function index(TicketRepository $repository): Response
    {
        $tickets = $repository->findAll();

        return $this->render('ticket/index.html.twig', [
            'controller_name' => 'TicketController',
            'tickets' => $tickets,
        ]);
    }

    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[Route('/new', name: 'new_ticket', methods: ['GET', 'POST'])]
    public function new(Request $request, TicketRepository $ticketRepository): Response
    {
        $user = $this->security->getUser();
        if ($user != null) {
            $ticket = new Ticket();
            $ticket->setAuthor(author: $this->getUser());
            $form = $this->createForm(TicketType::class, $ticket)
                ->add('saveAndCreateNew', SubmitType::class);

            if ($request->isMethod('POST')) {
                $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid()) {
                    $ticketRepository->save($ticket, true);

                    $submit = $form->get('saveAndCreateNew');
                    if ($submit->isClicked()) {
                        return $this->redirectToRoute('new_ticket');
                    }

                    return $this->redirectToRoute('app_ticket');
                }
            }

            return $this->render('ticket/new.html.twig', [
                'ticket' => $ticket,
                'form' => $form,
            ]);
        }

        return $this->redirectToRoute('app_login');
    }

    #[Route('/ticket/{id}', name: 'ticket_show')]
    public function show(TicketRepository $ticketRepository, int $id): Response
    {
        $ticket = $ticketRepository->find($id);

        if ( !$ticket) {
            throw $this->createNotFoundException(
                'No ticket found for id:'.$id
            );
        }

        return $this->render('components/ticket.twig', [
            'ticket' => $ticket,
        ]);
    }

    #[Route('/ticket/{id}/edit', name: 'ticket_edit')]
    public function edit(TicketRepository $ticketRepository, Request $request, Ticket $ticket): Response
    {
        if ($this->isGranted('ROLE_USER')){
            $form = $this->createForm(LimitTicketType::class, $ticket);
        }
        else {
            $form = $this->createForm(TicketType::class, $ticket);
        }
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $ticket = $form->getData();

            $time = new DateTime();
            $ticket->setUpdateDate($time);

            $ticketRepository->save($ticket, true);
            return $this->redirectToRoute('ticket_show', ['id' => $ticket->getId()]);
        }

        return $this->render('ticket/edit.html.twig', [
            'form' => $form->createView(),
            'ticket' => $ticket,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[IsGranted('ROLE_SUPPORT')]
    #[Route('/ticket/{id}/delete', name: 'ticket_delete')]
    public function delete(TicketRepository $ticketRepository, Ticket $ticket): Response
    {
        $ticketRepository->remove($ticket, true);
        return $this->redirectToRoute('app_ticket');
    }

    public function stats(StatService $stats): Response
    {
        return $this->render('components/navBar.twig', ['counts' => $stats->getAll()]);
    }
}

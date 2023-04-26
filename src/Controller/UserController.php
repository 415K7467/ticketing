<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('ROLE_ADMIN')]
class UserController extends AbstractController
{
    #[Route(path: 'admin/', name: 'app_users')]
    public function listUsers(UserRepository $repository): Response
    {
        $users = $repository->findAll();

        return $this->render('admin/listUser.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route(path: 'admin/{id}/edit', name: 'user_edit')]
    public function editUser(UserRepository $userRepository, Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->setRole($user, $form->getData()->getRoles());

            return $this->redirectToRoute('app_users');
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    #[Route(path: 'admin/{id}/delete', name: 'user_delete')]
    public function deleteUser(UserRepository $userRepository, User $user): Response
    {
        $userRepository->remove($user, true);

        return $this->redirectToRoute('app_users');
    }

}

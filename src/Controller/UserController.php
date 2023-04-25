<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route(path: 'admin/users', name: 'app_users')]
    public function listUsers(UserRepository $repository): Response
    {
        $users = $repository->findAll();

        return $this->render('admin/listUser.html.twig', [
            'users' => $users,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route(path: 'admin/{id}/delete', name: 'user_delete')]
    public function deleteUser(UserRepository $userRepository, User $user): Response
    {
        $userRepository->remove($user, true);

        return $this->redirectToRoute('app_users');
    }

}

<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserRoleType;
use App\Repository\UserRepository;
use JetBrains\PhpStorm\NoReturn;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route(path: 'admin/users', name: 'app_users')]
    public function listUsers(Request $request, UserRepository $repository): Response
    {
        $users = $repository->findAll();

        return $this->render('admin/listUser.html.twig', [
            'users' => $users,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route(path: 'admin/{id}/edit', name: 'user_edit')]
    public function editUser(Request $request, UserRepository $userRepository, int $id): Response
    {
        $user = $userRepository->find($id);
        $form = $this->createForm(UserRoleType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            return $this->redirectToRoute('app_users');
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
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

<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/user')]
final class UserController extends AbstractController
{
    #[Route(name: 'app_user_index', methods: ['GET'])]
    public function index(Request $request, UserRepository $userRepository): Response
    {
        $size = isset($request->query->all()['size']) ? (int)$request->query->get('size') : 10;
        $page = isset($request->query->all()['page']) ? (int)$request->query->get('page') : 1;

        $data = $userRepository->findPaginate($size, $page);

        return $this->render('admin/user/index.html.twig', [
            'users' => $data['users'],
            'count' => $data['count'],
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $form->get('password')->getData()
            );
            $user->setPassword($hashedPassword);


            $roleValue = $form->get('role')->getData();
            if (!$roleValue) {
                $user->setRoles([$roleValue]);
            } else {
                $user->setRoles([]);
            }

            // version ternaire : $user->setRoles(!$roleValue ? [$roleValue] : []);

            $user->setRoles([$roleValue]);

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index');
        }

        return $this->render('admin/user/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('admin/user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(User $user, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->remove('password');

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $roleValue = $form->get('role')->getData();
            $user->setRoles(!$roleValue ? [$roleValue] : []);

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index');
        }

        return $this->render('admin/user/edit.html.twig', [
            'form' => $form,
            'user' => $user
        ]);
    }

    #[Route('/{id}/delete', name: 'app_user_delete', methods: ['POST'])]
    public function delete(User $user, Request $request, EntityManagerInterface $entityManager): Response
    {
        $submittedToken = $request->getPayload()->get('token');

        if ($this->isCsrfTokenValid('delete-item', $submittedToken)) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
}


<?php

namespace App\Controller\Front;

use App\Entity\User;
use App\Form\Front\UserEditType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;

/**
 * @Route("/compte"), name"app_front_")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/{slug}", name="user_show", methods={"GET"})
     */
    public function show()
    {
        $user = $this->getUser();
        if ($user === null) {
            throw $this->createNotFoundException('Utilisateur non trouvé');
        }
        return $this->render('front/user/show.html.twig');
    }

    /**
     * @Route("/{slug}/modifier", name="user_edit", methods={"GET", "POST"})
     */
    public function edit(User $user, Request $request,UserRepository $userRepository, EntityManagerInterface $manager, UserPasswordHasherInterface $passwordHasher) 
    {
        $user = $this->getUser();
        $form = $this->createForm(UserEditType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('password')->getData()) {
                $plainTextPassword = $form->get('password')->getData();

                $hashedPassword = $passwordHasher->hashPassword($user, $plainTextPassword);

                $user->setPassword($hashedPassword);
            }

            if ($form->get('gamerTag')->getData()) {
                $slugger = new AsciiSlugger();
                $createSlug = $slugger->slug($user->getGamerTag());
                $createSlug = strtolower($createSlug);
                $user->setSlug($createSlug);
            }

            $userRepository->add($user, true);
            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', 'Profil mis à jour');
            return $this->redirectToRoute('user_show',['slug' => $user->getSlug()]);
        }

        return $this->render('front/user/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
   
    }

    /**
     * @Route("/{slug}/supprimer", name="user_delete", methods={"GET", "POST"})
     */
    public function delete(User $user = null, UserRepository $userRepository)
    {
        if ($user === null) {
            throw $this->createNotFoundException('Utilisateur non trouvée');
        }
        $userRepository->remove($user, true);
        return $this->redirectToRoute('main_home', [], Response::HTTP_SEE_OTHER);
    }
}

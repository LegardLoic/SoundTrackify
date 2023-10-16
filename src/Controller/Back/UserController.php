<?php

namespace App\Controller\Back;

use App\Entity\User;
use App\Form\Back\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;

/**
 * @Route("/admin/users", name="app_back_")
 * @IsGranted("ROLE_ADMIN")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $users = $userRepository->findAll();

        $users = $paginator->paginate(
            $users,
            $request->query->getInt('page',1),
            10
        );

        return $this->render('back/user/index.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/new", name="user_new", methods={"GET", "POST"})
     */
    public function new(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // hash the password with the appropriate "service"
            // the plaintext password is in the $user object updated by the form
            $plainTextPassword = $user->getPassword();

            $hashedPassword = $passwordHasher->hashPassword($user, $plainTextPassword);

            // overwrite the plaintext password with the hashed password, in the $user object
            $user->setPassword($hashedPassword);
            $slugger = new AsciiSlugger();
            $createSlug = $slugger->slug($user->getGamerTag());
            $createSlug = strtolower($createSlug);
            $user->setSlug($createSlug);
            
            // save in BDD
            $userRepository->add($user, true);
            $this->addFlash('success', 'Travail terminé !');
            return $this->redirectToRoute('app_back_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     */
    public function show(User $user = null): Response
    {
        if ($user === null) {
            throw $this->createNotFoundException('utilisateur non trouvé.');
        }
        return $this->render('back/user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, User $user = null, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $manager): Response
    {
        if ($user === null) {
            throw $this->createNotFoundException('utilisateur non trouvé.');
        }
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($form->get('password')->getData()) {

                $plainTextPassword = $form->get('password')->getData(); // $plainTextPassword = $_POST['password'];

                $hashedPassword = $passwordHasher->hashPassword($user, $plainTextPassword); // password_hash() de PHP

                $user->setPassword($hashedPassword);
            }

            if ($form->get('gamerTag')->getData()) {
                $slugger = new AsciiSlugger();
                $createSlug = $slugger->slug($user->getGamerTag());
                $createSlug = strtolower($createSlug);
                $user->setSlug($createSlug);
            }
            
            $userRepository->add($user, true);
            $this->addFlash('message', 'Profil mis à jour');
            return $this->redirectToRoute('app_back_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"POST"})
     */
    public function delete(Request $request, User $user = null, UserRepository $userRepository): Response
    {
        if ($user === null) {
            throw $this->createNotFoundException('utilisateur non trouvé.');
        }
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
        }
        $this->addFlash('success', 'cible détruite !');
        return $this->redirectToRoute('app_back_user_index', [], Response::HTTP_SEE_OTHER);
    }
}

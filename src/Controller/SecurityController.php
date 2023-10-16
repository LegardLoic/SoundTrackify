<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Form\Front\RegistrationType;
use Symfony\Component\Form\FormError;
use App\Service\SuccessMessageGenerator;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\Front\PasswordForgottenType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityController extends AbstractController
{
    /**
     *  @Route("/connexion", name="app_login", methods={"GET", "POST"})
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/deconnexion", name="app_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/inscription", name="app_register", methods={"GET", "POST"})
     */
    public function register(Request $request,UserRepository $userRepository, EntityManagerInterface $manager, UserPasswordHasherInterface $passwordHasher, SuccessMessageGenerator $successMessageGenerator)
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Vérifier si l'e-mail est unique en BDD ici avant d'enregistrer l'utilisateur
            $existingUser = $userRepository->findOneBy(['email' => $user->getEmail()]);
            if ($existingUser) {
                $form->get('email')->addError(new FormError('Cet e-mail est déjà utilisé.'));
                return $this->render('security/register.html.twig', [
                'form' => $form->createView(),
                ]);
            }
            
            $plainTextPassword = $user->getPassword();
            $hashedPassword = $passwordHasher->hashPassword($user, $plainTextPassword);
            $user->setPassword($hashedPassword);

            $slugger = new AsciiSlugger();
            $createSlug = $slugger->slug($user->getGamerTag());
            $createSlug = strtolower($createSlug);
            $user->setSlug($createSlug);

            $user->setRoles(['ROLE_USER']);

            $userRepository->add($user, true);

            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', $successMessageGenerator->getRandomSuccessMessage());

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/register.html.twig', [
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/modification-mot-de-passe", name="app_password_change", methods={"GET", "POST"})
     */
    public function passwordForgotten(Request $request,UserRepository $userRepository, EntityManagerInterface $manager, UserPasswordHasherInterface $passwordHasher) 
    {
        $form = $this->createForm(PasswordForgottenType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //On va chercher l'utilisateur par son email
            $user = $userRepository->findOneByEmail($form->get('email')->getData());

            // On vérifie si on a un utilisateur
            if($user) {
                $plainTextPassword = $form->get('password')->getData();
                $hashedPassword = $passwordHasher->hashPassword($user, $plainTextPassword);
                $user->setPassword($hashedPassword);

                $userRepository->add($user, true);
                $manager->persist($user);
                $manager->flush();

                $this->addFlash('success', 'Mot de passe mis à jour');
                return $this->redirectToRoute('app_login');
            }

            // $user est null
            $this->addFlash('danger', 'Impossible de trouver votre compte SoundTrackify');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/password_forgotten.html.twig', [
            'form' => $form->createView(),
        ]);
   
    }

    /**
     * @Route("/cookies/gestion", name="app_cookies_gestion", methods={"POST"})
     */
    public function cookies(): Response
    {
        return $this->redirectToRoute('main_home');
    }
}

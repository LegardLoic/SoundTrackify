<?php

namespace App\Controller\Front;

use App\Repository\AlbumRepository;
use App\Repository\VideogameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/"), name"app_front_")
 */
class MainController extends AbstractController
{
    /**
     * @Route("/", name="main_home", methods={"GET", "POST"})
     */
    public function home(VideogameRepository $videogameRepository, AlbumRepository $albumRepository): Response
    {

        $videogames = $videogameRepository->findLastFourForHomepage();

        $randomAlbums = $albumRepository->findFourRandomAlbum();

        $form = $this->createFormBuilder(null, [
            'action' => $this->generateUrl('main_search'),
            'method' => 'POST',
        ])
        ->add('query', TextType::class)
        ->add('search', SubmitType::class, ['label' => 'Rechercher'])
        ->getForm();

        return $this->render('front/main/home.html.twig', [
            'videogames' => $videogames,
            'randomAlbums' => $randomAlbums,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/search", name="main_search", methods={"POST"})
     */
    public function search(Request $request, AlbumRepository $albumRepository)
    {
        $form = $this->createFormBuilder(null)
        ->add('query', TextType::class)
        ->add('search', SubmitType::class, ['label' => 'Rechercher'])
        ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $query = $data['query'];

            // search logic here
            $albums = $albumRepository->search($query);

            return $this->render('front/main/search.html.twig', [
                'albums' => $albums,
                'query' => $query
            ]);
        }

        return $this->render('front/main/search.html.twig');  // vue par défaut si le formulaire n'est pas soumis ou invalide
    }

    /**
     * @Route("/contacts", name="main_contact", methods={"GET"})
     */
    public function contact(): Response
    {
        return $this->render('front/main/contact.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    /**
     * @Route("/mentions-legales", name="main_legalNotice", methods={"GET"})
     */
    public function legalNotice(): Response
    {
        return $this->render('front/main/legals.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    /**
     * @Route("/donnees-personnelles", name="main_personalData", methods={"GET"})
     */
    public function personalData(): Response
    {
        return $this->render('front/main/personal-data.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }
}

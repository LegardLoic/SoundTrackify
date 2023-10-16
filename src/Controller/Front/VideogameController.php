<?php

namespace App\Controller\Front;

use App\Repository\PlatformRepository;
use App\Repository\VideogameRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/jeux-videos"), name"app_front_")
 */
class VideogameController extends AbstractController
{
    /**
     * @Route("/", name="videogame_list", methods={"GET"})
     */
    public function list(PlatformRepository $platformRepository, VideogameRepository $videogameRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $platforms = $platformRepository->findAll();
        $videogames = $videogameRepository->findAllOrderByName();

        $form = $this->createFormBuilder(null, [
            'action' => $this->generateUrl('videogame_search'),
            'method' => 'POST',
        ])
        ->add('query', TextType::class)
        ->add('search', SubmitType::class, ['label' => 'Rechercher'])
        ->getForm();

        $videogames = $paginator->paginate(
            $videogames,
            $request->query->getInt('page',1),
            5
        );

        return $this->render('front/videogame/list.html.twig', [
            'platforms' => $platforms,
            'videogames' => $videogames,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}", name="videogame_show", methods={"GET"})
     */
    public function show($slug, VideogameRepository $videogameRepository): Response
    {
        if($this->isGranted('ROLE_USER')) {
            $videogame = $videogameRepository->findBySlug($slug);
            if ($videogame === null) {
            throw $this->createNotFoundException('Jeu vidéo non trouvé');
            }

            $user = $this->getUser();

            $playlists = $user->getPlaylists();

            return $this->render('front/videogame/show.html.twig', [
                'videogame' => $videogame,
                'playlists' => $playlists,
            ]);
        }

        $videogame = $videogameRepository->findBySlug($slug);
        if ($videogame === null) {
            throw $this->createNotFoundException('Jeu vidéo non trouvé');
        }

        return $this->render('front/videogame/show.html.twig', [
            'videogame' => $videogame,
        ]);
    }

    /**
     * @Route("/recherche/jeux", name="videogame_search", methods={"POST"})
     */
    public function search(Request $request, VideogameRepository $videogameRepository)
    {
        $form = $this->createFormBuilder(null)
        ->add('query', TextType::class)
        ->add('search', SubmitType::class, ['label' => 'Rechercher'])
        ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $query = $data['query'];

            // Logique de recherche ici
            $videogames = $videogameRepository->search($query);

            return $this->render('front/videogame/search.html.twig', [
                'videogames' => $videogames,
                'query' => $query
            ]);
        }

        return $this->render('front/videogame/search.html.twig');
    }
}

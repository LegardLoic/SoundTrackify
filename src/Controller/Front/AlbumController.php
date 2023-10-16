<?php

namespace App\Controller\Front;

use App\Repository\AlbumRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/albums"), name"app_front_")
 */
class AlbumController extends AbstractController
{
    /**
     * @Route("/", name="album_list", methods={"GET"})
     */
    public function list(AlbumRepository $albumRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $albums = $albumRepository->findAllOrderByName();

        $albums = $paginator->paginate(
            $albums,
            $request->query->getInt('page',1),
            5
        );

        return $this->render('front/album/list.html.twig', [
            'albums' => $albums,
        ]);
    }

    /**
     * @Route("/{slug}", name="album_show", methods={"GET"})
     */
    public function show($slug, AlbumRepository $albumRepository, Request $request): Response
    {

        if($this->isGranted('ROLE_USER')) {

            $session = $request->getSession();
            $historique = $session->get('historique', []);
            $historique[] = $request->getRequestUri();
            $session->set('historique', $historique);
            
            $album = $albumRepository->findBySlug($slug);
            if ($album === null) {
                throw $this->createNotFoundException('Album non trouvé');
            }

            $user = $this->getUser();

            $playlists = $user->getPlaylists();


            return $this->render('front/album/show.html.twig', [
                'album' => $album,
                'playlists' => $playlists,
            ]);
        }

        $album = $albumRepository->findBySlug($slug);
        if ($album === null) {
            throw $this->createNotFoundException('Album non trouvé');
        }

        return $this->render('front/album/show.html.twig', [
            'album' => $album,
        ]);
    }
}

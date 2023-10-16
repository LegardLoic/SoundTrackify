<?php

namespace App\Controller\Back;

use App\Entity\Playlist;
use App\Form\Back\PlaylistType;
use App\Repository\MusicRepository;
use App\Repository\PlaylistRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/playlists", name="app_back_")
 * @IsGranted("ROLE_ADMIN")
 */
class PlaylistController extends AbstractController
{
    /**
     * @Route("/", name="playlist_index", methods={"GET"})
     */
    public function index(PlaylistRepository $playlistRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $playlists = $playlistRepository->findAll();

        $playlists = $paginator->paginate(
            $playlists,
            $request->query->getInt('page',1),
            10
        );

        return $this->render('back/playlist/index.html.twig', [
            'playlists' => $playlists,
        ]);
    }

    /**
     * @Route("/new", name="playlist_new", methods={"GET", "POST"})
     */
    public function new(Request $request, PlaylistRepository $playlistRepository): Response
    {
        $playlist = new Playlist();
        $form = $this->createForm(PlaylistType::class, $playlist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('name')->getData()) {
                $slugger = new AsciiSlugger();
                $createSlug = $slugger->slug($playlist->getName());
                $playlist->setSlug($createSlug);
            }
            $playlistRepository->add($playlist, true);
            $this->addFlash('success', 'Travail terminé !');
            return $this->redirectToRoute('app_back_playlist_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/playlist/new.html.twig', [
            'playlist' => $playlist,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="playlist_show", methods={"GET"})
     */
    public function show(Playlist $playlist = null): Response
    {
        if ($playlist === null) {
            throw $this->createNotFoundException('playlist non trouvé.');
        }
        return $this->render('back/playlist/show.html.twig', [
            'playlist' => $playlist,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="playlist_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Playlist $playlist = null, PlaylistRepository $playlistRepository): Response
    {
        if ($playlist === null) {
            throw $this->createNotFoundException('playlist non trouvé.');
        }
        
        $form = $this->createForm(PlaylistType::class, $playlist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $playlistRepository->add($playlist, true);
            $this->addFlash('success', 'Travail terminé !');
            return $this->redirectToRoute('app_back_playlist_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/playlist/edit.html.twig', [
            'playlist' => $playlist,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="playlist_delete", methods={"POST"})
     */
    public function delete(Request $request, Playlist $playlist = null, PlaylistRepository $playlistRepository): Response
    {
        if ($playlist === null) {
            throw $this->createNotFoundException('playlist non trouvé.');
        }
        if ($this->isCsrfTokenValid('delete'.$playlist->getId(), $request->request->get('_token'))) {
            $playlistRepository->remove($playlist, true);
        }
        $this->addFlash('success', 'cible détruite !');
        return $this->redirectToRoute('app_back_playlist_index', [], Response::HTTP_SEE_OTHER);
    }

/**
 * @Route("/api/musics", name="api_musics", methods={"GET"})
 */
public function getFilteredMusics(Request $request, MusicRepository $musicRepository)
{
    $term = $request->query->get('term');
    $musics = $musicRepository->findFilteredMusics($term);

    $responseArray = [];
    foreach ($musics as $music) {
        $responseArray[] = [
            'id' => $music->getId(),
            'text' => $music->getName(),
        ];
    }
    return $this->json($responseArray);
}

}

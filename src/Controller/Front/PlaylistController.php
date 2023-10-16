<?php

namespace App\Controller\Front;

use App\Entity\Playlist;
use App\Form\Front\AddToPlaylistType;
use App\Form\Front\CreatePlaylistType;
use App\Repository\AlbumRepository;
use App\Repository\MusicRepository;
use App\Repository\PlaylistRepository;
use App\Service\SuccessMessageGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;

/**
 * @Route("/playlists"), name"app_front_")
 * @IsGranted("ROLE_USER")
 */
class PlaylistController extends AbstractController
{
    /**
     * @Route("/", name="playlist_list", methods={"GET"})
     */
    public function list(PaginatorInterface $paginator, Request $request): Response
    {
        $session = $request->getSession();
        $historique = $session->get('historique', []);
        $historique[] = $request->getRequestUri();
        $session->set('historique', $historique);
        
        $user = $this->getUser();

        $playlists = $user->getPlaylists();

        $playlists = $paginator->paginate(
            $playlists,
            $request->query->getInt('page',1),
            3
        );

        return $this->render('front/playlist/list.html.twig', [
            'playlists' => $playlists,
        ]);
    }

    /**
     * @Route("/nouvelle", name="playlist_createPlaylist", methods={"GET", "POST"})
     */
    public function createPlaylist(Request $request, PlaylistRepository $playlistRepository, EntityManagerInterface $manager, SuccessMessageGenerator $successMessageGenerator): Response
    {
        $user = $this->getUser();
        $playlist = new Playlist();
        $form = $this->createForm(CreatePlaylistType::class, $playlist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($form->get('name')->getData()) {
                $slugger = new AsciiSlugger();
                $createSlug = $slugger->slug($playlist->getName());
                $createSlug = strtolower($createSlug);
                $playlist->setSlug($createSlug);
            }

            $playlist->setUser($user);

            $playlistRepository->add($playlist, true);

            $manager->persist($playlist);
            $manager->flush();

            $this->addFlash('success', $successMessageGenerator->getRandomSuccessMessage());
            $session = $request->getSession();
            

            // Récupérer l'historique depuis la session
            $historique = $session->get('historique', []);
            

            // Obtenir l'URL deux pages en arrière
            $temp = array_slice($historique, -1, 1);
            $urlDeuxPagesArriere = end($temp);

            if ($urlDeuxPagesArriere) {
                return new RedirectResponse($urlDeuxPagesArriere);
            }


            return $this->redirectToRoute('playlist_list');
        }

        return $this->render('front/playlist/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/modifier", name="playlist_edit", methods={"GET", "POST"})
     */
    public function edit($id, Request $request, PlaylistRepository $playlistRepository, EntityManagerInterface $manager): Response
    {
        $playlist = $playlistRepository->find($id);

        $form = $this->createForm(CreatePlaylistType::class, $playlist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            if ($form->get('name')->getData()) {
                $slugger = new AsciiSlugger();
                $createSlug = $slugger->slug($playlist->getName());
                $createSlug = strtolower($createSlug);
                $playlist->setSlug($createSlug);
            }

            $playlistRepository->add($playlist, true);
            $manager->persist($playlist);
            $manager->flush();
            $this->addFlash('success', 'Nous faisons tous des choix, mais en réalité, ce sont ces choix qui nous font. "Bioshock"');
            return $this->redirectToRoute('playlist_show',['id' => $playlist->getId()]);
        }

        return $this->render('front/playlist/edit.html.twig', [
            'form' => $form->createView(),
            'playlist' => $playlist,
        ]);
    }

    /**
     * @Route("/{id}/supprimer", name="playlist_delete", methods={"GET", "POST"})
     */
    public function delete(Playlist $playlist, PlaylistRepository $playlistRepository): Response
    {
        $playlistRepository->remove($playlist, true);
        $this->addFlash('success', 'You Win !');
        return $this->redirectToRoute('playlist_list', [], Response::HTTP_SEE_OTHER);

    }

    /**
     * @Route("/{id}", name="playlist_show", methods={"GET"})
     */
    public function show($id, PlaylistRepository $playlistRepository): Response
    {
        $playlist = $playlistRepository->find($id);
        if ($playlist === null) {
            throw $this->createNotFoundException('Playlist non trouvée');
        }

        $musicToPlaylists = $playlist->getMusics();

        return $this->render('front/playlist/show.html.twig', [
            'musicToPlaylists' => $musicToPlaylists,
            'playlist' => $playlist,
        ]);
    }

    /**
     * @Route("/ajouter/{albumSlug}/{id}", name="playlist_addTrackToPlaylist", methods={"GET", "POST"})
     */
    public function addTrackToPlaylist($id, $albumSlug, MusicRepository $musicRepository, AlbumRepository $albumRepository, Request $request, EntityManagerInterface $manager, SuccessMessageGenerator $successMessageGenerator): Response
    {
        $session = $request->getSession();
        $historique = $session->get('historique', []);
        $historique[] = $request->getRequestUri();
        $session->set('historique', $historique);

        $music = $musicRepository->find($id);

        $album = $albumRepository->findBySlug($albumSlug);

        $user = $this->getUser();

        $form = $this->createForm(AddToPlaylistType::class, $music, ['user' => $user]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $selectedPlaylists = $form->get('playlists')->getData();

                // Associez la musique aux playlists sélectionnées
                foreach ($selectedPlaylists as $playlist) {
                    $playlist->addMusic($music);
                }

            $manager->persist($playlist);
            $manager->flush();
            $this->addFlash('success', $successMessageGenerator->getRandomSuccessMessage());
            return $this->redirectToRoute('album_show', ['slug' => $albumSlug]);
        }

        return $this->render('front/playlist/addtrack.html.twig', [
            'form' => $form->createView(),
            'music' => $music,
            'album' => $album,
        ]);
    }

    /**
     * @Route("/supprimer/{playlistId}/{musicId}", name="playlist_removeTrackFromPlaylist", methods={"GET", "POST"})
     */
    public function removeTrackFromPlaylist($musicId, $playlistId, PlaylistRepository $playlistRepository, MusicRepository $musicRepository, EntityManagerInterface $manager): Response
    {
        $music = $musicRepository->find($musicId);
        
        $playlist = $playlistRepository->find($playlistId);

        $playlist->removeMusic($music);

        // Sauvegarder les modifications
        $manager->persist($playlist);
        $manager->flush();

        return $this->redirectToRoute('playlist_show',['id' => $playlist->getId()], Response::HTTP_SEE_OTHER);
    }

}

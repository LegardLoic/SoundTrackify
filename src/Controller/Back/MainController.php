<?php

namespace App\Controller\Back;

use App\Repository\AlbumRepository;
use App\Repository\MusicRepository;
use App\Repository\PlatformRepository;
use App\Repository\PlaylistRepository;
use App\Repository\UserRepository;
use App\Repository\VideogameRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("/admin", name="app_back_")
 * @IsGranted("ROLE_ADMIN")
 */
class MainController extends AbstractController
{
    /**
     * @Route("/", name="main", methods={"GET"})
     */
    public function home(VideogameRepository $videogameRepository, AlbumRepository $albumRepository, MusicRepository $musicRepository, PlatformRepository $platformRepository, UserRepository $userRepository, PlaylistRepository $playlistRepository): Response
    {
        $albums = $albumRepository->findAll();
        $musics = $musicRepository->findAll();
        $platforms = $platformRepository->findAll();
        $playlists = $playlistRepository->findAll();
        $users = $userRepository->findAll();
        $videogames = $videogameRepository->findAll();

        $album = $albumRepository->findOneForDashboard();
        $music = $musicRepository->findOneForDashboard();
        $platform = $platformRepository->findOneForDashboard();
        $playlist = $playlistRepository->findOneForDashboard();
        $user = $userRepository->findOneForDashboard();
        $videogame = $videogameRepository->findOneForDashboard();
        

        return $this->render('back/main/home.html.twig', [
            'albums' => $albums,
            'album' => $album,
            'musics' => $musics,
            'music' => $music,
            'platforms' => $platforms,
            'platform' => $platform,
            'playlists' => $playlists,
            'playlist' => $playlist,
            'users' => $users,
            'user' => $user,
            'videogames' => $videogames,
            'videogame' => $videogame,
        ]);
    }
}

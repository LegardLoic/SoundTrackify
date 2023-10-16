<?php

namespace App\Controller\Back;

use App\Entity\Music;
use App\Form\Back\MusicType;
use App\Repository\MusicRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/musics", name="app_back_")
 * @IsGranted("ROLE_ADMIN")
 */
class MusicController extends AbstractController
{
    /**
     * @Route("/", name="music_index", methods={"GET"})
     */
    public function index(MusicRepository $musicRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $musics = $musicRepository->findAll();

        $musics = $paginator->paginate(
            $musics,
            $request->query->getInt('page',1),
            50
        );

        return $this->render('back/music/index.html.twig', [
            'musics' => $musics,
        ]);
    }

    /**
     * @Route("/new", name="music_new", methods={"GET", "POST"})
     */
    public function new(Request $request, MusicRepository $musicRepository): Response
    {
        $music = new Music();
        $form = $this->createForm(MusicType::class, $music);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $musicRepository->add($music, true);
            $this->addFlash('success', 'Travail terminé !');
            return $this->redirectToRoute('app_back_music_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/music/new.html.twig', [
            'music' => $music,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="music_show", methods={"GET"})
     */
    public function show(Music $music = null): Response
    {
        // 404 ?
        if ($music === null) {
            throw $this->createNotFoundException('musique non trouvé.');
        }
        return $this->render('back/music/show.html.twig', [
            'music' => $music,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="music_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Music $music = null, MusicRepository $musicRepository): Response
    {
        if ($music === null) {
            throw $this->createNotFoundException('musique non trouvé.');
        }
        $form = $this->createForm(MusicType::class, $music);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $musicRepository->add($music, true);
            $this->addFlash('success', 'Travail terminé !');
            return $this->redirectToRoute('app_back_music_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/music/edit.html.twig', [
            'music' => $music,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="music_delete", methods={"POST"})
     */
    public function delete(Request $request, Music $music = null, MusicRepository $musicRepository): Response
    {
        if ($music === null) {
            throw $this->createNotFoundException('musique non trouvé.');
        }
        if ($this->isCsrfTokenValid('delete'.$music->getId(), $request->request->get('_token'))) {
            $musicRepository->remove($music, true);
        }
        $this->addFlash('success', 'cible détruite !');
        return $this->redirectToRoute('app_back_music_index', [], Response::HTTP_SEE_OTHER);
    }
}

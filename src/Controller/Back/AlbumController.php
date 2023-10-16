<?php

namespace App\Controller\Back;

use App\Entity\Album;
use App\Form\Back\AlbumType;
use App\Repository\AlbumRepository;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;

/**
 * @Route("/admin/albums", name="app_back_")
 * @IsGranted("ROLE_ADMIN")
 */
class AlbumController extends AbstractController
{
    /**
     * @Route("/", name="album_index", methods={"GET"})
     */
    public function index(AlbumRepository $albumRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $albums = $albumRepository->findAll();

        $albums = $paginator->paginate(
            $albums,
            $request->query->getInt('page',1),
            10
        );

        return $this->render('back/album/index.html.twig', [
            'albums' => $albums,
        ]);
    }

    /**
     * @Route("/new", name="album_new", methods={"GET", "POST"})
     */
    public function new(Request $request, AlbumRepository $albumRepository): Response
    {
        $album = new Album();
        $form = $this->createForm(AlbumType::class, $album);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('name')->getData()) {
                $slugger = new AsciiSlugger();
                $createSlug = $slugger->slug($album->getName());
                $album->setSlug($createSlug);
            }
            $albumRepository->add($album, true);
            $this->addFlash('success', 'Travail terminé !');
            return $this->redirectToRoute('app_back_album_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/album/new.html.twig', [
            'album' => $album,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="album_show", methods={"GET"})
     */
    public function show(Album $album = null): Response
    {
        // 404 ?
        if ($album === null) {
            throw $this->createNotFoundException('album non trouvé.');
        }
        return $this->render('back/album/show.html.twig', [
            'album' => $album,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="album_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Album $album = null, AlbumRepository $albumRepository): Response
    {
        // 404 ?
        if ($album === null) {
            throw $this->createNotFoundException('album non trouvé.');
        }

        $form = $this->createForm(AlbumType::class, $album);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $albumRepository->add($album, true);
            $this->addFlash('success', 'Travail terminé !');
            return $this->redirectToRoute('app_back_album_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/album/edit.html.twig', [
            'album' => $album,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="album_delete", methods={"POST"})
     */
    public function delete(Request $request, Album $album, AlbumRepository $albumRepository): Response
    {
        // 404 ?
        if ($album === null) {
            throw $this->createNotFoundException('album non trouvé.');
        }
        
        if ($this->isCsrfTokenValid('delete'.$album->getId(), $request->request->get('_token'))) {
            $albumRepository->remove($album, true);
        }
        $this->addFlash('success', 'cible détruite !');

        return $this->redirectToRoute('app_back_album_index', [], Response::HTTP_SEE_OTHER);
    }
}

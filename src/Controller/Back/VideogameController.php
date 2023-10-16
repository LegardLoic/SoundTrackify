<?php

namespace App\Controller\Back;

use App\Entity\Videogame;
use App\Form\Back\VideogameType;
use App\Repository\VideogameRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/videogames", name="app_back_")
 * @IsGranted("ROLE_ADMIN")
 */
class VideogameController extends AbstractController
{
    /**
     * @Route("/", name="videogame_index", methods={"GET"})
     */
    public function index(VideogameRepository $videogameRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $videogames = $videogameRepository->findAll();

        $videogames = $paginator->paginate(
            $videogames,
            $request->query->getInt('page',1),
            10
        );

        return $this->render('back/videogame/index.html.twig', [
            'videogames' => $videogames,
        ]);
    }

    /**
     * @Route("/new", name="videogame_new", methods={"GET", "POST"})
     */
    public function new(Request $request, VideogameRepository $videogameRepository): Response
    {
        $videogame = new Videogame();
        $form = $this->createForm(VideogameType::class, $videogame);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('name')->getData()) {
                $slugger = new AsciiSlugger();
                $createSlug = $slugger->slug($videogame->getName());
                $videogame->setSlug($createSlug);
            }
            $videogameRepository->add($videogame, true);
            $this->addFlash('success', 'Travail terminé !');
            return $this->redirectToRoute('app_back_videogame_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/videogame/new.html.twig', [
            'videogame' => $videogame,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="videogame_show", methods={"GET"})
     */
    public function show(Videogame $videogame = null): Response
    {
        if ($videogame === null) {
            throw $this->createNotFoundException('jeux-video non trouvé.');
        }
        return $this->render('back/videogame/show.html.twig', [
            'videogame' => $videogame,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="videogame_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Videogame $videogame = null, VideogameRepository $videogameRepository): Response
    {
        if ($videogame === null) {
            throw $this->createNotFoundException('jeux-video non trouvé.');
        }
        $form = $this->createForm(VideogameType::class, $videogame);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $videogameRepository->add($videogame, true);
            $this->addFlash('success', 'Travail terminé !');
            return $this->redirectToRoute('app_back_videogame_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/videogame/edit.html.twig', [
            'videogame' => $videogame,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="videogame_delete", methods={"POST"})
     */
    public function delete(Request $request, Videogame $videogame = null, VideogameRepository $videogameRepository): Response
    {
        if ($videogame === null) {
            throw $this->createNotFoundException('jeux-video non trouvé.');
        }
        if ($this->isCsrfTokenValid('delete'.$videogame->getId(), $request->request->get('_token'))) {
            $videogameRepository->remove($videogame, true);
        }
        $this->addFlash('success', 'cible détruite !');
        return $this->redirectToRoute('app_back_videogame_index', [], Response::HTTP_SEE_OTHER);
    }
}

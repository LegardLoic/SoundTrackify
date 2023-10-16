<?php

namespace App\Controller\Back;

use App\Entity\Platform;
use App\Form\Back\PlatformType;
use App\Repository\PlatformRepository;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/platforms", name="app_back_")
 * @IsGranted("ROLE_ADMIN")
 */
class PlatformController extends AbstractController
{
    /**
     * @Route("/", name="platform_index", methods={"GET"})
     */
    public function index(PlatformRepository $platformRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $platforms = $platformRepository->findAll();

        $platforms = $paginator->paginate(
            $platforms,
            $request->query->getInt('page',1),
            13
        );

        return $this->render('back/platform/index.html.twig', [
            'platforms' => $platforms,
        ]);
    }

    /**
     * @Route("/new", name="platform_new", methods={"GET", "POST"})
     */
    public function new(Request $request, PlatformRepository $platformRepository): Response
    {
        $platform = new Platform();
        $form = $this->createForm(PlatformType::class, $platform);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $platformRepository->add($platform, true);
            $this->addFlash('success', 'Travail terminé !');
            return $this->redirectToRoute('app_back_platform_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/platform/new.html.twig', [
            'platform' => $platform,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="platform_show", methods={"GET"})
     */
    public function show(Platform $platform = null): Response
    {
        if ($platform === null) {
            throw $this->createNotFoundException('plateforme non trouvé.');
        }
        return $this->render('back/platform/show.html.twig', [
            'platform' => $platform,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="platform_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Platform $platform = null, PlatformRepository $platformRepository): Response
    {
        if ($platform === null) {
            throw $this->createNotFoundException('plateforme non trouvé.');
        }
        
        $form = $this->createForm(PlatformType::class, $platform);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $platformRepository->add($platform, true);
            $this->addFlash('success', 'Travail terminé !');
            return $this->redirectToRoute('app_back_platform_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/platform/edit.html.twig', [
            'platform' => $platform,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="platform_delete", methods={"POST"})
     */
    public function delete(Request $request, Platform $platform = null, PlatformRepository $platformRepository): Response
    {
        if ($platform === null) {
            throw $this->createNotFoundException('plateforme non trouvé.');
        }
        if ($this->isCsrfTokenValid('delete'.$platform->getId(), $request->request->get('_token'))) {
            $platformRepository->remove($platform, true);
        }
        $this->addFlash('success', 'cible détruite !');
        return $this->redirectToRoute('app_back_platform_index', [], Response::HTTP_SEE_OTHER);
    }
}

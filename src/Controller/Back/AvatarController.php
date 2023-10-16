<?php

namespace App\Controller\Back;

use App\Entity\Avatar;
use App\Form\Back\AvatarType;
use App\Repository\AvatarRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/avatars", name="app_back_")
 * @IsGranted("ROLE_ADMIN")
 */
class AvatarController extends AbstractController
{
    /**
     * @Route("/", name="avatar_index", methods={"GET"})
     */
    public function index(AvatarRepository $avatarRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $avatars = $avatarRepository->findAll();

        $avatars = $paginator->paginate(
            $avatars,
            $request->query->getInt('page',1),
            10
        );

        return $this->render('back/avatar/index.html.twig', [
            'avatars' => $avatars,
        ]);
    }

    /**
     * @Route("/new", name="avatar_new", methods={"GET", "POST"})
     */
    public function new(Request $request, AvatarRepository $avatarRepository): Response
    {
        $avatar = new Avatar();
        $form = $this->createForm(AvatarType::class, $avatar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $avatarRepository->add($avatar, true);
            $this->addFlash('success', 'Travail terminé !');
            return $this->redirectToRoute('app_back_avatar_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/avatar/new.html.twig', [
            'avatar' => $avatar,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="avatar_show", methods={"GET"})
     */
    public function show(Avatar $avatar = null): Response
    {
        // 404 ?
        if ($avatar === null) {
            throw $this->createNotFoundException('avatar non trouvé.');
        }
        return $this->render('back/avatar/show.html.twig', [
            'avatar' => $avatar,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="avatar_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Avatar $avatar = null, AvatarRepository $avatarRepository): Response
    {
        // 404 ?
        if ($avatar === null) {
            throw $this->createNotFoundException('avatar non trouvé.');
        }
        $form = $this->createForm(AvatarType::class, $avatar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $avatarRepository->add($avatar, true);
            $this->addFlash('success', 'Travail terminé !');
            return $this->redirectToRoute('app_back_avatar_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/avatar/edit.html.twig', [
            'avatar' => $avatar,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="avatar_delete", methods={"POST"})
     */
    public function delete(Request $request, Avatar $avatar = null, AvatarRepository $avatarRepository): Response
    {
        // 404 ?
        if ($avatar === null) {
            throw $this->createNotFoundException('avatar non trouvé.');
        }
        if ($this->isCsrfTokenValid('delete'.$avatar->getId(), $request->request->get('_token'))) {
            $avatarRepository->remove($avatar, true);
        }
        $this->addFlash('success', 'cible détruite !');
        return $this->redirectToRoute('app_back_avatar_index', [], Response::HTTP_SEE_OTHER);
    }
}

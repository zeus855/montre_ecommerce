<?php

namespace App\Controller\Admin;

use App\Entity\CGV;
use App\Form\CGVType;
use App\Repository\CGVRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/cgv')]
class CGVController extends AbstractController
{
    #[Route('/', name: 'app_admin_cgv_index', methods: ['GET'])]
    public function index(CGVRepository $cGVRepository): Response
    {
        return $this->render('admin/cgv/index.html.twig', [
            'cgvs' => $cGVRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_cgv_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $cGV = new CGV();
        $form = $this->createForm(CGVType::class, $cGV);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($cGV);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_cgv_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/cgv/new.html.twig', [
            'cgv' => $cGV,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_cgv_show', methods: ['GET'])]
    public function show(CGV $cGV): Response
    {
        return $this->render('admin/cgv/show.html.twig', [
            'cgv' => $cGV,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_cgv_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CGV $cGV, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CGVType::class, $cGV);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_cgv_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/cgv/edit.html.twig', [
            'cgv' => $cGV,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_cgv_delete', methods: ['POST'])]
    public function delete(Request $request, CGV $cGV, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cGV->getId(), $request->request->get('_token'))) {
            $entityManager->remove($cGV);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_cgv_index', [], Response::HTTP_SEE_OTHER);
    }
}

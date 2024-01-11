<?php

namespace App\Controller\Admin;

use App\Entity\HomePage;
use App\Form\HomePageType;
use App\Repository\HomePageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/home/page')]
class HomePageController extends AbstractController
{
    #[Route('/', name: 'app_admin_home_page_index', methods: ['GET'])]
    public function index(HomePageRepository $homePageRepository): Response
    {
        return $this->render('admin/home_page/index.html.twig', [
            'home_pages' => $homePageRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_home_page_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $homePage = new HomePage();
        $form = $this->createForm(HomePageType::class, $homePage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($homePage);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_home_page_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/home_page/new.html.twig', [
            'home_page' => $homePage,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_home_page_show', methods: ['GET'])]
    public function show(HomePage $homePage): Response
    {
        return $this->render('admin/home_page/show.html.twig', [
            'home_page' => $homePage,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_home_page_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, HomePage $homePage, EntityManagerInterface $entityManager): Response
    {
        
        $form = $this->createForm(HomePageType::class, $homePage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->flush();

            return $this->redirectToRoute('app_admin_home_page_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/home_page/edit.html.twig', [
            'home_page' => $homePage,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_home_page_delete', methods: ['POST'])]
    public function delete(Request $request, HomePage $homePage, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$homePage->getId(), $request->request->get('_token'))) {
            $entityManager->remove($homePage);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_home_page_index', [], Response::HTTP_SEE_OTHER);
    }
}

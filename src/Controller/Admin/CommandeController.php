<?php

namespace App\Controller\Admin;

use App\Entity\Commande;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/commande')]
class CommandeController extends AbstractController
{
    #[Route('/', name: 'app_admin_commande_index', methods: ['GET'])]
    public function index(CommandeRepository $commandeRepository): Response
    {
        return $this->render('admin/commande/index.html.twig', [
            'commandes' => $commandeRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_admin_commande_delete', methods: ['POST'])]
    public function delete(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {

        $commandeId = $commande->getId();

        if ($this->isCsrfTokenValid('delete'.$commande->getId(), $request->request->get('_token'))) {

            $montreCommandes = $commande->getMontreCommandes();
            
            foreach($montreCommandes as $montreCommande) {
                $entityManager->remove($montreCommande);
            }
            $entityManager->remove($commande);
            $entityManager->flush();
        }

        $this->addFlash('success', 
        // 'commande #' . $commande->getId() .' a bien été supprimé';
        sprintf('Commande #%d a bien été supprimé', $commandeId)
    );

        return $this->redirectToRoute('app_admin_commande_index', [], Response::HTTP_SEE_OTHER);
    }
}

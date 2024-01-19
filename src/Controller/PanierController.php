<?php

namespace App\Controller;

use App\Constante\AdresseTypeConstante;
use App\Constante\CommandeConstante;
use App\Entity\Adresse;
use App\Entity\Commande;
use App\Entity\Montre;
use App\Entity\MontreCommande;
use App\Form\AdresseType;
use App\Repository\MontreRepository;
use App\Repository\MontreCommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PanierController extends AbstractController
{
    #[Route('/panier', name: 'app_panier')]
    public function index(Request $request, MontreRepository $montreRepository): Response
    {
        // Récuperation des identifiants des montres dans le panier depuis la session
        $montresId = $request->getSession()->get('panier');

        // Si des montres sont presentes dans le panier, on les extrait du repository des montres
        if ($montresId) {
            $paniers = $montreRepository->findBy(['id' => array_keys($montresId)]);
        } else {
            // Si le panier est vide, on initialise la variable $paniers avec un tableau vide
            $paniers = [];
        }


        return $this->render('panier/index.html.twig', [
            'paniers' => $paniers,
            'quantites' => $montresId

        ]);
    }

    #[Route('/paiement/success', name: 'app_paiement_success_panier')]
    public function paiementSuccess(Request $request, EntityManagerInterface $entityManager): Response
    {
        // On récupère le panier depuis la session et les montres associées à l'aide de leurs id.
        $panier = $request->getSession()->get('panier');
        $montres = $entityManager->getRepository(Montre::class)->findBy(['id' => array_keys($panier)]);

        // On crée une nouvelle instance
        $commande = new Commande();
        // On attribue le statut "TERMINER" à cette commande.
        $commande->setStatut(CommandeConstante::TERMINER);
        // On associe la commande à l'utilisateur actuel
        $commande->setUser($this->getUser());
        // On persiste la commande en base de données
        $entityManager->persist($commande);

        // Initialisation de la variable
        $total = 0;

        // On parcourt les montres du panier
        foreach ($montres as $montre) {
            // Pour chaque montre, on récupère la quantité depuis le panier 
            $quantite = $panier[$montre->getId()];

            // calcule le total
            $total += ($quantite * $montre->getPrix());

            // On crée une nouvelle instance de la classe MontreCommande associée à la commande, avec les détails tels que la montre, la quantité, et l'utilisateur connecté.
            $montreCommande = new MontreCommande();            
            $montreCommande->setCommande($commande);
            $montreCommande->setMontre($montre);
            $montreCommande->setQuantite($quantite);
            $montreCommande->setUser($this->getUser());

            $entityManager->persist($montreCommande);
        }

        // On associe le total
        $commande->setTotal($total);
        
        // On récupère les adresses de livraison et de facturation depuis la session, puis les associe à la commande si elles existent
        $adresses = $request->getSession()->get('adresse');
        $livraison = $adresses['livraison'] ?? null;

        if ($livraison) {
            $adresse = $entityManager->getRepository(Adresse::class)->find($livraison);

            $commande->setLivraison($adresse);
        }

        $facturation = $adresses['facturation'] ?? null;

        if ($facturation) {
            $adresse = $entityManager->getRepository(Adresse::class)->find($facturation);

            $commande->setFacturation($adresse);
        }

        $entityManager->flush();

        // On supprime les informations du panier et des adresses de la session,
        $request->getSession()->remove('panier');
        $request->getSession()->remove('adresse');

        $this->addFlash('success', 'Félicitation votre paiement à bien été accepté');

        return $this->redirectToRoute('front');
    }

    #[Route('/paiement/failed', name: 'app_paiement_failed_panier')]
    public function paiementFailed(Request $request): Response
    {

        $this->addFlash('danger', 'Un problème est survenue lors de votre paiement, merci de reessayer plus tard');

        return $this->redirectToRoute('front');
    }


    #[Route('/panier/adresses', name: 'app_panier_adresse')]
    #[IsGranted('ROLE_USER')]
    public function adresses(Request $request, EntityManagerInterface $entityManager): Response
    {
        $adresses = $entityManager->getRepository(Adresse::class)->findBy(['user' => $this->getUser()]);

        $livraisons = [];
        $facturations = [];

        foreach ($adresses as $adresse) {
            if ($adresse->getLabel() === AdresseTypeConstante::LIVRAISON) {
                $livraisons[] = $adresse;
            } elseif ($adresse->getLabel() === AdresseTypeConstante::FACTURATION) {
                $facturations[] = $adresse;
            }
        }

        return $this->render('panier/adresses.html.twig', [
            'adresses' => [
                'livraisons' => $livraisons,
                'facturations' => $facturations
            ]
        ]);
    }

    // La route pour supprimer une adresse
    #[Route('/panier/adresses/{id}', name: 'app_edit_adresse')]
    #[IsGranted('ROLE_USER')]
    public function editAdresse(Request $request, Adresse $adresse, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AdresseType::class, $adresse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $adresse->setUser($this->getUser());

            $entityManager->flush();

            $this->addFlash('success', 'Votre adresse à bien été modifié');

            if ($request->query->get('urlRedirect')) {
                return $this->redirect($request->query->get('urlRedirect'));
            }

            return $this->redirectToRoute('app_panier_adresse');
        }

        /** @var User $user */
        $user = $this->getUser();

        return $this->render('panier/validate.html.twig', [
            'form' => $form->createView(),
            'adresses' => $user->getAdresses()
        ]);
    }

    // La route pour supprimer une adresse
    #[Route('/panier/delete_adresses/{id}', name: 'app_delete_adresse')]
    #[IsGranted('ROLE_USER')]
    public function deleteAdresse(Adresse $adresse, Request $request, EntityManagerInterface $entityManager): Response
    {
        $session = $request->getSession();

        $panier = $session->get('panier');
        $adresseid = $adresse->getId();
        if ($panier[$adresseid] ?? false) {

            unset($panier[$adresseid]);
        }

        $session->set('panier', $panier);
        $entityManager->remove($adresse);
        $entityManager->flush();

        $this->addFlash('success', 'Votre adresse à été suprimée');

        if ($request->query->get('urlRedirect')) {
            return $this->redirect($request->query->get('urlRedirect'));
        }

        return $this->redirectToRoute('app_panier_adresse');
    }


    // Route qui permet de gerer la validation d'une adresse 
    #[Route('/panier/validation', name: 'app_panier_validation')]
    #[IsGranted('ROLE_USER')]
    public function validation(Request $request, EntityManagerInterface $entityManager): Response
    {
        // On cree une nouvelle instance de l'entité Adresse et un formulaire associé AdresseType.Le formulaire sera utilisé pour saisir des infos de l'adresse
        $adresse = new Adresse;
        $form = $this->createForm(AdresseType::class, $adresse);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $adresse->setUser($this->getUser());

            $entityManager->persist($adresse);
            $entityManager->flush();

            $this->addFlash('success', 'Votre adresse à bien été rajoutée');

            if ($request->query->get('urlRedirect')) {
                return $this->redirect($request->query->get('urlRedirect'));
            }

            return $this->redirectToRoute('app_panier_adresse');
        }

        /** @var User $user */
        $user = $this->getUser();

        return $this->render('panier/validate.html.twig', [
            'form' => $form->createView(),
            'adresses' => $user->getAdresses()
        ]);
    }


    #[Route('/selection/adresse', name: 'app_selection_panier', methods: ['GET'])]
    public function selectionAdresse(Request $request): Response
    {
        $result = [
            'livraison' => $request->query->get('livraison'),
            'facturation' => $request->query->get('facturation'),
        ];

        $session = $request->getSession();

        $adresse = $session->get('adresse');
        $ads = [];
        if (!$adresse) {

            $ads = [
                'livraison' => null,
                'facturation' => null,
            ];

            foreach ($result as $type => $adresseId) {
                if ($type === 'livraison') {
                    $ads['livraison'] = $adresseId;
                }
                if ($type === 'facturation') {
                    $ads['facturation'] = $adresseId;
                }
            }
        } else {
            foreach ($result as $type => $adresseId) {
                if ($type === 'livraison') {
                    $ads['livraison'] = $adresseId;
                }
                if ($type === 'facturation') {
                    $ads['facturation'] = $adresseId;
                }
            }
        }
        $session->set('adresse', $ads);

        return $this->json(['url' => $this->generateUrl('app_paiement_panier')], 200);
    }

    #[Route('/paiement/info', name: 'app_paiement_panier')]
    #[IsGranted('ROLE_USER')]
    public function paiement(Request $request, MontreRepository $montreRepository): Response
    {
        // Récuperation de l'utilisateur  connecté
        $user = $this->getUser();

        // Config de la clé secrète Stripe
        \Stripe\Stripe::setApiKey($this->getParameter('app.stripe.secret_key'));

        // Recuperation des infos paniers en session
        $panier = $request->getSession()->get('panier');

        // Recuperation des infos des montre dans le panier
        $montres = $montreRepository->findBy(['id' => array_keys($panier)]);

        // Creation d'un tableau contenant les prix de chaque montre dans le panier
        $prices = [];
        foreach ($montres as $montre) {
            $prices[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => ($montre->getPrix() * 100),
                    'product_data' => [
                        'name' => $montre->getTitre(),
                    ],
                ],
                'quantity' => $panier[$montre->getId()]
            ];
        }

        // Cree une session de paiement avec Stripe en utilisant les infos du panier et infos de paiement
        $checkoutSession = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $prices,
            'mode' => 'payment',
            'customer_email' => $user->getEmail(),
            'success_url' => $this->generateUrl('app_recapitulatif_success', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->generateUrl('app_recapitulatif_failed', [], UrlGeneratorInterface::ABSOLUTE_URL)
        ]);

        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Location', $checkoutSession->url);
        $response->setStatusCode(303);

        return $response;
    }


    #[Route('/Ajout/{slug}', name: 'app_ajout_panier')]
    public function add(Montre $montre, Request $request): Response
    {
        // On recupere la session 
        $session = $request->getSession();

        // On récupère le contenu du panier à partir de la session utilisateur
        $panier = $session->get('panier');

        // Si le panier est vide
        if (!$panier) {
            // On cree un nouveau panier avec 1 comme quantite de montre afin de ne pas avoir de bugg
            $panier = [
                $montre->getId() => 1
            ];
        } else {
            // Au cas ou panier est non vide

            // On vérifie si la montre est déjà dans le panier
            if ($panier[$montre->getId()] ?? false) {
                // Incrémente la quantité dans le panier
                $panier[$montre->getId()] +=  1;
            } else {
                // On ajoute cette montre au panier avec une quantité de 1
                $panier[$montre->getId()] =  1;
            }
        }
        // on rempli la session avec le nouveau panier
        $session->set('panier', $panier);
        // on affiche un message flash
        $this->addFlash('success', 'La montre à bien été rajoutée dans le panier');
        return $this->redirectToRoute('app_panier');
    }

    // permet de supprimer une montre dans le panier
    #[Route('/update-delete/{id}/element/', name: 'app_update_delete_panier')]
    public function updateDelete(Montre $montre, Request $request): Response
    {
        $session = $request->getSession();

        $panier = $session->get('panier');
        $montreid = $montre->getId();
        if ($panier[$montreid] ?? false) {

            unset($panier[$montreid]);
        }

        $session->set('panier', $panier);

        $this->addFlash('success', 'Votre panier à été mis à jour');

        return $this->redirectToRoute('app_panier');
    }


    // permet d'afficher les elements de paniers
    #[Route('/update/{id}/element/{valeur}', name: 'app_update_panier')]
    public function update(Montre $montre, int $valeur, Request $request): Response
    {
        $session = $request->getSession();

        // On récupère le panier de la session.
        $panier = $session->get('panier');
        // On obtient l'id de la montre à partir de l'objet Montre.
        $montreid = $montre->getId();
        // On vérifie si la montre est déjà dans le panier. Si oui, on incrémente sa valeur de 1.
        if ($panier[$montreid] ?? false) {
            $panier[$montreid] = $valeur + 1;
        }

        // On met à jour le panier dans la session
        $session->set('panier', $panier);
        
        // On ajoute un message flash
        $this->addFlash('success', 'Votre panier à été mis à jour');

        return $this->redirectToRoute('app_panier');
    }


    // permet de decrementer la quantité de montre dans le panier
    #[Route('/update-moins/{id}/element/{valeur}', name: 'app_update_moins_panier')]
    public function updateMoins(Montre $montre, int $valeur, Request $request): Response
    {
        $session = $request->getSession();

        // On récupère le panier de la session.
        $panier = $session->get('panier');
        // On obtient l'id de la montre à partir de l'objet Montre.
        $montreid = $montre->getId();
        if ($panier[$montreid] ?? false) {
            if ($valeur == 1) {
                unset($panier[$montreid]);
            } else {
                $panier[$montreid] = $valeur - 1;
            }
        }

        // On met à jour le panier dans la session
        $session->set('panier', $panier);

        // On ajoute un message flash
        $this->addFlash('success', 'Votre panier à été mis à jour');

        return $this->redirectToRoute('app_panier');
    }
}

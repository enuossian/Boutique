<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Service\CartService;
use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class CommandeController extends AbstractController
{
    #[Route('/ajout', name: 'ajout_commande')]
    public function index(CartService $cs, ProduitRepository $repo): Response
    {
        $panier = $cs->getCartWithData('panier', []);
        // Si le panier est vide on redirige vers la page d'accueil
        if($panier === []) {
            //$session->addFlash('message', 'Votre panier est vide');
            return $this->redirectToRoute('accueil');
        }
        //Le panier n'est pas vide, on créé la commande
        //On parcourt le panier pour créer les détails de la commande
        foreach($panier as $item => $qt){
  


        }



        return $this->render('commande/index.html.twig', [
            'controller_name' => 'CommandeController',
        ]);
    }
}

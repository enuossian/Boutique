<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Service\CartService;
use Doctrine\ORM\EntityManager;
use App\Form\ModifierCommandeType;
use App\Repository\ProduitRepository;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class CommandeController extends AbstractController
{
    #[Route('/commander', name: 'ajout_commande')]
    public function index(CartService $cs, EntityManagerInterface $manager): Response
    {
        // connecter l'utilisateur
        // récupérer cartwithdata grâce à cartservice 
        $panier = $cs->getCartWithData('panier', []);
        // Si le panier est vide on redirige vers la page d'accueil
        if($panier === []) {
            return $this->redirectToRoute('accueil');
        }
        //On parcourt le panier pour créer les détails de la commande
        foreach($panier as $item ){
            $commande = new Commande; 
            $montant = $item['quantite'] * $item['produit']->getPrix();
            $commande->setUser($this->getUser());
            $commande->setDateEnregistrement(new \DateTime());
            $commande->setProduit($item['produit']);
            $commande->setQuantite($item['quantite']);
            $commande->setEtat('En cours de traitement');
            $commande->setMontant($montant);
            $manager->persist($commande);
        }
            $manager->flush();
        return $this->render('commande/index.html.twig', [
            
        ]);
    }
    #[Route('/admin/commandes', name: 'commandes_gestion')]
    public function commande(CommandeRepository $repo): Response
    {
        $commandes = $repo->findAll();
        return $this->render('admin/commande/gestion.html.twig', [
            'commandes' => $commandes,
        ]);
    }
    #[Route('/admin/commande/modifier/{id}', name: 'commande_modifier')]
    public function form(Request $request, EntityManagerInterface $manager, $id, CommandeRepository $repo): Response
    {
        $nouveau = $repo->find($id);
        $form = $this->createForm(ModifierCommandeType::class, $nouveau);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($nouveau);
            $manager->flush();

            return $this->redirectToRoute('membre_gestion');
        }
        return $this->render('admin/commande/modifier.html.twig', [
            'form' => $form,
            'nouveau' => $nouveau           
        ]);
    }

    #[Route('/admin/commandes/supprimer/{id}', name: 'supprimer_commande')]
    public function supprimer(Commande $commande, EntityManagerInterface $manager): Response
    {
        $manager->remove($commande);
        $manager->flush();
        return $this->redirectToRoute('commandes_gestion');
    }
}

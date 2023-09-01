<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    #[Route('/', name: 'accueil')]
    public function index(ProduitRepository $repo): Response
    {
        $produits = $repo->findAll();
        return $this->render('main/index.html.twig', [
            "produits" => $produits
        ]);
    }
    
    #[Route('/produit/voir/{id}', name:'voir_produit')]
    public function voir($id, ProduitRepository $repo, Request $request, EntityManagerInterface $manager)
    {
        $produit = $repo->find($id);
        return $this->render('main/show.html.twig', [
        'produit' => $produit
        ]);
    }
    
}

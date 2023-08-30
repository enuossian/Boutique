<?php

namespace App\Controller;

use DateTime;
use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\UserRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }

    #[Route('/produit/modifier/{id}', name:'modif_produit')]
    #[Route('/produit/ajouter', name: 'ajout_produit')]
    public function form(Request $request, EntityManagerInterface $manager, Produit $produit = null, SluggerInterface $slugger): Response
    {
        if($produit == null)
        { 
            $produit = new Produit;
        }

        $editMode = $produit->getId() !== null;
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {

            $imageFile = $form->get('photo')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
                try {
                    $imageFile->move(
                        $this->getParameter('img_upload'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }
                $produit->setPhoto($newFilename);
            }  

            $produit->setDateEnregistrement(new DateTime());
            $manager->persist($produit);
            $manager->flush();

            if($editMode)
            {
                $this->addFlash('success', "le produit a bien été modifié");
            } else {
                $this->addFlash('success', "le produit a bien été ajouté");
            }

            $this->addFlash('success', "le produit a bien été ajouté");
            return $this->redirectToRoute('produit_gestion');
        }

        return $this->render('admin/produit/form.html.twig', [
            'formProduit' => $form, 
            'editMode' => $editMode,
        ]);
    }
    #[Route('/produit/gestion', name:'produit_gestion')]
    public function gestion(ProduitRepository $repo) : Response
    {
        $produits = $repo->findAll();
        return $this->render('admin/produit/gestion.html.twig', [
            'produits' => $produits,
        ]);
    }
    #[Route('/produit/supprimer/{id}', name:'suppr_produit')]
    public function supp_produit(Produit $produit, EntityManagerInterface $manager)
    {
        $manager->remove($produit);
        $manager->flush();
        return $this->redirectToRoute('produit_gestion');
    }



   
}
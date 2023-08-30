<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ModifierType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminMembreController extends AbstractController
{
    #[Route('/admin/membre', name: 'membre_gestion')]
    public function index(UserRepository $repo): Response
    {
        $membres = $repo->findAll();
        return $this->render('admin_membre/gestion.html.twig', [
            'membres' => $membres,
            
        ]);
    }
    #[Route('/admin/membre/modifier/{id}', name: 'membre_modifier')]
    public function form(Request $request, EntityManagerInterface $manager, $id, UserRepository $repo): Response
    {
        $statut = $repo->find($id);
        $form = $this->createForm(ModifierType::class, $statut);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($statut);
            $manager->flush();

            return $this->redirectToRoute('membre_gestion');
        }
        return $this->render('admin_membre/form.html.twig', [
            'form' => $form,
            'statut' => $statut           
        ]);
    }
    #[Route('/admin/membre/supprimer/{id}', name: 'membre_supprimer')]
    public function supp_produit(User $user, EntityManagerInterface $manager)
    {

        $manager->remove($user);
        $manager->flush();
        return $this->redirectToRoute('membre_gestion');
    }
    

   

}

<?php
namespace App\Controller;

use App\Entity\Produit;

use App\Service\CartService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    
    #[Route('/cart', name: 'cart')]
    public function index(CartService $cs)
    {
        return $this->render('cart/index.html.twig', [
            'items' => $cs->getCarWithData(),
            'total' => $cs->getTotal()
        ]);

    }

    #[Route('/cart/add/{id}', name: 'cart_add')]
    public function add($id, CartService $cs)
    {
       $cs->add($id);
        return $this->redirectToRoute('accueil');

    }

    #[Route('/cart/remove/{id}', name:'cart_remove')]
    public function remove($id, CartService $cs)
    {
        $cs->remove($id);
        return $this->redirectToRoute('cart');
    }
}
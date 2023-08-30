<?php

namespace App\Service;

use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class CartService
{
    private $repo;
    private $rs;
    public function __construct(ProduitRepository $repo, RequestStack $rs)
    {
        $this->rs = $rs; 
        $this->repo = $repo;
    }
    public function add($id)
    {
        $session = $this->rs->getSession();
        $cart = $session->get('cart', []);
        $qt = $session->get('qt', 0);

        if(!empty($cart[$id]))
        {
            $cart[$id]++;
            $qt++;
        }else
        {   $qt++;
             $cart[$id] = 1;
        }
       

        $session->set('cart', $cart);
        $session->set('qt', $qt);
    }

    public function remove($id)
    {
        $session = $this->rs->getSession();
        $cart = $session->get('cart', []);

        $qt = $session->get('qt', 0);

        if(!empty($cart[$id]))
        {
            $qt -= $cart[$id];
            unset($cart[$id]);
        }

        if($qt < 0)
        {
            $qt = 0;
        }

        $session->set('qt', $qt);
        $session->set('cart', $cart);
    }

    public function getCarWithData()
    {
        $session = $this->rs->getSession();
        $cart = $session->get('cart', []);
        
        $cartWithData = [];

        foreach($cart as $id => $quantite)
        {
            $cartWithData[] = [
                'produit' => $this->repo->find($id),
                'quantite' => $quantite
            ];
        }
        return $cartWithData;
    }

    public function getTotal()
    {
        $total = 0; // j'initialise mon Total
        foreach ($this->getCarWithData() as $item)
        {
            $sousTotal = $item['produit']->getPrix() * $item['quantite'];
            $total = $total + $sousTotal;
        }
        return $total;
    }
}
<?php

namespace App\Services;

use App\Entity\Menus;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService {

    private RequestStack $requestStack;

    private EntityManagerInterface $entityManagerInterface;
     public function __construct(RequestStack $requestStack, EntityManagerInterface $entityManagerInterface) {
        $this->requestStack = $requestStack;
        $this->entityManagerInterface = $entityManagerInterface;
    }
public function addToCart(int $id): void {

 $cart =  $this->getSession()->get('cart', []);

 if(!empty($cart[$id])){
    $cart[$id]++;
 }else{
    $cart[$id]= 1;
 }
 $this->getSession()->set('cart', $cart);
}



public function getTotal(): array
{
    $cart = $this->getSession()->get('cart');
    if (!is_array($cart)) {
        return [];
    }
    $cartData = []; 
    foreach ($cart as $id => $quantity){
        $menus = $this->entityManagerInterface->getRepository(Menus::class)->findOneBy(['id' => $id]);
        if (!$menus){
            // Supprimer le produit puis continuer en sortant de la boucle
        }
        $cartData[] = [
            'menus' => $menus,
            'quantity' => $quantity
        ];
    }
    return $cartData;
}




public function removeToCart(int $id)
{
    $cart =  $this->requestStack->getSession()->get('cart', []);
    unset($cart[$id]);
    return $this->getSession()->set('cart', $cart);
}

public function removeCartAll() 
{
return $this->getSession()->remove('cart');
}


public function decrease(int $id)
{
    $cart =  $this->getSession()->get('cart', []);
    if($cart[$id] > 1){
        $cart[$id]--;
    }else{
        unset($cart[$id]);
    }
    $this->getSession()->set('cart', $cart);
}



private function getSession(): SessionInterface 
{
    return $this->requestStack->getSession();
}
}
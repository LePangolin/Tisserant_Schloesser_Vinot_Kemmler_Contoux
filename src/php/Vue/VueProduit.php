<?php

namespace custumbox\php\Vue;

use custumbox\php\Modele\Produit;
use Slim\Container;
use Slim\Http\Response;

class VueProduit{
    private $c;

    public function __construct(Container $c)
    {
        $this->c = $c;
    }

    public function affichageProduit(){
        $prod = Produit::get();
        $body = "";
        foreach($prod as $p){
            $body .= "Nom du produit : $p->titre,  Poids du produit : $p->poids  &nbsp; <img src=\"./assets/images/produits/$p->id.jpg\"></img> ";
        }
        return $body;
    }
}

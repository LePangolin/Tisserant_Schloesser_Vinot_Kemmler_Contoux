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

    public function affichageProduit(Response $response){
        $prod = Produit::get();
        $body = "";
        foreach($prod as $p){
            
        }
    }
}

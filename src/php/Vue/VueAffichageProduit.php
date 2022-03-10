<?php

namespace custumbox\Vue;

use custumbox\Modele\Categorie;
use Slim\Container;
use Slim\Http\Response;

class VueAffichageProduit{
    private $c;

    public function __construct(Container $c)
    {
        $this->c = $c;
    }

    public function affichageProduit(Response $response){
        $categ = Categorie::get();

    }
}

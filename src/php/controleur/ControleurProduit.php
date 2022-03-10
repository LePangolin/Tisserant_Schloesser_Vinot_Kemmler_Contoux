<?php

// NAMESPACE
namespace custumbox\php\controleur;

// IMPORTS
use Slim\Container;


class ControleurProduit{
    /**
     * Constantes
     */

    /**
     * @var object container
     */
    private object $c;

    /**
     * Constructeur de ListeController
     * @param object $c container
     */
    public function __construct(object $c) {
        $this->c = $c;
    }

}
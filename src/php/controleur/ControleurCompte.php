<?php
declare(strict_types=1);

// NAMESPACES
namespace custumbox\php\controleur;

// IMPORTS
use Slim\Container;
use custumbox\php\Modele\Compte;

class ControleurCompte
{
    // ATTRIBUTS
    private $container;

    // CONSTRUCTEUR
    public function __construct(Container $container) {
        $this->container = $container;
    }

    // METHODES
    private function creerCompteInBDD(array $args) : void{
        $c = new Compte();
        $c->Login = filter_var($args['Login'], FILTER_SANITIZE_STRING);
        $c->Mdp = password_hash(filter_var($args['Mdp'], FILTER_SANITIZE_STRING), PASSWORD_DEFAULT);
        $c->Mail = filter_var($args['Mail'], FILTER_SANITIZE_EMAIL);
        $c->Telephone = filter_var($args['Telephone'], FILTER_SANITIZE_STRING);
        $c->save();
    }
}
<?php
declare(strict_types=1);

// NAMESPACE
namespace custumbox\php\controleur;

// IMPORTS

use custumbox\php\Modele\Produit;
use custumbox\php\tools;
use custumbox\php\Vue\VueUtilisateur;
use Slim\Container;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Classe controleurAffichage,
 * Controleur sur l'affichage des produits
 */
class ControleurAffichage
{
    // ATTRIBUTS
    private $c;
    const HOME = "Home";

    // CONSTRUCTEUR
    public function __construct(object $container) {
        $this->c = $container;
    }

    public function afficherHome(Request $rq, Response $rs, array $args): Response{
        $container = $this->c;
        $base = $rq->getUri()->getBasePath();
        $route_uri = $container->router->pathFor("home",$args);
        $url = $base . $route_uri;
        $notif = tools::prepareNotif($rq);
        $vue = new VueUtilisateur([],ControleurAffichage::HOME,$notif,$base);
        $rs->getBody()->write($vue->render());
        return $rs;
    }
}
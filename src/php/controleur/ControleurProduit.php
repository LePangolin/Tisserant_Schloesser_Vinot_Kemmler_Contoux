<?php

// NAMESPACE
namespace custumbox\php\controleur;

// IMPORTS
use custumbox\php\Modele\Produit;
use custumbox\php\tools;
use custumbox\php\Vue\VueUtilisateur;
use Slim\Container;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;


class ControleurProduit{
    /**
     * Constantes
     */
    const SEARCH_RESULTS = "search_results";

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

    public function searchProducts(Request $rq, Response $rs, array $args): Response {
        $container = $this->c;
        $base = $rq->getUri()->getBasePath();
        $route_uri = $container->router->pathFor('searchProducts', $args);
        $url = $base . $route_uri;

        $search = $rq->getQueryParams()["q"];
        if (!isset($rq->getQueryParams()['q'])) {
            $rs->getBody()->write("ERREUR, il faut q=.... dans l'url");
        } else {
            $products = Produit::where([
                ['titre', 'LIKE', "%$search%"]
            ])->get();

            $notif = tools::prepareNotif($rq);

            $v = new VueUtilisateur($products, ControleurProduit::SEARCH_RESULTS, $notif, $base);

            $rs->getBody()->write($v->render());
        }

        return $rs;
    }
}
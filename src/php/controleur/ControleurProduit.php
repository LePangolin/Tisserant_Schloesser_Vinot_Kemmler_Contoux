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


class ControleurProduit{
    /**
     * Constantes
     */
    const SEARCH_RESULTS = "search_results";
    const ALL_PRODUCTS =  "all_products";

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

        $notif = tools::prepareNotif($rq);
    
        if (!isset($rq->getQueryParams()['q'])) {
            $products = Produit::get();

            $v = new VueUtilisateur($products, ControleurProduit::ALL_PRODUCTS, $notif, $base);
        } else {
            $search = $rq->getQueryParams()["q"];
            $products = Produit::where('titre', 'LIKE', "%$search%")->get();

            $v = new VueUtilisateur($products, ControleurProduit::SEARCH_RESULTS, $notif, $base);
        }

        $rs->getBody()->write($v->render());
        return $rs;
    }
}
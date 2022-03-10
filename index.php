<?php
/**
 * File:  index.php
 * description: fichier index projet CrazyCharlyDay
 *
 * @author: contoux
 * @author: tisserant
 * @author: kemmler
 * @author: schloesser
 * @author vinot
 */

session_start();

require_once __DIR__ . '/vendor/autoload.php';

use custumbox\php\controleur\ControleurAffichage;
use custumbox\php\controleur\ControleurCommande;
use custumbox\php\controleur\ControleurProduit;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\App;
use custumbox\php\dbInit;

require 'vendor/autoload.php';

$app = new App(dbInit::init());


$app->get('/produits',
    function (Request $rq, Response $rs, array $args): Response {
        $controller = new ControleurProduit($this);
        return $controller->searchProducts($rq, $rs, $args);
    })->setName('searchProducts');


$app->get('[/]', 
function(Request $rq, Response $rs, array $args): Response{
    $controller = new ControleurAffichage($this);
    return $controller->afficherHome($rq,$rs,$args);
})->setName("home");
$app->post('/formulaireCreerCommande[/]',
function (Request $rq, Response $rs, array $args):Response{
    $controller=new ControleurCommande($this);
    return $controller->creerCommande($rq,$rs,$args);

})->setName("formulaireCreerCommande");
try {
    $app->run();
} catch (Throwable $e) {
}



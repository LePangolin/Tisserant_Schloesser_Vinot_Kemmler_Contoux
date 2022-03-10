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
use custumbox\php\controleur\ControleurCompte;
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

/*************************
 * connexion
 *************************/

/**
 * pages
 */

// connexion
$app->get('/login',
    function (Request $rq, Response $rs, array $args): Response {
        $controller = new ControleurCompte($this);
        return $controller->loginPage($rq, $rs, $args);

    })->setName("login");

// inscription
$app->get('/signUp',
    function (Request $rq, Response $rs, array $args): Response {
        $controller = new ControleurCompte($this);
        return $controller->signUpPage($rq, $rs, $args);

    })->setName("signUp");

// deconnexion
$app->get('/logout',
    function (Request $rq, Response $rs, array $args): Response {
        $controller = new ControleurCompte($this);
        return $controller->logout($rq, $rs, $args);

    })->setName("logout");


/**
 * reception de donnees
 */

// reception connexion
$app->post('/loginConfirm',
    function (Request $rq, Response $rs, array $args): Response {
        $controller = new ControleurCompte($this);
        return $controller->authentification($rq, $rs, $args);

    })->setName("loginConfirm");

// reception inscription
$app->post('/signupConfirm',
    function (Request $rq, Response $rs, array $args): Response {
        $controller = new ControleurCompte($this);
        return $controller->newUser($rq, $rs, $args);

    })->setName("signupConfirm");

try {
    $app->run();
} catch (Throwable $e) {
}



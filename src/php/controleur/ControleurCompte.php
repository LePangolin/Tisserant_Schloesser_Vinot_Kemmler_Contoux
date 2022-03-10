<?php
declare(strict_types=1);

// NAMESPACES
namespace custumbox\php\controleur;

// IMPORTS
use custumbox\php\Vue\VueUtilisateur;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
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
        $c = new utilisateur();
        $c->Login = filter_var($args['Login'], FILTER_SANITIZE_STRING);
        $c->Mdp = password_hash(filter_var($args['Mdp'], FILTER_SANITIZE_STRING), PASSWORD_DEFAULT);
        $c->Mail = filter_var($args['Mail'], FILTER_SANITIZE_EMAIL);
        $c->Telephone = filter_var($args['Telephone'], FILTER_SANITIZE_STRING);
        $c->save();
    }

    private function loginValide( string $login) : bool{
        $res =  utilisateur::where('login', '=', $login)->get();
        $r = $res->count();
        if ($r==0) return true;
        else return false;
    }

    public function creerCompte(Request $rq, Response $rs, array $args): Response {
        try {
            $vue = new VueUtilisateur($this->container);

            if (sizeof($args) == 4) {
                if ($this->loginValide(filter_var($args['login'], FILTER_SANITIZE_STRING)) == true) {
                    $this->creerCompteInBDD($args);
                }
            }
        }catch (\Exception $e) {
            $rs->getBody()->write("Erreur dans la creation d'un compte...<br>".$e->getMessage()."<br>".$e->getTrace());
        }
        return $rs;
    }
}
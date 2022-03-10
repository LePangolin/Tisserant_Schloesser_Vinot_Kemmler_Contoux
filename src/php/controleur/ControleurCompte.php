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

    /**
     * Méthode qui créé un compte utilisateur dans la base de donnée
     *
     * @param array $args
     */
    private function creerCompteInBDD(array $args) : void{
        $c = new Compte();
        $c->Login = filter_var($args['Login'], FILTER_SANITIZE_STRING);
        $c->sel = password_hash(filter_var($args['Mdp'], FILTER_SANITIZE_STRING), PASSWORD_DEFAULT);
        $c->Mail = filter_var($args['Mail'], FILTER_SANITIZE_EMAIL);
        $c->Telephone = filter_var($args['Telephone'], FILTER_SANITIZE_STRING);
        $c->save();
    }

    /**
     * Méthode qui test si le login est déjà existant
     *
     * @param string $login
     * @return bool
     */
    private function loginValide( string $login) : bool{
        $res =  Compte::where('login', '=', $login)->get();
        $r = $res->count();
        if ($r==0) return true;
        else return false;
    }

    /**
     * Méthode qui créé un compte
     *
     * @param Request $rq
     * @param Response $rs
     * @param array $args
     * @return Response
     */
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

    private function supprimerSessionConnexion() :void {
        session_destroy();
    }

    private function genereSessionConnexion($login): void{
        session_start();
        $_SESSION['Login'] = $login;
    }

    private function mdpValide($login, $mdp): bool {
        $sel = Compte::select('Mdp')->where('Login', '=', $login)->first();
        return password_verify($mdp, $sel["Mdp"]);
    }

    public function seDeconnecterCompte(Request $rq, Response $rs, array $args): Response {
        $this->supprimerSessionConnexion();
        //$rs = $rs->withRedirect($this->container->router->pathFor('accueil'));
        return $rs;
    }

    public function seConnecterCompte(Request $rq, Response $rs, array $args): Response {
        try {
            $vue = new VueUtilisateur($this->container);
            if (sizeof($args) == 2) {
                $login = filter_var($args['login'], FILTER_SANITIZE_STRING);
                if ($this->loginValide($login) == false) {
                    if ($this->mdpValide($login, filter_var($args['psw'], FILTER_SANITIZE_STRING)) == true) {
                        $this->genereSessionConnexion($login);
                        //$rs = $rs->withRedirect($this->container->router->pathFor('accueil'));
                    } else {
                        $rs->getBody()->write($vue->render($vue->htmlErreur("Erreur, la connexion n'a pas pu aboutir. Erreur dans le mdp.")));
                    }
                } else {
                    $rs->getBody()->write($vue->render($vue->htmlErreur("Erreur, la connexion n'a pas pu aboutir. Erreur dans le login")));
                }
            }
        } catch (\Exception $e) {
            $rs->getBody()->write("Erreur a la connexion d'un compte...<br>".$e->getMessage()."<br>".$e->getTrace());
        }
        return $rs;
    }
}
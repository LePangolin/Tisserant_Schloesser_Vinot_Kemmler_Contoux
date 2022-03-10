<?php
declare(strict_types=1);

// NAMESPACES
namespace custumbox\php\controleur;

// IMPORTS
use custumbox\php\tools;
use custumbox\php\Vue\VueUtilisateur;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Container;
use custumbox\php\Modele\Compte;

class ControleurCompte {
    const LOGIN = 'login';
    const SIGNUP = 'signUp';
    const TAILLE_USERNAME_MIN = 4;
    const TAILLE_USERNAME_MAX = 100;
    const TAILLE_MDP_MIN = 8;
    const TAILLE_MDP_MAX = 256;

    /**
     * @var object container
     */
    private object $c;

    /**
     * Constructeur de RegisterController
     * @param object $c container
     */
    public function __construct(object $c) {
        $this->c = $c;
    }

    /**
     * Traitement de l'inscription d'un utilisateur
     * @param Request $rq requête
     * @param Response $rs réponse
     * @param array $args arguments de la requête
     * @return Response
     */
    public function newUser(Request $rq, Response $rs, array $args): Response {
        $container = $this->c;
        $base = $rq->getUri()->getBasePath();
        $route_uri = $container->router->pathFor('signupConfirm');
        $url = $base . $route_uri;
        $content = $rq->getParsedBody();

        $NomUtilisateur = filter_var($content['username'], FILTER_SANITIZE_STRING);
        $MotDePasse = $content['password'];
        $options = ['cost' => 12];
        $MotDePasseConfirm = $content['password_confirm'];
        $Email = filter_var($content['email'], FILTER_SANITIZE_EMAIL);

        $userNameExist = Compte::where("Login", "=", $NomUtilisateur)->count();

        if (strlen($NomUtilisateur) < self::TAILLE_USERNAME_MIN) {
            $notifMsg = urlencode("Ce nom d'utilisateur est trop court. Réessayez.");
            return $rs->withRedirect($base."/signUp?notif=$notifMsg");
        } else if (strlen($NomUtilisateur) > self::TAILLE_USERNAME_MAX) {
            $notifMsg = urlencode("Ce nom d'utilisateur est trop long. Réessayez.");
            return $rs->withRedirect($base."/signUp?notif=$notifMsg");
        } else if ($userNameExist != 0) {
            $notifMsg = urlencode("Ce nom d'utilisateur est déjà pris. Réessayez.");
            return $rs->withRedirect($base."/signUp?notif=$notifMsg");
        } else if (strlen($MotDePasse) < self::TAILLE_MDP_MIN) {
            $notifMsg = urlencode("Ce mot de passe est trop court. Réessayez.");
            return $rs->withRedirect($base."/signUp?notif=$notifMsg");
        } else if (strlen($MotDePasse) > self::TAILLE_MDP_MAX) {
            $notifMsg = urlencode("Ce mot de passe est trop long. Réessayez.");
            return $rs->withRedirect($base."/signUp?notif=$notifMsg");
        } else if ($MotDePasseConfirm != $MotDePasse) {
            $notifMsg = urlencode("Les mots de passe ne correspondent pas. Réessayez.");
            return $rs->withRedirect($base."/signUp?notif=$notifMsg");
        } else {
            $MotDePasse = password_hash($MotDePasse, PASSWORD_DEFAULT, $options);
            $newUser = new Compte();
            $newUser->Login=$NomUtilisateur;
            $newUser->sel=$MotDePasse;
            $newUser->Mail=$Email;
            $newUser->Telephone = filter_var($args['Telephone'], FILTER_SANITIZE_STRING);
            $newUser->Niveau_acces=1;
            $newUser->save();

            // gestion session
            $this->sessionConnexion($newUser);

            $notifMsg = urlencode("Vous êtes connecté en tant que $NomUtilisateur.");
            return $rs->withRedirect($base."?notif=$notifMsg");
        }
    }

    /**
     * Gestion de la session lors de la connexion d'un utilisateur
     * @param Compte $user Utilisateur connecté
     * @return void
     */
    private function sessionConnexion(Compte $user): void {
        if (isset($_SESSION['Login'])) {
            session_destroy();
            session_start();
        }
        $_SESSION['Login'] = $user['Login'];
        $_SESSION['AccessRights'] = $user['Niveau_acces'];
    }

    /**
     * Gestion de la session lors de la déconnexion d'un utilisateur
     * @return void
     */
    private function sessionDeconnexion(): void {
        session_destroy();
        session_start();
    }

    /**
     * Traitement de la déconnexion d'un utilisateur
     * @param Request $rq requête
     * @param Response $rs réponse
     * @param array $args arguments de la requête
     * @return Response
     */
    public function logout(Request $rq, Response $rs, array $args): Response {
        $container = $this->c;
        $base = $rq->getUri()->getBasePath();
        $route_uri = $container->router->pathFor('logout');
        $url = $base . $route_uri;


        $this->sessionDeconnexion();

        $notifMsg = urlencode("Vous avez été déconnecté.");


        return $rs->withRedirect($base."/login?notif=$notifMsg");
    }

    /**
     * Affichage de la page permettant la connexion d'un utilisateur
     * @param Request $rq requête
     * @param Response $rs réponse
     * @param array $args arguments de la requête
     * @return Response
     */
    public function loginPage(Request $rq, Response $rs, array $args): Response {
        $container = $this->c;
        $base = $rq->getUri()->getBasePath();
        $route_uri = $container->router->pathFor('login');
        $url = $base . $route_uri;

        $notif = tools::prepareNotif($rq);

        $v = new VueUtilisateur([], ControleurCompte::LOGIN, $notif, $base);
        $rs->getBody()->write($v->render());
        return $rs;
    }

    /**
     * Traitement de la connexion d'un utilisateur
     * @param Request $rq requête
     * @param Response $rs réponse
     * @param array $args arguments de la requête
     * @return Response
     */
    public function authentification(Request $rq, Response $rs, array $args): Response {
        $container = $this->c;
        $base = $rq->getUri()->getBasePath();
        $route_uri = $container->router->pathFor('loginConfirm');
        $url = $base . $route_uri;

        $content = $rq->getParsedBody();

        $NomUtilisateur = $content['username'];
        $MotDePasse = $content['password'];


        $userNameExist = Compte::where("Login", "=", $NomUtilisateur)->count();

        if ($userNameExist == 1) {
            $GetUser=Compte::where("Login","=",$NomUtilisateur)->first();
            $HashedPassword=$GetUser->password;
            if (password_verify($MotDePasse,$HashedPassword)) {
                $user = Compte::where('Login', '=', $NomUtilisateur)->first();

                $this->sessionConnexion($user);

                $notifMsg = urlencode("Vous êtes connecté en tant que $NomUtilisateur.");
                return $rs->withRedirect($base."?notif=$notifMsg");
            }
        }

        $this->sessionDeconnexion();

        $notifMsg = urlencode("Mot de passe ou nom d'utilisateur incorrect.");
        return $rs->withRedirect($base."/login?notif=$notifMsg");
    }

    /**
     * Affichage de la page permettant l'inscription d'un utilisateur
     * @param Request $rq requête
     * @param Response $rs réponse
     * @param array $args arguments de la requête
     * @return Response
     */
    public function signUpPage(Request $rq, Response $rs, array $args): Response {
        $container = $this->c;
        $base = $rq->getUri()->getBasePath();
        $route_uri = $container->router->pathFor('signUp');
        $url = $base . $route_uri;

        $notif = tools::prepareNotif($rq);

        $v = new VueUtilisateur([], ControleurCompte::SIGNUP, $notif, $base);
        $rs->getBody()->write($v->render());
        return $rs;
    }
}
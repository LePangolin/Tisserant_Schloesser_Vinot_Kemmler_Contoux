<?php
declare(strict_types=1);

// NAMESPACE
namespace custumbox\php\controleur;

// IMPORTS
use custumbox\php\Modele\Boite;
use custumbox\php\Modele\Commande;
use custumbox\php\Modele\Produit;
use custumbox\php\tools;
use custumbox\php\Vue\VueUtilisateur;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Container;
use Slim\Http\Request;

/**
 * Classe controleurAffichage,
 * Controleur sur l'affichage des produits
 */
class ControleurCommande {
    //Constantes
    const COMMANDE_FORM_CREATE='commande_form_create';

    // ATTRIBUTS
    private $c;

    // CONSTRUCTEUR
    public function __construct(Container $container) {
        $this->c = $container;
    }
    public function construireCommande(Request $rq,Response $rs,array $args): Response {
        $container = $this->c;
        $base = $rq->getUri()->getBasePath();
        $route_uri = $container->router->pathFor('nouvelleCommande');
        $url = $base . $route_uri;

        $content = $rq->getParsedBody();

        if (isset($_SESSION['username']) && isset($_SESSION['AccessRights'])) {
            $nomBoite=filter_var($content['boite'],FILTER_SANITIZE_STRING);
            $message=filter_var($content['message'],FILTER_SANITIZE_STRING);
            $idCreateur=$content['createur'];
            $couleur=$content['couleur'];
            $destinaire=filter_var($content['destinataire'],FILTER_SANITIZE_STRING);
            $lien=$content['lien'];
            $produits=$content['produits'];
            $boite=Boite::where("taille","=",$nomBoite);

            if(is_null($boite)){
                $notifMsg = urlencode("Taille de boite inconnue, veuillez contacter un administrateur");
                return $rs->withRedirect($base."?notif=$notifMsg");
            }else{
                $CommandeToAdd=new Commande();
                $CommandeToAdd->idBoite=$boite->id;
                $CommandeToAdd->Message=$message;
                $CommandeToAdd->idCreateur=$idCreateur;
                $CommandeToAdd->Couleur=$couleur;
                $CommandeToAdd->Destinataire=$destinaire;
                $CommandeToAdd->Lien=$lien;
                $CommandeToAdd->save();
                foreach ($produits as $produit){
                    $prod=Produit::where("name","=",$produit['nom']);
                    $CommandeToAdd->produits()->save($prod, ['qte'=>$produit['qte']]);
                }
                $notifMsg = urlencode("Commande créée !");
                return $rs->withRedirect($base."/list/view?token=$CommandeToAdd->id&notif=$notifMsg");
            }
        }else{
            $notifMsg = urlencode("Impossible de créer une nouvelle liste. Reconnectez-vous.");
            return $rs->withRedirect($base."/login?notif=$notifMsg");
        }
    }

    /**
     * Affichage de la page permettant de créer une liste
     * @param Request $rq requête
     * @param Response $rs réponse
     * @param array $args arguments de la requête
     * @return Response
     */
    public function creerCommande(Request $rq, Response $rs, array $args): Response {
        $container = $this->c;
        $base = $rq->getUri()->getBasePath();
        $route_uri = $container->router->pathFor('formulaireCreerCommande');
        $url = $base . $route_uri;

        $notif = tools::prepareNotif($rq);

        if (!isset($_SESSION['username']) || !isset($_SESSION['AccessRights'])) {
            $notifMsg = urlencode("Impossible de créer une nouvelle commande. Reconnectez-vous.");
            return $rs->withRedirect($base."/login?notif=$notifMsg");
        }

        $v = new VueUtilisateur([], ControleurCommande::COMMANDE_FORM_CREATE, $notif, $base);
        $rs->getBody()->write($v->render());
        return $rs;
    }
}
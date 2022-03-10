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
use http\Message\Body;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Container;
use Slim\Http\Request;

/**
 * Classe controleurAffichage,
 * Controleur sur l'affichage des produits
 */
class ControleurCommande
{
    // ATTRIBUTS
    private $c;
    const COMMANDE = "commande";

    // CONSTRUCTEUR
    public function __construct(Container $container) {
        $this->c = $container;
    }
    /*
    public function creerCommande(Request $rq,Response $rs,array $args): Response {
        $container = $this->c;
        $base = $rq->getUri()->getBasePath();
        $route_uri = $container->router->pathFor('createCommande', $args);
        $url = $base . $route_uri;
        $content = $rq->getParsedBody();
        $nomBoite=filter_var($content['boite'],FILTER_SANITIZE_STRING);
        $message=filter_var($content['message'],FILTER_SANITIZE_STRING);
        $idCreateur=$content['createur'];
        $couleur=$content['couleur'];
        $destinaire=filter_var($content['destinataire'],FILTER_SANITIZE_STRING);
        $lien=$content['lien'];
        $produits=$content['produits'];
        $boite=Boite::where("taille","=",$nomBoite);

        if(is_null($boite)){
            echo ("Taille de la boite inconnu, veuillez contacter un administrateur");
        }else{
            $CommandeToAdd=new Commande();
            $CommandeToAdd->idBoite=$boite->id;
            $CommandeToAdd->Message=$message;
            $CommandeToAdd->idCreateur=$idCreateur;
            $CommandeToAdd->Couleur=$couleur;
            $CommandeToAdd->Destinataire=$destinaire;
            $CommandeToAdd->Lien=$lien;
            $CommandeToAdd->save();
            echo("Sauvegarde effectuée dans Commande");
            foreach ($produits as $produit){
                $prod=Produit::where("name","=",$produit['nom']);
                $CommandeToAdd->produits()->save($prod, ['qte'=>$produit['qte']]);
            }
            $rs->getBody()->write("Ajout de la commande");
        }
        return $rs;
    }
    */


    public function enregistrerCommande(Request $rq,Response $rs,array $args): Response {
        $container = $this->c;
        $base = $rq->getUri()->getBasePath();
        $route_uri = $container->router->pathFor("faireCommande",$args);
        $url = $base . $route_uri;
        $notif = tools::prepareNotif($rq);
        $listeProduit = Produit::get();


        $vue = new VueUtilisateur($listeProduit,ControleurCommande::COMMANDE,$notif,$base);
        $rs->getBody()->write($vue->render());

        return $rs;
    }
}
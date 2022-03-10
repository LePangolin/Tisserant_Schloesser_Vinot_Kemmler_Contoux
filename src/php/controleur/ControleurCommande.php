<?php
declare(strict_types=1);

// NAMESPACE
namespace custumbox\php\controleur;

// IMPORTS
use custumbox\php\Modele\Boite;
use custumbox\Modele\Commande;
use custumbox\php\Modele\Produit;
use Slim\Container;

/**
 * Classe controleurAffichage,
 * Controleur sur l'affichage des produits
 */
class ControleurCommande
{
    // ATTRIBUTS
    private $c;

    // CONSTRUCTEUR
    public function __construct(Container $container) {
        $this->c = $container;
    }
    public function creerCommande(Request $rq,Response $rs,array $args){
        $container = $this->c;
        $base = $rq->getUri()->getBasePath();
        $route_uri = $container->router->pathFor();
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


        }


    }
}
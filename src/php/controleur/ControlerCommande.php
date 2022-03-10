<?php
declare(strict_types=1);

// NAMESPACE
namespace custumbox\php\controleur;

// IMPORTS
use custumbox\Modele\Boite;
use custumbox\Modele\Commande;
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
    public function createCommande(Request $rq,Response $rs,array $args){
        $container = $this->c;
        $base = $rq->getUri()->getBasePath();
        $route_uri = $container->router->pathFor();
        $url = $base . $route_uri;
        $content = $rq->getParsedBody();
        $nomBoite=$content['boite'];
        $message=$content['message'];
        $idCreateur=$content['createur'];
        $couleur=$content['couleur'];
        $destinaire=$content['destinataire'];
        $lien=$content['lien'];

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
            echo("Sauvegarde effectuée");
        }


    }
}
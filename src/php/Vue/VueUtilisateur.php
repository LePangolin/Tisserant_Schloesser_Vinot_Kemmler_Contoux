<?php

namespace custumbox\php\Vue;

use custumbox\php\controleur\ControleurAffichage;
use custumbox\php\controleur\ControleurCommande;
use custumbox\php\controleur\ControleurProduit;
use custumbox\php\Modele\Produit;
use custumbox\php\tools;
use Slim\Container;
use Slim\Http\Response;

class VueUtilisateur{
    /**
     * @var iterable Tableau d'éléments à afficher
     */
    private iterable $tab;
    /**
     * @var string Sélecteur de l'affichage à fournir
     */
    private string $selecteur;
    /**
     * @var array Propriétés de la notification
     */
    private array $notif;
    /**
     * @var string Base du site
     */
    private string $base;

    /**
     * Constructeur de la vue créateur
     * @param iterable $t Tableau d'éléments à afficher
     * @param string $s Sélecteur de l'affichage à fournir
     * @param array $n Propriétés de la notification
     * @param string $b Base du site
     */
    public function __construct(iterable $t, string $s, array $n, string $b) {
        $this->tab = $t;
        $this->selecteur = $s;
        $this->notif = $n;
        $this->base = $b;
    }

    private function affichageProduit(){
        $body = <<<END
        <form action="$this->base/produits">
          <label for="q">Chercher un produit</label>
          <input type="search" id="q" name="q">
          <input type="submit" value="Rechercher">
        </form>
        END;
        foreach($this->tab as $p){
            $body .= "Nom du produit : $p->titre, <br> Poids du produit : $p->poids, <br> Description : $p->description &nbsp; <br> <img src=\"./assets/images/produits/$p->id.jpg\"></img> <br /> ";
        }
        return $body;
    }
    private function creerCommande():string{
        $file="mettreformulairehtml";
        return file_get_contents($file);
    }
    public function render(): string {
        $from = "";
        $htmlPage = "";
        $title = "";
        $notif = "";
        $content = "";
        switch ($this->selecteur) {
            case ControleurProduit::SEARCH_RESULTS : {
                $content = $this->affichageProduit();
                $title = "Résultats de recherche";
                break;
            }
            case ControleurAffichage::HOME : {
                $htmlPage = $this->home();
                break;
            }
            case ControleurProduit::ALL_PRODUCTS : {
                $content = $this->affichageProduit();
                $title  = "Tout les produits disponible";
                break;
            }
            case ControleurCommande::COMMANDE_FORM_CREATE :{
                $content =$this->creerCommande();
                $title = 'Création d\'une commande';
                break;
            }
        }
        return tools::getHtml($from, $htmlPage, $title, $notif, $content, $this->notif, $this->base);
    }

    private function home(){
        $file = "./src/html/index.html";
        return file_get_contents($file);
    }
}

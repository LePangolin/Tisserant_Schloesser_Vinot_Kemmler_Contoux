<?php

namespace custumbox\php\Vue;

use custumbox\php\controleur\ControleurAffichage;
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
        $body = "";
        foreach($this->tab as $p){
            $body .= "Nom du produit : $p->titre, <br> Poids du produit : $p->poids, <br> Description : $p->description &nbsp; <br> <img src=\"./assets/images/produits/$p->id.jpg\"></img> <br /> ";
        }
        return $body;
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
                //$from = 'indexStyle.css';
                break;
            }
            case ControleurAffichage::HOME : {
                $htmlPage = $this->home();
                //$from = "indexStyle.css"; 
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

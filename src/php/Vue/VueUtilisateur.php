<?php
declare(strict_types=1);

namespace custumbox\php\Vue;

use custumbox\php\controleur\ControleurAffichage;
use custumbox\php\controleur\ControleurCommande;
use custumbox\php\controleur\ControleurCompte;
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
        <div class="row">
        END;
        foreach($this->tab as $p){
            $body .= "<div class='col-12 col-md-6 pg-l-4'> <div class='card'style='width: 100%;'> <img class='card-img-top' src=\"./assets/images/produits/$p->id.jpg\"> <div> <h5 class='card-title'>$p->titre , Poids du produit : $p->poids,  </h5><p class='card-text'> $p->description</p></div></div> </div> <br /> ";
        }
        $body.="</div";
        return $body;
    }

    private function home(){
        $file = "./src/html/index.html";
        return file_get_contents($file);
    }
    private function formulaireCommande() {

        $listeProduits = $this->tab;
        $produits = "";
        foreach($this->tab as $p){
            $produits .= " <option value='$p->titre'>$p->titre</option>";
        }



        $body =  <<<END
        <div id="form-outer"> 
            <form id="survey-form" method="post" action="$this->base/nouvelleCommande">
                <div class="rowTab">
                    <div>
                        <select id="dropdown" name="taille" class="dropdown" defaultValue>
                            <option disabled selected value>Taille de ta box</option>
                            <option selected value="petite">Petite</option>
                            <option value="moyenne">Moyenne</option>
                            <option value="grande">Grande</option>
                        </select>
                    </div>
                </div>
                <div class="rowTab">
                    <div class="labels">
                        <label for="comments">Un message à ajouter ?</label>
                    </div>
                    <div class="rigtTab">
                        <textarea id="comments" name="message" class="input-field" style="height:50px; resize:vertical;" placeholder="je sais pas"></textarea>
                    </div>
                </div>
                <div class="baseColor">
                    <label for="Color">Primary Color: </label>
                    <input  id="Color" type="color" name="couleur" value="#09091B">
                </div>
                <div class="rowTab">
                    <div class="labels">
                        <label for="destinaire">Quel est votre destinataire ?</label>
                    </div>
                    <div>
                        <input id="destinaire" name="destinataire" class="input-field" type="text" placeholder="Adresse"/>
                    </div>
                </div>
                <input id="contenu-cart" style="display: none;" type="text" value=""/>
                <button id="submit" type="submit">Envoi !</button>
                <div class="rowTab">
                    <div>
                        <select id="dropdown-products" name="produits" class="dropdown">
                            <option disabled selected value>Tout nos produits</option>
                            $produits
                        </select>
                    </div>
                </div>
            </form>
            <div id="produitSelect"></div>
            <div id="cart">
            </div>
        </div>
        <script> 
        let panier = [];
        let produit;
        let poidsTotal = 0;
        let produitDisplay = document.getElementById('produitSelect');
        document.getElementById('dropdown-products').addEventListener('change', (event) => {
            console.log(event.target.value);
            let html = "";
            let produitsAct = event.target.value;
            let produits = $listeProduits;
            
            console.log(produits);
            produits.forEach( elem => {
                if (elem.titre === produitsAct) {
                    produit = elem;
                    html = `
                    <div class="rowTab">
                        <p> \${elem.titre} <p/>
                        <br>
                        <img src="./assets/images/produits/\${elem.id}.jpg" alt="\${elem.titre}">
                        <p> \${elem.description}<p/>
                        <p> Poids : \${elem.poids} kg <p/>
                        <br>
                        <button id="boutonAjout" >Ajouter au panier<button/>  
                    <div/>
                        `;
                }
            })
            produitDisplay.innerHTML = html;
            document.getElementById('boutonAjout').addEventListener('click', (e) => {
                console.log(produit);
                let taille = document.getElementById("dropdown");
                let poidsLimite = 0;
                switch (taille.value) {
                  case 'petite' :
                     poidsLimite = 0.7;
                      break;
                  case 'moyenne' :
                     poidsLimite = 1.5;
                      break;
                  case 'grande' :
                     poidsLimite = 3.2;
                      break;
                }
                console.log("Poid limite"+poidsLimite);
                if (poidsTotal + produit.poids > poidsLimite) {
                    window.alert('le poids total de la box est dépassé *!')
                }else {
                    let objetProduit = null;
                    const qty = 1;
                    panier.forEach(obj => {
                        console.log(obj.allo);
                        if (obj.allo.titre === produit.titre) {
                            objetProduit = obj;
                        } 
                    });
                    if (objetProduit !== null) {
                        objetProduit.quantity++;
                    } else {
                        panier.push({allo : produit, quantity : qty})
                    }
                    document.getElementById("contenu-cart").value = JSON.stringify(panier);
                }
                console.log(panier);
                let panierDisplay = "<ul>";
                panier.forEach(objet => {
                    poidsTotal = 0;    
                    poidsTotal += objet.allo.poids * objet.quantity;
                    console.log(poidsTotal);
                    panierDisplay += `<li> Titre : \${objet.allo.titre} | Quantité : \${objet.quantity} | Poids : \${objet.allo.poids * objet.quantity} </li>`
                })
                panierDisplay += "</ul>";
                document.getElementById("cart").innerHTML = panierDisplay;
            })
        });
        </script>
        END;

        return $body;
    }

    /**
     * Récupère la page de connexion
     * @return string
     */
    private function loginPage(): string {
        $file =  "./src/html/formLogin.html";
        return file_get_contents($file);
    }

    /**
     * Récupère la page d'inscription
     * @return string
     */
    public function signUpPage(): string {
        $file =  "./src/html/formSignUp.html";
        return file_get_contents($file);
    }
    private function creerCommande():string{
        $file="./src/html/index.html";
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
            case ControleurCompte::LOGIN : {
                $htmlPage = $this->loginPage();
                break;
            }
            case ControleurCompte::SIGNUP : {
                $htmlPage = $this->signUpPage();
                break;
            }
            case ControleurProduit::ALL_PRODUCTS : {
                $content = $this->affichageProduit();
                $title  = "Tout les produits disponible";
                break;
            }
            case ControleurCommande::COMMANDE : {
                $content = $this->formulaireCommande();
                $title = "Création de votre commande";
                $from = "commande.css";
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

}

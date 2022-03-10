<?php
declare(strict_types=1);

// NAMESPACE
namespace custumbox\php\controleur;

// IMPORTS
use Conf;
use custumbox\php\Modele\Categorie;
use custumbox\php\Modele\Produit;
use custumbox\php\tools;
use custumbox\php\Vue\VueUtilisateur;
use Slim\Container;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;


class ControleurProduit{
    /**
     * Constantes
     */
    const SEARCH_RESULTS = "search_results";
    const ALL_PRODUCTS =  "all_products";
    const CREATE_PRODUCTS= "create_products";

    /**
     * @var object container
     */
    private object $c;

    /**
     * Constructeur de ListeController
     * @param object $c container
     */
    public function __construct(object $c) {
        $this->c = $c;
    }

    public function searchProducts(Request $rq, Response $rs, array $args): Response {
        $container = $this->c;
        $base = $rq->getUri()->getBasePath();
        $route_uri = $container->router->pathFor('searchProducts', $args);
        $url = $base . $route_uri;

        $notif = tools::prepareNotif($rq);
    
        if (!isset($rq->getQueryParams()['q'])) {
            $products = Produit::get();

            $v = new VueUtilisateur($products, ControleurProduit::ALL_PRODUCTS, $notif, $base);
        } else {
            $search = $rq->getQueryParams()["q"];
            $products = Produit::where('titre', 'LIKE', "%$search%")->get();

            $v = new VueUtilisateur($products, ControleurProduit::SEARCH_RESULTS, $notif, $base);
        }

        $rs->getBody()->write($v->render());
        return $rs;
    }
    public function creerProduit(Request $rq, Response $rs,array $args):Response{

        $container = $this->c;
        $base = $rq->getUri()->getBasePath();
        $route_uri = $container->router->pathFor('produitVierge');
        $url = $base . $route_uri;

        $notif = tools::prepareNotif($rq);


        if (!isset($_SESSION['username']) || !isset($_SESSION['AccessRights'])) {
            $notifMsg = urlencode("Impossible de créer une nouvelle liste. Reconnectez-vous.");
            return $rs->withRedirect($base."/login?notif=$notifMsg");
        }

        $v = new VueUtilisateur([], ControleurProduit::CREATE_PRODUCTS, $notif, $base);
        $rs->getBody()->write($v->render());
        return $rs;
    }
    public function constructionProduit(Request $rq,Response $rs,array $args):Response{
        $container = $this->c;
        $base = $rq->getUri()->getBasePath();
        $route_uri = $container->router->pathFor('nouvelleCommande');
        $url = $base . $route_uri;

        $content = $rq->getParsedBody();

        if (isset($_SESSION['username']) && isset($_SESSION['AccessRights'])) {
            $titre=filter_var($content['nom'],FILTER_SANITIZE_STRING);
            $descr=filter_var($content['description'],FILTER_SANITIZE_STRING);
            $nomcateg=$content['categorie'];
            $poids=floatval($content['poids']);
            $categ=Categorie::where('nom','=',$nomcateg)->first();
            if(is_null($categ)){
                $notifMsg = urlencode("La catégorie n'existe pas, veuillez la créer !");
                return $rs->withRedirect($base."?notif=$notifMsg");
            }else{
                $idCateg=$categ->id;
                $newProducts=new Produit();
                $newProducts->nom=$titre;
                $newProducts->description=$descr;
                $newProducts->poids=$poids;
                $newProducts->save();

                // upload image
                $cheminServeur = $_FILES['photo']['tmp_name'];
                $extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
                $fileName = $newProducts->id.'.'.$extension;
                $uploadFile = Conf::PATH_IMAGE_PRODUIT . "/$fileName";
                move_uploaded_file($cheminServeur, $uploadFile);
            }
            $notifMsg = urlencode("Produit créée !");
            return $rs->withRedirect($base."?notif=$notifMsg");

        }else{
            $notifMsg = urlencode("Impossible de créer un nouveau produit. Reconnectez-vous en tant qu'Administrateur.");
            return $rs->withRedirect($base."/login?notif=$notifMsg");
        }
    }
}
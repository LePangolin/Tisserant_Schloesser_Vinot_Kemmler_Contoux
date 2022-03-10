<?php

namespace custumbox\php;

use JetBrains\PhpStorm\ArrayShape;
use Psr\Http\Message\ServerRequestInterface as Request;

class tools {
    /**
     * Permet d'ajouter un élément dans le corps de la page
     * @param string $page page
     * @param string $text Élément à ajouter
     * @return string
     */
    public static function insertIntoBody(string $page, string $text): string {
        $positionBody = strpos($page, "<body>") + 6;
        return substr_replace($page, $text, $positionBody, 0);
    }

    /**
     * Génère une boîte avec un message
     * @param string $message message
     * @return string
     */
    public static function messageBox(string $message): string {
        return <<<END
        <script>
            window.addEventListener("load", () => {
                let notif = document.getElementById('notif-box');
                
                let interval = setInterval(disparaitre, 15000);
                
                function disparaitre() {
                    notif.style.display = "none";
                        
                    let url = window.location.href;
                    url = url.substring(0, url.indexOf("notif")-1)
                    history.pushState(null, "", url);
                    
                    clearInterval(interval);
                }
                notif.addEventListener("click", disparaitre);
            });
        </script>
        <style>
        #notif-box {
            top:100px;
            right:20px;
            position:fixed;
            z-index:300;
        }
        #notif {
            background-color:white;
            border-radius:5px;
            box-shadow:0 5px 12px 0 rgba(0,0,0,.3);
            color:black;
            margin-bottom:3px;
            max-height:232px;
            overflow:hidden;
            padding:18px 18px 18px 24px;
            position:relative;
            width:420px
        }
        #notif:before {
            background-color:#656565;
            border-radius:5px 0 0 5px;
            content:"";
            display:block;
            height:100%;
            left:0;
            position:absolute;
            top:0;
            width:6px
        }
        #notif-content {
            flex:1;
            font-size:14px;
            font-weight:300;
            line-height:1.5;
            text-align:left
        }
        #notif-box:hover {
            cursor:pointer;
        }
        </style>
        <div id="notif-box">
            <div id="notif">
                <div id="notif-content">
                    <p>$message</p>
                </div>
            </div>
        </div>
        END;
    }

    /**
     * Autorise l'affichage des notifications (messageBox)
     * @param Request $rq requete
     * @return array
     */
    public static function prepareNotif(Request $rq): array {
        return array(
            "notif" => isset($rq->getQueryParams()['notif']) ? urldecode($rq->getQueryParams('notif')["notif"]): null,
            "link" => isset($rq->getQueryParams()['notif']) && isset($rq->getQueryParams()['link']) ? filter_var($rq->getQueryParams('link')["link"], FILTER_SANITIZE_ADD_SLASHES): null,
        );
    }

    /**
     * Formate l'affichage
     * @param string $from
     * @param string $htmlPage
     * @param string $title
     * @param string $notif
     * @param string $content
     * @param array $notifParams
     * @param string $base
     * @return string
     */
    public static function getHtml(string $from, string $htmlPage, string $title, string $notif, string $content, array $notifParams, string $base): string {
        $style = $from != "" ? "<link rel='stylesheet' href='$base/src/style/$from'>": "";
        $connexion = !isset($_SESSION['username'])
            ? "<a href='$base/login'>Connexion</a>"
            : <<<END
            <a href="$base/list">Mes listes</a>
            <a href='$base/monCompte'>👤 {$_SESSION['username']}</a>
            <a href='$base/logout'>Se déconnecter</a>
            END;

        $nav = <<<END
        <div class="topnav" id="myTopnav">
            <a href="$base/" class="active">CustomBox</a>
            <a href="$base/formulaireListe">Produit</a>
            <a href="$base/token">jesais pas</a>
            <a href="$base/createurs">allo</a>
            <a href="javascript:void(0);" class="icon" onclick="myFunction()">
                <p class="fa fa-bars">
                    <span class="hamburger"></span>
                    <span class="hamburger"></span>
                    <span class="hamburger"></span>
                </p>
            </a>
            $connexion
        </div>
        <script>
            function myFunction() {
              const x = document.getElementById("myTopnav");
              if (x.className === "topnav") {
                x.className += " responsive";
              } else {
                x.className = "topnav";
              }
            }
        </script>
        END;


        $html = $htmlPage != "" ? self::insertIntoBody($htmlPage, $nav.
            "<link rel=\"stylesheet\" href=\"$base/src/style/indexStyle.css\">") : <<<END
            <!DOCTYPE html> <html lang="fr">
            <head>
                <meta charset="UTF-8">
                <link rel="stylesheet" href="$base/src/style/indexStyle.css">
                <title>$title</title>
                $style
            </head>
            <body>
            $notif
            $nav
            <div class="content">
            $content
            </div>
            </body></html>
        END;

        if (!is_null($notifParams["notif"])) {
            $texte = $notifParams["notif"];
            if (is_null($notifParams["link"])) {
                $html = tools::insertIntoBody($html, tools::messageBox($texte));
            } else {
                $lien = $notifParams['link'];
                $html = tools::insertIntoBody($html, tools::messageBox("<a href='$lien'>$texte</a>"));
            }
        }
        return $html;
    }
}
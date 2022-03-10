<?php
declare(strict_types=1);

// NAMESPACE
namespace custumbox\php\controleur;

// IMPORTS
use Slim\Container;

/**
 * Classe controleurAffichage,
 * Controleur sur l'affichage des produits
 */
class controleurAffichage
{
    // ATTRIBUTS
    private $container;

    // CONSTRUCTEUR
    public function __construct(Container $container) {
        $this->container = $container;
    }
}
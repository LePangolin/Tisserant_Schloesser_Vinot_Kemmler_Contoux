<?php
/**
 * File:  index.php
 * description: fichier index projet wishlist
 *
 * @author: contoux
 * @author: tisserant
 * @author: kemmler
 * @author: lopes vaz
 */

session_start();

require_once __DIR__ . '/vendor/autoload.php';

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\App;
use CrazyCharlyDay\dbInit;

require 'vendor/autoload.php';

$app = new App(dbInit::init());

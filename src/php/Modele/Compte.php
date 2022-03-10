<?php
declare(strict_types=1);

namespace custumbox\php\Modele;

use Illuminate\Database\Eloquent\Model;

class Compte extends Model{
    protected $table="utilisateur";
    protected $primary="Login";
    public $timestamps = false;
}
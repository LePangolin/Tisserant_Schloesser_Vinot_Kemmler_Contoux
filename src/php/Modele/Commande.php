<?php
namespace custumbox\Modele;

use Illuminate\Database\Eloquent\Model;

class Commande extends Model{
    protected $table="commande";
    protected $primary="id";
    public $timestamps = false;
}

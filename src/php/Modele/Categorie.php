<?php
namespace custumbox\php\Modele;

use Illuminate\Database\Eloquent\Model;

class Categorie extends Model{
    protected $table="categorie";
    protected $primary="id";
    public $timestamps = false;
}

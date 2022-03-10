<?php
namespace custumbox\Modele;

use Illuminate\Database\Eloquent\Model;

class Categorie extends Model{
    protected $table="categorie";
    protected $primary="id";
    public $timestamps = false;

    public function getProduit(){
        return $this->hasMany(Produit::class,"id");
    }
}

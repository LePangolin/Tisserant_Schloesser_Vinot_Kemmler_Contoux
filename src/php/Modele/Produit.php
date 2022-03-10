<?php
namespace custumbox\php\Modele;

use Illuminate\Database\Eloquent\Model;

class Produit extends Model{
    protected $table="produit";
    protected $primary="id";
    public $timestamps = false;


    public function getCateg(){
        return $this->belongsTo(Categorie::class,"id");
    }
    public function commandes() {
        return $this->belongsToMany(
            'custumbox\php\Modele\Commande',
            'listeCommande',
            'idProduit',
            'idCommande'
        )->withPivot(['qte']);
    }
}
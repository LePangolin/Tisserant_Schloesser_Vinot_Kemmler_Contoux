<?php
namespace custumbox\php\Modele;

use Illuminate\Database\Eloquent\Model;

class Commande extends Model{
    protected $table="commande";
    protected $primary="id";
    public $timestamps = false;

    public function produits() {
        return $this->belongsToMany(
            'custumbox\php\Modele\Produit',
            'listeCommande',
            'idCommande',
            'idProduit'
        )->withPivot(['qte']);
    }
}

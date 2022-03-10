<?php
namespace custumbox\Modele;

use Illuminate\Database\Eloquent\Model;

class Produit extends Model{
    protected $table="produit";
    protected $primary="id";
    public $timestamps = false;


    public function getCateg(){
        return $this->belongsTo(Categorie::class,"id");
    }
}
<?php
declare(strict_types=1);

namespace custumbox\php\Modele;

use Illuminate\Database\Eloquent\Model;

class Categorie extends Model{
    protected $table="categorie";
    protected $primary="id";
    public $timestamps = false;
}

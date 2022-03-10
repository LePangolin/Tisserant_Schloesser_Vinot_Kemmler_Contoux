<?php
namespace custumbox\Modele;

use Illuminate\Database\Eloquent\Model;

class Boite extends Model{
    protected $table="boite";
    protected $primary="id";
    public $timestamps = false;
}

<?php
declare(strict_types=1);

namespace custumbox\php\Modele;

use Illuminate\Database\Eloquent\Model;

class Boite extends Model{
    protected $table="boite";
    protected $primary="id";
    public $timestamps = false;
}

<?php
namespace App\models;
use Illuminate\Database\Eloquent\Model;

class Catalogo extends Model {
	protected $table = 'catalogo';
	public $timestamps = false;
	// Add your validation rules here
	public static $rules = array();

	// Don't forget to fill this array
	protected $fillable = array('codcatalogo','nombre','codigo','descripcion','estado','abreviatura');

}

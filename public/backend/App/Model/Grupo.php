<?php 
	namespace App\Model;

	use App\Model\Model;

	class Grupo extends Model {
		
		private $nome;

		use \Src\Traits\TraitGetAttributesAsArray;

		public function __construct($data = [] ){
			
		}

	}
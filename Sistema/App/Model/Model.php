<?php 
	namespace App\Model;

	abstract class Model{
		
		private $id;

		use \Src\Traits\TraitCrypt;

		public function getId(){ return $this->id; }
		public function setId(int $id){ $this->id = $id; }

	}
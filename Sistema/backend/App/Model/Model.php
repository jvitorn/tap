<?php 
	namespace App\Model;

	use Src\Interfaces\InterfaceModels;

	abstract class Model implements InterfaceModels {
		
		protected $id;
		protected $active;

		protected function __construct($data = []){
			if(isset($data['id'])) $this->setId($data['id']);
			if(isset($data['active'])) $this->setActive($data['active']);
		}

		protected function set($name, $val){ 
			$this->{'set'.ucfirst($name)}($val);
		}

		public function getId(){ return $this->id; }
		public function setId(int $id){ $this->id = $id; }

		public function getActive(){ return $this->active; }
		public function setActive($a){ $this->active = $a; }
	}
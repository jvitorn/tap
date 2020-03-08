<?php 
	namespace App\Model;

	use App\Model\Model;

	class Config extends Model {

		private $name;
		private $value;
		private $type;

		use \Src\Traits\TraitGetAttributesAsArray;

		public function __construct($data = []){
			
			foreach($data as $name => $val){
				
				$setter = 'set'.ucfirst($name);

				if(method_exists($this,$setter)){
					$this->set($setter, $val);	
				}
			}
		}

		public function getName(){ return $this->name; }
		public function setName($name){ $this->name = $name; }

		public function getValue(){ return $this->value; }
		public function setvalue($value){ $this->value = $value; }

		public function get_public_attributes_as_array(){
        	$data = $this->get_attributes_as_array();
        	return $data;
        }
	}
<?php 
	namespace App\Model;

	use App\Model\Model;
	use App\Model\User;

	/**
	 * @var $id : int
	 * @var $active : bool
	 * 
	 * @method getId()
	 * @method setId()
	 * @method getActive()
	 * @method setActive()
	 */
	class Category extends Model {

		private $name;
		private $user;
		private $type;
		private $description;
		private $value;
		private $dt_initial;
		private $dt_final;
		private $is_public;

		use \Src\Traits\TraitGetAttributesAsArray;

		public function __construct(User $user, $data = []){

            $this->setUser($user);
			
			foreach($data as $name => $val){
				$setter = 'set'.ucfirst($name);
				if(method_exists($this,$setter)) $this->set($setter, $val);
			}
		}

		public function getName(){ return $this->name; }
		public function setName($nm){ $this->name = addslashes($nm); }

		public function getUser(){ return $this->user; }
		public function setUser(User $user){ $this->user = $user; }

		public function getType(){ return $this->type; }
		public function setType($tp){ $this->type = $tp; }

		public function getDescription(){ return $this->description; }
		public function setDescription($ds){ $this->description = $ds; }

		public function getValue(){ return $this->value; }
		public function setValue($vl){ $this->value = $vl; }

		public function getDt_initial(){ return $this->dt_initial; }
		public function setDt_initial($dt){ $this->dt_initial = $dt; }

		public function getDt_final(){ return $this->dt_final; }
		public function setDt_final($dt){ $this->dt_final = $dt; }

		public function getIs_public(){ return $this->is_public; }
		public function setIs_public($public){ $this->is_public = $public; }

		public function getPublicColumns(){
            return ('id, name, description, value, type,active, dt_initial, dt_final');
        }
	}
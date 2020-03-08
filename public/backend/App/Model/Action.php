<?php 
	namespace App\Model;

	use App\Model\Model;
	use App\Model\User;
	use App\Model\Category;

	/**
	 * @var $id : int
	 * @var $active : bool
	 * 
	 * @method getId()
	 * @method setId()
	 * @method getActive()
	 * @method setActive()
	 */
	class Action extends Model {

		private $user;			// id User
		private $category;		// id Category
		private $name;
		private $description;
		private $indice;
		private $created_at;
		private $updated_at;
		private $repeats;
		private $active_days;
		private $active_hours;
		private $dthr_initial;
		private $dthr_final;
		private $value;

		public function __construct($user_id, $category_id, $data = []){

			$this->user 	= $user_id;
			$this->category = $category_id;

			foreach($data as $name => $val){
				$setter = 'set'.ucfirst($name);
				if(method_exists($this,$setter)) $this->set($setter, $val);
			}
		}

		use \Src\Traits\TraitGetAttributesAsArray;

		public function getUser(){ return $this->user; }

		public function getCategory(){ return $this->category; }

		public function getName(){ return $this->name; }
		public function setName($nm){ $this->name = $nm; }

		public function getDescription(){ return $this->description; }
		public function setDescription($ds){ $this->description = $ds; }

		public function getIndice(){ return $this->indice; }
		public function setIndice($i){ $this->indice = $i; }

		public function getCreated_at(){ return $this->created_at; }
		public function setCreated_at($dt){ $this->created_at = $dt; }

		public function getUpdated_at(){ return $this->updated_at; }
		public function setUpdated_at($dt){ $this->updated_at = $dt; }

		public function getRepeats(){ return $this->repeats; }
		public function setRepeats($r){ $this->repeats = $r; }

		public function getActive_days(){ return $this->active_days; }
		public function setActive_days($ad){ $this->active_days = $ad; }

		public function getActive_hours(){ return $this->active_hours; }
		public function setActive_hours($ah){ $this->active_hours = $ah; }

		public function getDthr_initial(){ return $this->dt_initial; }
		public function setDthr_initial($dt){ $this->dt_initial = $dt; }

		public function getDthr_final(){ return $this->dt_final; }
		public function setDthr_final($dt){ $this->dt_final = $dt; }

		public function getValue(){ return $this->value; }
		public function setValue($vl){ $this->value = $vl; }

		public function get_public_attributes_as_array(){

        }
	}
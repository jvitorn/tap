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

		private $user;			// obj User
		private $category;		// obj Category
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
		public function setName($nm){ $this->name = addslashes($nm); }

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

		public function get_public_fields(){
            $data = $this->getAttributesAsArray();

            unset($data['password']);
            unset($data['created_at']);
            unset($data['updated_at']);
            unset($data['cd_recovery_pw']);
            unset($data['dthr_request_recovery_pw']);

            return $data;
        }

        public function fill_editable_fields($array){

        }

        public function verify_required_fields($data = []){
            $error = "";
            // if(!isset($data['name'])) $error     .= 'O campo name deve ser informado';
            // if(!isset($data['email'])) $error    .= 'O campo email deve ser informado';
            // if(!isset($data['password'])) $error .= 'O campo password deve ser informado';

            return $error;
        }

        public function fill_required_fields($data = []){
            
            $res = $this->verify_required_fields($data);
            
            if($res == ''){
                // $this->setName($data['name']);
                // $this->setEmail($data['email']);
                // $this->setPassword($data['password']);
                // $this->setType('user');
                // $this->setActive('0');
                // $this->setCreated_at(date('Y-m-d h:i:s'));
                // $this->setAuth($data['auth']);
            }
            
            return $res;
        }
	}
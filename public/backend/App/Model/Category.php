<?php 
	namespace App\Model;

	use App\Model\Model;
	use App\Model\Action;

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

		private $actions = [];

		use \Src\Traits\TraitGetAttributesAsArray;

		public function __construct(int $user_id, $data = []){

            $this->user = $user_id;
			
			foreach($data as $name => $val){
				$setter = 'set'.ucfirst($name);
				if(method_exists($this,$setter)) $this->set($setter, $val);
			}
		}

		public function getName(){ return $this->name; }
		public function setName($nm){ $this->name = addslashes($nm); }

		public function getUser(){ return $this->user; }

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

		public function getActions(){
			return $this->actions;
		}

		public function addAction(Action $action){
			$this->actions[] = $action;
		}

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
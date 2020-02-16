<?php 
	namespace App\Model;

	use App\Model\Model;

	/**
	 * @var $id : int
	 * @var $active : bool
	 * 
	 * @method getId()
	 * @method setId()
	 * @method getActive()
	 * @method setActive()
	 */
	class User extends Model {

		private $name;
		private $email;
		private $password;
		private $type;
		private $auth;
		private $cd_recovery_pw;
		private $dthr_request_recovery_pw;
		private $created_at;
		private $updated_at;
		private $height;
		private $weight;
		private $gender;
		private $dt_birth;
		private $menu_config;

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
		public function setName($name){ 
            $this->name = addslashes($name); 
        }

		public function getEmail(){ return $this->email; }
		public function setEmail($email){ 
            $this->email = addslashes($email);
        }

		public function getPassword(){ return $this->password; }
		public function setPassword($pw){ 
			if(!empty($pw)){
				$this->password = md5("6yr".$pw."(8ng#21");
			}
		}

		public function getType(){ return $this->type; }
		public function setType($type){ $this->type = $type; }

		public function getAuth(){ return $this->auth; }
		public function setAuth($auth){ $this->auth = $auth; }

		public function getCd_recovery_pw(){ return $this->cd_recovery_pw; }
		public function setCd_recovery_pw($rpw){ $this->cd_recovery_pw = $rpw; }

		public function getDthr_request_recovery_pw(){ return $this->dthr_request_recovery_pw; }
		public function setDthr_request_recovery_pw($dthr){
			$this->dthr_request_recovery_pw = $dthr;
		}

		public function getCreated_at(){ return $this->created_at; }
		public function setCreated_at($date){ $this->created_at = $date; }

		public function getUpdated_at(){ return $this->updated_at; }
		public function setUpdated_at($date){ $this->updated_at = $date; }

		public function getHeight(){ return $this->height; }
		public function setHeight($h){  $this->height = str_replace(',','.', $h); }

		public function getWeight(){ return $this->weight; }
		public function setWeight($w){ $this->weight = str_replace(',','.', $w); }

		public function getGender(){ return $this->gender; }
		public function setGender($g){ $this->gender = $g; }

		public function getDt_birth(){ return $this->dt_birth; }
		public function setDt_birth($birth){ $this->dt_birth = $birth; }

		public function getMenu_config(){ return $this->menu_config; }
        public function setMenu_config($config){ $this->menu_config = $config; }
        
        public function getPublicColumns(){
            return ('id, name, email, type,height, weight, gender, active, dt_birth');
        }

        public function getPublicColumnsAsArray(){
            $data = $this->getAttributesAsArray();

            unset($data['password']);
            unset($data['created_at']);
            unset($data['updated_at']);
            unset($data['cd_recovery_pw']);
            unset($data['dthr_request_recovery_pw']);

            return $data;
        }

        public function editPublicColumns($data){
            if(isset($data['name'])) $this->setName($data['name']);
            if(isset($data['password'])) $this->setPassword($data['password']);
            if(isset($data['gender'])) $this->setGender($data['gender']);
            if(isset($data['height'])) $this->setHeight($data['height']);
            if(isset($data['weight'])) $this->setWeight($data['weight']);
            
            $this->setUpdated_at(date('Y-m-d h:i:s'));
        }

        private function verify_required_fields($data = []){
            $error = "";
            if(!isset($data['name'])) $error     .= 'O campo name deve ser informado';
            if(!isset($data['email'])) $error    .= 'O campo email deve ser informado';
            if(!isset($data['password'])) $error .= 'O campo password deve ser informado';

            return $error;
        }

        public function fill_required_fields($data = []){
            
            $res = $this->verify_required_fields($data);
            
            if($res == ''){
                $this->setName($data['name']);
                $this->setEmail($data['email']);
                $this->setPassword($data['password']);
                $this->setType('user');
                $this->setActive(1);
                $this->setCreated_at(date('Y-m-d h:i:s'));
            }
            
            return $res;
        }
	}

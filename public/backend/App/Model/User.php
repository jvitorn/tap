<?php 
	namespace App\Model;

	use App\Model\Model;
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
	class User extends Model {

		private $name;
		private $email;
		private $password;
		private $type;
		private $auth;
		private $dthr_request_recovery_pw;
		private $created_at;
		private $updated_at;
		private $height;
		private $weight;
		private $gender;
		private $dt_birth;

		private $categories = [];

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
		public function setAuth($auth){  
			if(!empty($auth)){
				$this->auth = md5($auth);
			}else{
				$this->auth = '';
			}
		}

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

		public function getCategories(){
			return $this->categories;
		}
		public function addCategory(Category $cat){
			$this->categories[] = $cat;
		}

		public function generateAuthCode(){
			// $code = substr(md5(date('idmyhs')),0,6);]
			$code = "123456";
           	$this->setAuth($code);
           	return $code;
        }

        public function get_public_attributes_as_array(){
        	$data = $this->get_attributes_as_array();

        	unset($data['password']);
        	unset($data['auth']);
        	unset($data['dthr_request_recovery_pw']);

        	return $data;
        }
	}

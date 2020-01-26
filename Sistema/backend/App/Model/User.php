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
	 * 
	 */
	class User extends Model {

		private $name;
		private $email;
		private $password;
		private $type;
		private $auth;
		private $cd_recovery_pw;
		private $dthr_recovery_pw_request;
		private $created_at;
		private $updated_at;
		private $height;
		private $weight;
		private $gender;
		private $dt_birth;
		private $menu_config;

		use \Src\Traits\TraitGetAttributesAsArray;

		public function __construct($data = []){
			foreach($data as $name => $val) $this->set($name, $val);
		}

		public function getName(){ return $this->name; }
		public function setName($name){ $this->name = $name; }

		public function getEmail(){ return $this->email; }
		public function setEmail($email){ $this->email = $email; }

		public function getPassword(){ return $this->password; }
		public function setPassword($pw){ if(!empty($pw)) $this->password = md5($pw); }

		public function getType(){ return $this->type; }
		public function setType($type){ $this->type = $type; }

		public function getAuth(){ return $this->auth; }
		public function setAuth($auth){ $this->auth = $auth; }

		public function getCd_recovery_pw(){ return $this->cd_recovery_pw; }
		public function setCd_recovery_pw($rpw){ $this->cd_recovery_pw = $rpw; }

		public function getDthr_recovery_pw_request(){ return $this->dthr_recovery_pw_request; }
		public function setDthr_recovery_pw_request($dthr){
			$this->dthr_recovery_pw_request = $dthr;
		}

		public function getCreated_at(){ return $this->created_at; }
		public function setCreated_at($date){ $this->created_at = $date; }

		public function getUpdated_at(){ return $this->updated_at; }
		public function setUpdated_at($date){ $this->updated_at = $date; }

		public function getHeight(){ return $this->height; }
		public function setHeight($h){ $this->height = $h; }

		public function getWeight(){ return $this->weight; }
		public function setWeight($w){ $this->weight = $w; }

		public function getGender(){ return $this->gender; }
		public function setGender($g){ $this->gender = $g; }

		public function getDt_birth(){ return $this->dt_birth; }
		public function setDt_birth($birth){ $this->dt_birth = $dt; }

		public function getMenu_config(){ return $this->menu_config; }
		public function setMenu_config($config){ $this->menu_config = $config; }
	}

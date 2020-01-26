<?php 
	namespace App\Model;

	use App\Model\Model;
	use App\Model\User;

	/**
	 * @var $id : int
	 * @var $active : bool
	 * 
	 * @method set($attributeName, $value)
	 * @method getId()
	 * @method setId(int $id)
	 * @method getActive()
	 * @method setActive(string $status)
	 */
	class Financial extends Model {

		use \Src\Traits\TraitGetAttributesAsArray;

		private $user;
		private $name;

		public function __construct($data = []){
			foreach($data as $name => $val) $this->set($name, $val);
		}

		public function getUser(){ return $this->user; }
		public function setUser(User $user){ $this->user = $user; }

		public function getName(){ return $this->name; }
		public function setName($name){ $this->name = $name; }
	}
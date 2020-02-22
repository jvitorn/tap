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
	class Email extends Model {

		private $title;
		private $content;
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

		public function getTitle(){ return $this->title; }
		public function setTitle($title){ 
            $this->title = addslashes($title); 
        }

		public function getContent(){ return $this->content; }
		public function setContent($content){ 
            $this->content = addslashes($content);
        }

		public function getType(){ return $this->type; }
		public function setType($type){ $this->type = $type; }

        public function getPublicColumns(){
            return ('id, title, content, type');
        }
	}

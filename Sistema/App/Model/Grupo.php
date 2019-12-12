<?php 
	namespace App\Model;

	use App\Model\Model;

	class Grupo extends Model {
		private $nome;
		private $status;
		private $permissao;

		public function __construct($data = null ){
			if(isset($data['id']) && is_numeric($data['id'])){
				$this->setId($data['id']);
			}
			isset($data['nome'])?$this->setNome($data['nome']):false;
			isset($data['status'])?$this->setStatus($data['status']):false;
			isset($data['permissao'])?$this->setPermissao($data['permissao']):false;
		}

		public function getNome(){ return $this->nome; }
		public function setNome($nome){ $this->nome = $nome; }

		public function getStatus(){ return $this->status; }
		public function setStatus($status){ $this->status = $status; }

		public function getPermissao(){ return $this->permissao; }
		public function setPermissao($p){ $this->permissao = $p; }
	}
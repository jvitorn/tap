<?php 
	namespace App\Model;

	use App\Model\Model;
	use App\Model\Grupo;

	use Src\Interfaces\InterfaceModel;

	class User extends Model implements InterfaceModel{

		//chaves estrangeiras
		private $grupo;

		private $nome;
		private $email;
		private $login;
		private $senha;
		private $status;
		private $dtLogin;
		private $hash;
		private $updated_at;
		private $created_at;

		public function __construct($data = null){
			if(is_array($data)){
				isset($data['id'])? $this->setId($data['id']): false;
				isset($data['grupo'])? $this->setGrupo($data['grupo']): false;
				isset($data['status'])? $this->setStatus($data['status']): false;
				isset($data['nome'])? $this->setNome($data['nome']): false;
				isset($data['email'])? $this->setEmail($data['email']): false;
				isset($data['login'])? $this->setLogin($data['login']): false;
				isset($data['senha'])? $this->setSenha($data['senha']): false;
				isset($data['dtLogin'])? $this->setDtLogin($data['dtLogin']): false;
				isset($data['hash'])? $this->setHash($data['hash']): false;
			}
		}

		public function getGrupo(){ return $this->grupo; }
		public function setGrupo(Grupo $grupo){ $this->grupo = $grupo; }

		public function getNome(){ return $this->nome; }
		public function setNome($nome){ $this->nome = $nome; }

		public function getEmail(){ return $this->email; }
		public function setEmail($email){ $this->email = $email; }

		public function getLogin(){ return $this->login; }
		public function setLogin($login){ $this->login = $login; }

		public function getSenha(){ return $this->senha; }
		public function setSenha($senha){ if(!empty($senha)) $this->senha = md5($senha); }

		public function getStatus(){ return $this->status; }
		public function setStatus($status){ $this->status = $status; }

		public function getDtLogin(){ return $this->dtLogin; }
		public function setDtLogin($dt){ $this->dtLogin = $dt; }

		public function getHash(){ return $this->hash; }
		public function setHash($hash){ $this->hash = $hash; }

		public function getIP(){ return $this->ip; }
		public function setIP($ip){ $this->ip = $ip; }

		//controle de criacao e atualizacao da conta
		public function getUpdatedAt(){ return $this->updated_at; }
		public function setUpdatedAt($date){ $this->updated_at = $date; }

		public function getCreatedAt(){ return $this->created_at; }
		public function setCreatedAt($date){ $this->created_at = $date; }

		public function getVars(){
			return get_object_vars($this);
		}
	}

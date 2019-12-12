<?php 
	namespace App\DAO;

	use App\DAO\DAO;
	use App\Model\User;

	class UserDAO extends DAO {

		static public function create(User $user){

			$cols  = "cd_usuario, id_grupo, nm_usuario, nm_email, nm_login, cd_senha,
					 ic_status,criado_as";

			$vals  = "NULL";
			$vals .= "'".$user->getGrupo()->getId()."'";
			$vals .= ",'".$user->getNome()."'";
			$vals .= ",'".$user->getEmail()."'";
			$vals .= ",'".$user->getLogin()."'";
			$vals .= ",'".$user->getSenha()."'";
			$vals .= ",'".$user->getStatus()."'";
			$vals .= ",'".$user->getCreatedAt()."'";

			return parent::insert('usuario',$cols,$vals);
		}

		static public function find(User $user = null, $order = null, $limit = null){

			$cols  = "cd_usuario, id_grupo, nm_usuario, nm_email, nm_login, cd_senha,
					  cd_hash, ic_status,criado_as";

			$where = "cd_usuario > 0 ";

			if(!empty($user)){
				
				if( !empty( $user->getId() ) )
					$where .= " AND cd_usuario = '".$user->getId()."' ";

				if(!empty( $user->getId()->getGrupo() ) )
					$where .= " AND id_grupo = '".$user->getGrupo()->getId()."' ";

				if( !empty( $user->getEmail() ) )
					$where .= " AND nm_email = '".$user->getEmail()."' ";

				if( !empty( $user->getLogin() ) )
					$where .= " AND nm_login = '".$user->getLogin()."' ";

				if( !empty( $user->getSenha() ) )
					$where .= " AND cd_senha = '".$user->getSenha()."' ";

				if( !empty( $user->getStatus() ) )
					$where .= " AND ic_status = '".$user->getStatus()."' ";

				if( !empty( $user->getDtLogin() ) )
					$where .= " AND dt_login = '".$user->getDtLogin()."' ";

				if( !empty( $user->getHash() ) )
					$where .= " AND cd_hash = '".$user->getHash()."' ";

				if( !empty( $user->getCriadoAs() ) )
					$where .= " AND criado_as = '".$user->getCriadoAs()."' ";
				
				if( !empty( $user->getAtualizadoAs() ) )
					$where .= " AND atualizado_as = '".$user->getAtualizadoAs()."' ";
			}
			//retorna um array com os usuarios selecionados
			return parent::Select('usuario',$cols,$where,$order,$limit);
		}

		static public function Login(User $user){

			$order = null;
			$limit = 1;

			$res = self::find($user,$order,$limit);

			if(is_array($res)){

				$res = $res[0];
				$pt1 = md5( date('Yidmhsi') );
				$pt2 = md5( $res['nm_email']. $pt1 );
				$auth = $pt1.".".$pt2;

				$user->setAuth($auth);
				$user->setId($res['cd_usuario']);

				if(self::edit($user)) return $user;
			}
		}

		//valida se a hash da session existe no DB
		static public function verifyAuth(User $user){
			if( is_array( self::find($user) ) ) return true;
		}

		static public function edit(User $user){

			$update = " cd_usuario = {$user->getId()} ";

			if(!empty($user->getGrupo()))
				$update .= ", id_grupo = '".$user->getGrupo()->getId()."' ";

			if(!empty($user->getStatus()))
				$update .= ", ic_status = '".$user->getStatus()."' ";

			if(!empty($user->getNome()))
				$update .= ", nm_usuario = '".$user->getNome()."' ";

			if(!empty($user->getEmail()))
				$update .= ", nm_email = '".$user->getEmail()."' ";

			if(!empty($user->getLogin()))
				$update .= ", nm_login = '".$user->getLogin()."' ";

			if(!empty($user->getSenha()))
				$update .= ", cd_senha = '".$user->getSenha()."' ";

			if(!empty($user->getDtLogin()))
				$update .= ", dt_login = '".$user->getDtLogin()."' ";

			if(!empty($user->getHash()))
				$update .= ", cd_hash = '".$user->getHash()."' ";

			if(!empty($user->getAtualizadoAs()))
				$update .= ", atualizado_as = '".$user->getAtualizadoAs()."' ";

			$where = " cd_usuario = {$user->getId()} ";

			return parent::Update('usuario',$update,$where);
		}

		static public function remove(User $user){

			if(!empty($user->getId())){
				$where = "cd_usuario = '".$user->getId()."' ";
				return parent::Delete('usuario',$where);	
			}
		}
	}
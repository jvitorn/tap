<?php 
	namespace App\DAO;

	use App\DAO\DAO;
	use App\Model\User;

	/**
	 * @method Insert()
	 * @method Select()
	 * @method Update()
	 * @method Delete()
	 * @method query($sql)
	 * @method array_to_sql_create($data)
	 * @method array_to_sql_update($data)
	 * @method array_to_sql_where($data)
	 */
	class UserDAO extends DAO {

		static public function create(User $user){
			parent::array_to_sql_create($user->getAttributesAsArray());
			return parent::Insert('user');
		}

		static public function find(User $user, $data = []){

			parent::array_to_sql_where('user',$user->getAttributesAsArray());
			parent::QueryParams($data);
			$data = parent::Select('user');
			
			if(is_array($data['res'])){
				$users = [];
				foreach($data['res'] as $key => $user){ $users[] = new User($user); }
				$data['res'] = $users;
			}

			return $data;
		}

		static public function edit(User $user){
			$update = parent::array_to_sql_update('user',$user->getAttributesAsArray());
			$where 	= " id = {$user->getId()} ";
			return parent::Update('user',$update,$where);
		}

		static public function remove(User $user){
			if(!empty($user->getId())){
				$where = "id = '".$user->getId()."' ";
				return parent::Delete('user',$where);	
			}
		}

		static public function Login(User $user){
			if(!empty($user->getEmail()) && !empty($user->getPassword())){
				$u = self::find($user);

				if(is_array($u)){
					$u = $u[0];
					$user->setAuth(md5($u['email'].date('Yidmhsi')));
					$user->setId($u['id']);
					$update = "auth = '{$user->getAuth()}'";
					$where 	= "user.id = ".$user->getid(); 
					if(parent::Update('user',$update,$where)) return $user;
				}
			}
		}

		/* verifica se a hash da session existe no DB */
		static public function verifyAuth(User $user){
			if(!empty($user->getAuth()) && !empty($user->getId())){
				if(is_array(self::find($user))) return true;
			}
		}
	}
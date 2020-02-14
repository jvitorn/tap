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
            if(is_numeric($user->getId())){
                parent::array_to_sql_update($user->getAttributesAsArray());
                parent::array_to_sql_where('user',['id' => $user->getId() ]);
			    return parent::Update('user');
            }
		}

		static public function remove(User $user){
			if(!empty($user->getId())){
                parent::array_to_sql_where('user',['id' => $user->getId()]);
				return parent::Delete('user');
			}
		}

		static public function Login(User $user){
			if(!empty($user->getEmail()) && !empty($user->getPassword())){
                $user->setActive(1);

                $data = ['cols' => 'id,name,email,gender, type, height, weight, menu_config'];
                $u = self::find($user,$data);

				if(is_array($u) && empty($u['error'])){
                    $user = $u['res'][0];
                    $user->setAuth(md5($user->getEmail().date('Yidmhsi')));
                    if(self::edit($user)){ return $user; }
				}
			}
		}
	}
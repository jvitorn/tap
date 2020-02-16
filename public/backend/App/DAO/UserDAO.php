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
            /* verifica se o email ja está cadastrado */
            $res = self::find(new User(['email' => $user->getEmail()]));

            if(is_array($res) && count($res) > 0){
                return 'Este Email ja está cadastrado.';
            }else{
                parent::array_to_sql_create($user->getAttributesAsArray());
			    return parent::Insert('user');
            }
		}

		static public function find(User $user){
            parent::array_to_sql_where('user',$user->getAttributesAsArray());
            self::columns($user->getPublicColumns());
			return parent::Select('user');
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
                
                $data = self::find($user);

                if(is_array($data) && count($data) > 0){
                    parent::array_to_sql_where('user',['id' => $user->getId()]);
				    return parent::Delete('user');
                }else{
                    return 'Este usuário não existe';
                }
                
			}
		}

		static public function Login(User $user){
			if(!empty($user->getEmail()) && !empty($user->getPassword())){
                $user->setActive(1);

                $res = self::find($user);

				if(is_array($res) && count($res) > 0){
                    $user = new User($res[0]);
                    $user->setAuth(md5($res[0]['id'].date('hIymsid')));
                    
                    $data = $user->getPublicColumnsAsArray();
                    return $data;
				}
			}
		}
	}
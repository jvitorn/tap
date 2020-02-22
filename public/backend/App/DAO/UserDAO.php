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

                    parent::array_to_sql_update(['is_logged' => 1]);
                    parent::array_to_sql_where('user',['id' => $user->getId() ]);
                    parent::update('user');
                    
                    $data = $user->getPublicColumnsAsArray();
                    $data['jti'] = md5($res[0]['id'].date('hIymsid'));
                    return $data;
				}
			}
        }

        static public function is_logged(User $user){
            $where = ['id' => $user->getId(),'is_logged' => 1];
            self::columns($user->getPublicColumns());
            parent::array_to_sql_where('user',$where);
            $res = parent::Select('user');
            if(is_array($res) && count($res) > 0) return true;
        }
        
        static public function logout(User $user){
            parent::array_to_sql_update(['is_logged' => "/0/"]);
            parent::array_to_sql_where('user',['id' => $user->getId() ]);
            return parent::update('user');
        }

        static public function active(User $user){
            $return;
            $res = self::find($user);
            
            if(is_array($res) && count($res) > 0){
                
                $user->setActive(1);
                $user->setAuth('');
                $user->setId($res[0]['id']);
                
                if(self::edit($user)){
                    $return = 'success';
                }else{
                    $return = 'não foi possivel ativar o usuário';
                }

            }else{
                $return = 'Código ou id inválidos';
            }

            return $return;
        }

        static public function reset_password_request(User $user, $code){
            
            $return;
            $user->setActive('1');
            $res = self::find($user);
   
            if(is_array($res) && count($res) > 0){
                $user->setAuth($code);
                $user->setId($res[0]['id']);
                if(self::edit($user)){ return $res[0]; }
            }
        }

        static public function verify_reset_password_code(User $user){
            $res = self::find($user);

            if(is_array($res) && count($res) > 0){
                return $res[0]['id'];
            }
        }

        static public function reset_password(User $user, $pw){
            $res = self::find($user);

            if(is_array($res) && count($res) > 0){
                $user->setAuth('');
                $user->setPassword($pw);
                $user->setId($res[0]['id']);
                return self::edit($user);
            }
        }
	}
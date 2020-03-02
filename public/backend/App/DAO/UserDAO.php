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
			    return parent::base_create('user', $user);
            }
		}

		static public function find(User $user){
            return parent::base_find('user',$user);
		}

		static public function edit(User $user){
            if(is_numeric($user->getId())){
			    return parent::base_edit('user',$user);
            }
		}

		static public function remove(User $user){

            if(!is_numeric($user->getId())) return ;
                
            $data = self::find($user);

            if(is_array($data) && count($data) > 0){
			    return parent::base_remove('user',$user);
            }else{
                return 'Este usuário não existe';
            }
        }

		static public function login(User $user){
			if(!empty($user->getEmail()) && !empty($user->getPassword())){
                $user->setActive(1);

                $res = self::find($user);

				if(is_array($res) && count($res) > 0){
                    $res = $res[0];
                    $user->setId($res['user_id']);
                    $user->setName($res['user_name']);
                    $user->setWeight($res['user_weight']);
                    $user->setHeight($res['user_height']);
                    $user->setType($res['user_type']);

                    self::edit(new User(['is_logged' => 1,'id' => $user->getId()]) );
                    
                    $data = $user->getPublicColumnsAsArray();
                    $data['jti'] = md5($res['user_id'].date('hIymsid'));
                    return $data;
				}
			}
        }

        static public function is_logged(User $user){

            $res = self::find(new User(
                [
                    'id'        => $user->getId(),
                    'active'    => '1',
                    'is_logged' => '1'
                ]
            ));

            if(is_array($res) && count($res) > 0) return true;
        }
        
        static public function logout(User $user){
            return self::edit(new User(['is_logged' => '0', 'id' => $user->getId()]));
        }

        static public function active(User $user){
            $return;
            $res = self::find($user);
            
            if(is_array($res) && count($res) > 0){
                
                $user->setActive(1);
                $user->setAuth('');
                $user->setId($res[0]['user_id']);
                
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
            
            $user->setActive('1');
            $res = self::find($user);
   
            if(is_array($res) && count($res) > 0){
                $user->setAuth($code);
                $user->setId($res[0]['user_id']);
                if(self::edit($user)){ return $res[0]; }
            }
        }

        static public function verify_reset_password_code(User $user){
            $res = self::find($user);

            if(is_array($res) && count($res) > 0){
                return $res[0]['user_id'];
            }
        }

        static public function reset_password(User $user, $pw){
            $res = self::find($user);

            if(is_array($res) && count($res) > 0){
                $user->setAuth('');
                $user->setPassword($pw);
                $user->setId($res[0]['user_id']);
                return self::edit($user);
            }
        }
	}
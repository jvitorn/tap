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

            $ret = "";

            /* verifica se os campos requeridos estão preenchidos. */
            if(empty($user->getName())) $ret .= 'O campo name deve ser informado. ';
            if(empty($user->getEmail())) $ret .= 'O campo email deve ser informado. ';
            if(empty($user->getPassword())) $ret .= 'O campo password deve ser informado. ';
            if(empty($user->getAuth())) $ret .= "O campo AUTH está vazio. ";

            /* se algum dos campos estiver vazio, retorna uma mensagem de erro. */
            if($ret != "") return $ret;

            /* verifica se o email ja está cadastrado */
            $res = self::find(new User(['email' => $user->getEmail()]));

            /* se encontrar um usuário com este email, retorna uma mensagem de erro. */
            if(is_array($res) && count($res) > 0){
                return 'Este Email ja está cadastrado.';
            }else{

                /* se nao encontrar, cadastra-o. */
                $user->setActive("0");
                $user->setCreated_at(date('Y-m-d h:i:s'));

			    return parent::base_create('user', $user);
            }
		}

		static public function find(User $user){
            /* busca por usuários. */
            return parent::base_find('user',$user);
		}

		static public function edit(User $user){

            /* se o campo ID nao estiver vazio... */
            if(!empty($user->getId())){
                
                /* define como NULL os campos que não são editaveis. */
                $user->setCreated_at(null);
                $user->setEmail(null);
                $user->setType(null);

                /*atualiza o usuário. */
			    return parent::base_edit('user',$user);
            }
		}

		static public function remove(User $user,$is_admin = 0){
            $ret = "";

            /* se o ID do usuário nao for informado, retorna uma mensagem de erro. */
            if(empty($user->getId())) $ret .= "O ID deve ser informado!";
            
            if(!$is_admin){
                if(empty($user->getAuth())) $ret .= "O Código deve ser informado!";    
            }
            
            if($ret != "") return $ret;
            
            /* busca o usuário no DB. */
            $b_user = new User();
            $b_user->setId($user->getId());
            
            if(!empty($user->getEmail())){
                $b_user->setEmail($user->getEmail());
            }
            
            $data = self::find($b_user);

            /* se encontrar o usuário... */
            if(is_array($data) && count($data) > 0){

                $data = $data[0];

                /* se o código enviado pelo usuário não for o mesmo do DB. */
                if(!$is_admin){
                    if($data['user_auth'] != $user->getAuth()) return "Código inválido. ";    
                }

                /* remove o usuário do DB. */
			    if(parent::base_remove('user',$user)){

                    /* se remover, retorna sucesso. */
                    return "success";

                }else{

                    /* se nao remover, retorna uma mensagem de erro. */
                    return "Não foi possivel excluir este usuário. ";

                }

            }else{

                /* se nao encontrar o usuário, retorna uma mensagem de erro. */
                return 'Este usuário não existe';

            }
        }

		static public function login(User $user){
            
            $ret = "";

            /* verifica se os campos requeridos estão preenchidos. */
			if(empty($user->getEmail())) $ret .= "O Email deve ser informado";
            if(empty($user->getPassword())) $ret .= "O Password deve ser informado";

            /* se algum dos campos estiver vazio, retorna uma mensagem de erro. */
            if($ret != "") return $ret;

            /* busca o usuário no DB. */
            $user->setActive(1);
            $res = self::find($user);

            /* se encontrar o usuário... */
			if(is_array($res) && count($res) > 0){
                $res = $res[0];

                /* define atributos do objeto. */
                $user->setId($res['user_id']);
                $user->setName($res['user_name']);
                $user->setWeight($res['user_weight']);
                $user->setHeight($res['user_height']);
                $user->setType($res['user_type']);

                $data['id']     = $res['user_id'];
                $data['name']   = $res['user_name'];
                $data['email']  = $res['user_email'];
                $data['type']   = $res['user_type'];
                
                /* retorna o array com as informações do usuário. */
                return $data;
			}
        }
        
        static public function active(User $user){

            $ret = "";
             /* verifica se os campos requeridos estão preenchidos. */
            if(empty($user->getAuth())) $ret .= "O código deve ser informado. ";
            if(empty($user->getEmail())) $ret .= "O Email do usuário deve ser informado. ";

            /* se algum dos campos estiver vazio, retorna uma mensagem de erro. */
            if($ret != "") return $ret;

            /* busca o usuário no DB. */
            $res = self::find($user);
            
            /* se encontrar o usuário... */
            if(is_array($res) && count($res) > 0){
                
                /* define os atributos do obbjeto antes de atualizar. */
                $user->setActive(1);
                $user->setAuth();
                $user->setId($res[0]['user_id']);
                
                /* atualiza os dados do usuário. */
                if(self::edit($user)){

                    /* se atualizar, retorna mensagem de sucesso. */
                    $return = 'success';

                }else{

                    /* se nao atualizar, retorna mensagem de erro. */
                    $return = 'não foi possivel ativar o usuário';

                }

            }else{

                /* se nao encontrar o usuário, retorna mensagem de erro. */
                $return = 'Código ou id inválidos';

            }

            return $return;
        }

        static public function reset_password_request(User $user){
           
            /* procura um usuário com este email e que esteja ativo */ 
            $res = self::find(new User(
                [
                    'email'     => $user->getEmail(),
                    'active'    => 1
                ]
            ));
    
            /* se encontrar o usuário... */
            if(is_array($res) && count($res) > 0){
                
                /* adiciona o ID do usuário selecionado. */
                $user->setId($res[0]['user_id']);

                /* atualiza o usuário, adicionando o código de autenticação. */
                if(self::edit($user)){ return $res[0]; }
            }
        }

        static public function verify_reset_password_code(User $user){
            
            $ret = "";

            /* verifica se o campo do código está vazio. */
            if(empty($user->getAuth())) return "O Código precisa ser informado!";
            
            /* procura um usuário com o código de autenticação. */
            $res = self::find($user);

            /* se enconrar, retorna o ID do usuário. */
            if(is_array($res) && count($res) > 0) return $res[0]['user_id'];
        }

        static public function reset_password(User $user){
            
            $ret = "";

            /* verifica se os campos requeridos estão vazios. */
            if(empty($user->getAuth())) $ret .= "O Código deve ser informado. ";
            if(empty($user->getId())) $ret .= "O ID do usuário deve ser informado. ";
            if(empty($user->getPassword())) $ret .= "O Password deve ser informado. ";

            /* se algum dos campos estiver vazio, retorna a mensagem de erro. */
            if($ret != "") return $ret;

            /* busca o usuário no DB. */
            $res = self::find(new User(['id' => $user->getId()]));

            /* se encontrar o usuário... */
            if(is_array($res) && count($res) > 0){
                
                /* se os códigos forem compativeis... */
                if($res[0]['user_auth'] == $user->getAuth()){
                    
                    $user->setAuth('');
                
                    /* atualiza o usuário, redefinindo a senha. */
                    if(self::edit($user)) return 'success';

                }else{

                    /* se os códigos nao forem compativeis, retorna uma mensagem de erro. */
                    return "Código inválido. ";

                }
                
            }else{

                /* se nao encontrar o usuário retorna uma mensagem de erro. */
                return "Usuário nao encontrado. ";

            }
        }
	}
<?php 
	namespace App\DAO;

	use App\DAO\DAO;
	use App\Model\Email;

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
	class EmailDAO extends DAO {

		static public function create(Email $email){
            /* verifica se o tipo de email ja está cadastrado */
            $res = self::find(new Email(['email' => $email->getType()]));

            if(is_array($res) && count($res) > 0){
                return 'Este Email ja está cadastrado.';
            }else{
                parent::array_to_sql_create($email->getAttributesAsArray());
			    return parent::Insert('email');
            }
		}

		static public function find(Email $email){
            parent::array_to_sql_where('email',$email->getAttributesAsArray());
            self::columns($email->getPublicColumns());
			return parent::Select('email');
		}

		static public function edit(Email $email){
            if(is_numeric($email->getId())){
                parent::array_to_sql_update($email->getAttributesAsArray());
                parent::array_to_sql_where('email',['id' => $email->getId() ]);
			    return parent::Update('email');
            }
		}

		static public function remove(Email $email){
            
            if(!empty($email->getId())){
                
                $data = self::find($user);

                if(is_array($data) && count($data) > 0){
                    parent::array_to_sql_where('email',['id' => $email->getId()]);
				    return parent::Delete('email');
                }else{
                    return 'Este email não existe';
                }
                
			}
        }
	}
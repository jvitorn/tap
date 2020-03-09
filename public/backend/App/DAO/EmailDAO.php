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

		static public function find(Email $email){
			return parent::base_find('email', $email);
		}

		static public function edit(Email $email){
            
            if(is_numeric($email->getId())){
			   	if(parent::base_edit('email', $email)){
			   		return 'success';
			   	}else{
			   		return "erro ao tentar atualizar o email";
			   	}
            }else{
            	return "o ID deve ser informado";
            }
		}
	}
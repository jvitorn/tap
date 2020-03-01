<?php 
	namespace App\DAO;

	use App\DAO\DAO;
	use App\Model\User;
    use App\Model\Category;
    use App\Model\Action;

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
	class ActionDAO extends DAO {

		static public function create(Action $action){
            
            if(empty($action->getUser())){
                return 'O usuário deve ser informado!';
            }

            if(empty($action->getCategory())){
                return 'A categoria deve ser informada!';
            }

	        return parent::base_create('action',$action);
		}

		static public function find(Action $action){
			return parent::base_find('action',$action);
		}

		static public function edit(Action $action){

            if(is_numeric($action->getId()) && is_object($action->getUser())){

                $res = self::find(new Action(
                	$action->getUser(),
                	$action->getCategory(),
                	[	'user' => $action->getUser(),
                		'id'=>$action->getId()
                	]
                ));

                if(is_array($res) && count($res) > 0){
                    if( parent::base_edit('action',$action)) return 'success';
                }else{
                    return 'Ação não encontrada';
                }
            }
		}

		static public function remove(Action $action){
            
            if(!empty($action->getId())){
                
                $data = self::find($action);

                if(is_array($data) && count($data) > 0){
				    if(parent::base_remove('action',$action)) return 'success';
                }else{
                    return 'Esta ação não existe';
                }
			}
        }
	}
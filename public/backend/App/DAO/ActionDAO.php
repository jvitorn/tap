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
            parent::array_to_sql_create($action->getAttributesAsArray());
	        return parent::Insert('action');
		}

		static public function find(Action $action){
            parent::array_to_sql_where('action',$action->getAttributesAsArray());
            self::columns($action->getPublicColumns());
			return parent::Select('action');
		}

		static public function edit(Action $action){

            if(is_numeric($action->getId())){

                $res = self::find(new Action(
                    $action->getUser(),
                    $action->getCategory(),
                    [
                        'id'    => $action->getId(),
                    ]
                ));

                if(is_array($res) && count($res) > 0){
                    parent::array_to_sql_update($action->getAttributesAsArray());
                    parent::array_to_sql_where('action',[
                        'user'      => $action->getUser(),
                        'category'  => $action->getCategory(),
                        'id'        => $action->getId(),
                        
                    ]);

                    if( parent::Update('action')){
                        return 'success';
                    }

                }else{
                    return 'Ação não encontrada';
                }
            }
		}

		static public function remove(Action $action){
            
            if(!empty($action->getId())){
                
                $data = self::find($action);

                if(is_array($data) && count($data) > 0){
                    $where = ['id' => $action->getId()];
                    parent::array_to_sql_where('action',$where);
				    if(parent::Delete('action')) return 'success';
                }else{
                    return 'Esta ação não existe';
                }
			}
        }
	}
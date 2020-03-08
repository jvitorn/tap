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
	class CategoryDAO extends DAO {

		static public function create(Category $cat){
            
            /* verifica se ja existe uma cateogiria igual */
            $res = self::find(new Category( $cat->getUser(),
                [
                    'is_public' => $cat->getIs_public(),
                    'name'      => $cat->getName(),
                    'type'      => $cat->getType(),
                ]
            ));

            /* se encontrar, retorna uma mensagem de erro. */
            if(is_array($res) && count($res) > 0){

                return 'Esta categoria ja existe!';

            }else{

                /*se nao encontrar, cadastra no DB. */
		        return parent::base_create('category', $cat);

            }
		}

		static public function find(Category $cat){
                
            parent::columns('action',(
                new Action($cat->getUser(), $cat->getid() )
            )->get_attributes_as_array());

            parent::join("LEFT JOIN action ON action.category = category.id");

            return parent::base_find('category',$cat);
		}

        static public function find_public_categories(Category $cat){

            $action = new Action($cat->getUser(),$cat->getId());

            $fields_category = parent::columns('category', $cat->get_attributes_as_array());

            

            $arrFields = $cat->get_attributes_as_array();
            
            unset($arrFields['user']);

            $query  = "SELECT $fields_category FROM category ";
            $query .= "WHERE ".parent::array_to_sql_where('category',$arrFields);
            $query .= " AND is_public = '1' AND active ='1' ";          
            
            $categories = parent::query($query);
            $fields_action = parent::columns('action', $action->get_attributes_as_array());

            $query  = "SELECT $fields_action FROM action ";
            $query .= "WHERE user = ".$cat->getUser();

            $actions = parent::query($query);

            $res = [];
            $c = 0;

            if(is_array($categories)){
                foreach($categories as $key => $category ){
                    $encontrou = false;
                    
                    if(is_array($actions)){
                        foreach($actions as $k => $action){
                        
                            if($category['category_id'] == $action['action_category']){
                                $res[] = array_merge($action,$category);
                                unset($actions[$k]);
                                $encontrou = true;
                                break;
                            }
                        }
                    }

                    if(!$encontrou){
                        $res[] = $categories[$key];
                    }
                }
            }
            
            if(is_array($res)) return $res;
            return array();
        }

		static public function edit(Category $cat){

            if(empty($cat->getId())) return "O ID da categoria deve ser informado. ";

            $res = null;

            if($cat->getIs_public()){

                $res = self::find_public_categories(new Category($cat->getUser(),[
                    'id'        => $cat->getId(),
                    'is_public' => $cat->getIs_public()
                ]));

            }else{

                $res = self::find(new Category($cat->getUser(),[
                    'id'        => $cat->getId(),
                    'is_public' => $cat->getIs_public()
                ]));

            }
            
            if(is_array($res) && count($res) > 0){
                
                if( parent::base_edit('category', $cat)) return 'success';
            
            }else{

                return 'Categoria não encontrada';
                
            }
		}

		static public function remove(Category $cat){
            
            $ret = "";

            if(!$cat->getIs_public()){
                if(empty($cat->getUser())) return "O ID do usuário deve ser informado. ";
            }

            if(empty($cat->getId())) return "O ID da categoria deve ser informado. ";
            
            if($ret != "") return $ret;
            
            if($cat->getIs_public()){
                $data = self::find_public_categories($cat);
            }else{
                $data = self::find($cat);
            }
            

            if(is_array($data) && count($data) > 0){

                /* deletando o tipo de tarefa. */
			    if(parent::base_remove('category',$cat)){

                    return "success";

                }else{

                    return "Não foi possivel deletar a categoria. ";

                }

            }else{

                return 'Esta categoria não existe';

            }
        }

	}
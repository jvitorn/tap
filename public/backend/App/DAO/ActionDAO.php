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
            
            $ret = "";

            if(empty($action->getUser())) $ret .= 'O usuário deve ser informado!';
            if(empty($action->getCategory())) $ret .= 'A categoria deve ser informada!';
            if(empty($action->getName())) $ret .= 'o Nome deve ser informado!';

            if($ret != "") return $ret;

            if(self::category_is_accessible($action)){
                
                if(parent::base_create('action',$action)){
                    
                    return "success";

                }else{

                    return "Nao foi possivel cadastrar a ação. ";

                }

            }else{

                return "Categoria inválida. ";

            }
		}

		static public function find(Action $action){

            if(empty($action->getUser())) return "O ID do usuário deve ser informado. ";

            if(!empty($action->getName())){
                parent::where(" AND action.name LIKE '".$action->getName()."%' ");
                $action->setName(null);
            }

			return parent::base_find('action',$action);
		}

		static public function edit(Action $action){

            $ret = "";

            if(empty($action->getUser())) $ret .= 'O usuário deve ser informado!';
            if(empty($action->getCategory())) $ret .= 'A categoria deve ser informada!';
            if(empty($action->getName())) $ret .= 'o nome deve ser informado!';
            if(empty($action->getId())) $ret .= 'o id deve ser informado!';

            if($ret != "") return $ret;

            /* verifica se a ação pertence ao usuário. */
            if(!self::action_is_accessible($action)) return 'Ação inválida. ';

            /* verifica se o usuário pode editar a a ação desta categoria. */
            if(self::category_is_accessible($action)){
                
                if( parent::base_edit('action',$action)){
                    
                    return 'success';

                }else{

                    return "nao foi possivel editar a ação. ";

                }

            }else{

                return 'Ação não encontrada';

            }     
		}

		static public function remove(Action $action){
            
            if(!self::action_is_accessible($action)) return 'Ação inválida. ';

            if(parent::base_remove('action',$action)){
                
                return 'success';

            }else{

                return "Não foi possivel deletar a ação. ";

            }
        }

        static private function category_is_accessible(Action $action ){

            /* query para verificar se o usuário pode atribuir a ação a esta categoria. */
            $sql_vrf_ctgr  = " SELECT * FROM category ";
            $sql_vrf_ctgr .= " WHERE category.is_public = '1' ";
            $sql_vrf_ctgr .= " AND category.id = ".$action->getCategory();
            $sql_vrf_ctgr .= " OR category.user = ".$action->getUser();
            $sql_vrf_ctgr .= " AND category.id = ".$action->getCategory();

            $res = parent::query($sql_vrf_ctgr);

            if(is_array($res) && count($res) > 0 ) return true;
        }

        static private function action_is_accessible(Action $action){
            
            /* monta a query para verificar se o usuário pode acessar esta ação. */
            $query  = " SELECT * FROM action";
            $query .= " WHERE action.user = ".$action->getUser();
            $query .= " AND action.id = ".$action->getId();

            /* busca a action. */
            $res = parent::query($query);
            
            /* se encontrar a ação, retorna true. */
            if(is_array($res) && count($res) > 0) return true;
        }
	}
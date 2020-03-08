<?php 
	namespace App\Controller;

    use App\Controller\Controller;
    
    use App\Model\Category;
    use App\Model\Action;
	
    use App\DAO\CategoryDAO;
    use App\DAO\ActionDAO;
    
	/**
	 * @method $this->render->json($dataArray);
	 */
	class ControllerCategory extends Controller {

        public function user_new_category($data){
            $this->validate_access(['user','adm']);

            $data['is_public']  = 0;
            $data['active']     = 1;

            $this->new_category($this->user->getId(), $data);
        }

        public function admin_new_category($data){
            $this->validate_access('adm');

            $data['is_public']  = 1;

            $this->new_category($this->user->getId(), $data);
        }

        private function new_category($user_id, $data){

            $cat = new Category($user_id, $data);
            $res = CategoryDAO::create($cat);

            if(is_numeric($res)){
                $json['status'] = 'success';
                $json['msg']    = 'Categoria criada com sucesso!';
            }else{
                $json['msg']    = "Erro: não foi possivel criar a categoria!\r\n";
                $json['msg']   .= $res;
            }

            $this->render->json($json);
        }

        /* lista as categorias publicas. */
        public function list_public_categories($data){
            $this->validate_access('adm');
            $data['is_public'] = 1;
            $category = new Category($this->user->getId(), $data);
            $res = $this->get_categories($category);

            $this->render->json($res);
        }

        /* lista as categorias privadas. */
        public function list_private_categories(){
            $this->validate_access(['user','adm']);
            $data['is_public'] = 0;
            $category = new Category($this->user->getId(), $data);
            $res = $this->get_categories($category);

            $this->render->json($res);
        }

        /* lista as categorias privadas e publicas. */
        public function list_all_categories(){
            $this->validate_access(['user','adm']);
            
            $data['is_public'] = 0;
            $category = new Category($this->user->getId(), $data);
            $res1 = $this->get_categories($category);

            $data['is_public'] = 1;
            $category = new Category($this->user->getId(), $data);
            $res2 = $this->get_categories($category);

            if(is_array($res2)) $res1 = array_merge($res1,$res2);

            $this->render->json($res1);
        }
        
        public function list_detailed_categories($data){
            $this->validate_access(['user','adm']);

            if(isset($data['id']) && !is_numeric($data['id'])) unset($data['id']);

            /* buscando ações de categorias publicas */
            $data['is_public'] = 1;
            $category   = new Category($this->user->getId(), $data);
            $categories = CategoryDAO::find($category);

            $action     = new Action($this->user->getId());
            $actions    = ActionDAO::find($action);

            $res1       = $this->order_table($categories, $actions);
            $res1       = $this->order_list($res1);

            /* buscando ações de categorias privadas. */
            $data['is_public'] = 0;
            $category   = new Category($this->user->getId(), $data);
            $categories = CategoryDAO::find($category);

            $action     = new Action($this->user->getId());
            $actions    = ActionDAO::find($action);

            $res2       = $this->order_table($categories, $actions);
            $res2       = $this->order_list($res2);

            if(is_array($res2)) $res1 = array_merge($res1,$res2);

            $this->render->json($res1);
        }

        private function get_categories($category){
            $res = CategoryDAO::find($category);
            return $this->prepare_array($res);
        }

        /* agrupa as ações através de suas categorias. */
        private function order_list($cats){

            $categories = [];
            $c = 0;

            if(!is_array($cats)) return array();

            foreach($cats as $key => $cat){

                $id = $cat['category_id'];

                if(!isset($categories[$id])){
                    
                    if(is_array($cat)){
                        
                        foreach($cat as $key => $value){
                            if(substr($key,0,8) == "category"){
                                $categories[$id][$key] = $value;
                            }
                        }

                        $c = 0; 
                    }

                }

                $actions = null;

                if(isset($cat['action_id']) && is_numeric($cat['action_id'])){

                    foreach($cat as $key => $value){
                        if(substr($key,0,6) == "action") $actions[$key] = $value;
                    }    
                }

                if(is_array($actions)){
                    $categories[$id]['actions'][$c] = $actions;
                }else{
                    $categories[$id]['actions'] = array();    
                }
                $c++;
            }

            return $categories;
        }

        /* agrupa as ações com suas categorias. */
        private function order_table($categories, $actions){
            
            $res = [];
            $c = 0;

            if(is_array($categories)){
                foreach($categories as $key => $category ){
                    $encontrou = false;
                    
                   
                        foreach($actions as $k => $action){

                            if($category['category_id'] == $action['action_category']){
                                $res[] = array_merge($action,$category);
                                unset($actions[$k]);
                                $encontrou = true;
                                break;
                            }
                        }
                    

                    if(!$encontrou) $res[] = $categories[$key];
                }
            }

            // echo "<pre>";
            // print_r($res);

            return $res;
        }

        public function user_edit_category($data = []){
            $this->validate_access(['user','adm']);
            $data['is_public'] = 0;
            $this->edit_category($data);
        }

        public function admin_edit_category($data = []){
            $this->validate_access('adm');
            $data['is_public'] = 1;
            $this->edit_category($data);
        }

        private function edit_category($data){
            $cat = new Category($this->user->getId(), $data);
            $res = CategoryDAO::edit($cat);

            if($res == 'success'){
                $json['status'] = 'success';
                $json['msg']    = 'Categoria atualizada com sucesso!';
            }else{
                $json['msg']    = "Erro: não foi possivel atualizar a categoria!\r\n";
                $json['msg']   .= $res;
            }

            $this->render->json($json);
        }

        public function user_remove_category($id){
            $this->validate_access(['user','adm']);
            $this->remove_category('0',$id);
        }

        public function admin_remove_category($id){
            $this->validate_access('adm');
            $this->remove_category('1',$id);
        }

        private function remove_category($is_public, $id){
            $data = ['id' => $id,'is_public' => $is_public];
            $res = CategoryDAO::remove(new Category($this->user->getId(),$data));

            if($res == 'success'){
                $json['status'] = 'success';
                $json['msg']    = 'Categoria deletada com sucesso';
            }else{
                $json['status'] = 'success';
                $json['msg']    = "Erro: não foi possivel deletar a categoria\r\n";
                $json['msg']   .= $res;
            }
            
            $this->render->json($json);
        }
	}
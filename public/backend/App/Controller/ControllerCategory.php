<?php 
	namespace App\Controller;

    use App\Controller\Controller;
    
    use App\Model\Category;
	
    use App\DAO\CategoryDAO;
    
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

            $res = CategoryDAO::find_public_categories(new Category($this->user->getId()));
            $res = $this->order_list($res);

            $this->render->json($res);
        }

        /* lista todas categorias(publicas e privadas) */
        public function list_all_categories($data){
            $this->validate_access(['user','adm']);

            $res = CategoryDAO::find_public_categories(new Category($this->user->getId()));
            $res = $this->order_list($res);

            $data['is_public'] = 0;
            $res2 = $this->list_categories($this->user->getId(),$data);

            if(is_array($res2)) $res = array_merge($res,$res2);

            $this->render->json($res);
        }

        /* lista todas as categorias privadas criadas pelo usuário. */
        public function my_categories($data){
            $this->validate_access(['user','adm']);
            $data['is_public'] = 0;
            
            $res = $this->list_categories($this->user->getId(),$data);
            $this->render->json($res);
        }

        /* faz a busca e retorna os resultados para outros metodos. */
        private function list_categories($user_id, $data){
            $res = CategoryDAO::find(new Category($user_id,$data));
            return $this->order_list($res);
        }

        /* agrupa as ações através de suas categorias. */
        private function order_list($cats){

            $categories = [];
            $c = 0;

            foreach($cats as $key => $cat){

                $id = $cat['category_id'];

                if(!isset($categories[$id])){
                    foreach($cat as $key => $value){
                        if(substr($key,0,8) == "category"){
                            $categories[$id][$key] = $value;
                        }
                    }
                    $c = 0;
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
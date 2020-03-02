<?php 
	namespace App\Controller;

    use App\Controller\Controller;
    use App\Controller\ControllerAction;

	use App\Model\User;
    use App\Model\Category;
	
    use App\DAO\CategoryDAO;
    
	/**
	 * @method $this->render->json($dataArray);
	 */
	class ControllerCategory extends Controller {

        public function create(User $user, $data = []){

            $json['status'] = 'error';

            $cat = new Category($user, $data);
            $res = CategoryDAO::create($cat);

            if(is_numeric($res)){
                $json['status'] = 'success';
                $json['msg']    = 'Categoria criada com sucesso!';
            }else{
                $json['msg']    = "Erro: não foi possivel criar a categoria!\r\n";
                $json['msg']   .= $res;
            }
            return $json;
        }

        public function edit(User $user, $data = []){
            $json['status'] = 'error';

            if(isset($data['id'])){
                $cat = new Category($user, $data);
                $res = CategoryDAO::edit($cat);

                if($res == 'success'){
                    $json['status'] = 'success';
                    $json['msg']    = 'Categoria atualizada com sucesso!';
                }else{
                    $json['msg']    = "Erro: não foi possivel atualizar a categoria!\r\n";
                    $json['msg']   .= $res;
                }

            }else{
                 $json['msg']    = "Erro:o campo ID deve ser informado!\r\n";
            }
            return $json;
        }

        
        public function list(User $user, $data){
            $cat    = new Category($user, $data);
            $cats   = CategoryDAO::find($cat);
            
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

                if(is_numeric($cat['action_id'])){

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

        public function remove($user, $id){
            if(!isset($id)) return 'O ID do item precisa ser informado';
            $res = CategoryDAO::remove(new Category($user,['id' => $id]));

            if($res == 'success'){
                $json['status'] = 'success';
                $json['msg']    = 'Categoria deletada com sucesso';
            }else{
                $json['status'] = 'success';
                $json['msg']    = "Erro: não foi possivel deletar a categoria\r\n";
                $json['msg']   .= $res;
            }
            return $json;
        }
	}
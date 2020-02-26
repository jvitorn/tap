<?php 
	namespace App\Controller;

    use App\Controller\Controller;

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
            $cat = new Category($user, $data);
            return CategoryDAO::find($cat);
        }

        public function remove($user, $data){
            if(!isset($data['id'])) return 'O ID do item precisa ser informado';
            $res = CategoryDAO::remove(new Category($user, $data));

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
<?php 
	namespace App\DAO;

	use App\DAO\DAO;
	use App\Model\User;
    use App\Model\Category;

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
            /**
             * Verifica se já existe no banco um tipo de ação que seja:
             *  do mesmo usuário + do mesmo tipo + com o mesmo nome + do mesmo código publico
             */
            $a = new Category( $cat->getUser(),
                [
                    'is_public' => $cat->getIs_public(),
                    'name'      => $cat->getName(),
                    'type'      => $cat->getType(),
                ]
            ); 
            $res = self::find($a);
            if(is_array($res) && count($res) > 0){
                return 'Esta categoria ja existe!';
            }else{
                parent::array_to_sql_create($cat->getAttributesAsArray());
		        return parent::Insert('category');
            }
            
		}

		static public function find(Category $cat){

            $where = $cat->getAttributesAsArray();
                
            // se for publica, pegar todas nao só as do usuario
            if($cat->getIs_public()){ unset($where['user']); }

            parent::array_to_sql_where('category',$where);

            self::columns($cat->getPublicColumns());
			return parent::Select('category');
		}

		static public function edit(Category $cat){
            if(is_numeric($cat->getId())){
                $res = self::find(new Category($cat->getUser(),[
                    'id' => $cat->getId(),
                    'is_public' => $cat->getIs_public()
                ]));

                if(is_array($res) && count($res) > 0){
                    parent::array_to_sql_update($cat->getAttributesAsArray());
                    parent::array_to_sql_where('category',[
                        'id' => $cat->getId(),
                        'is_public' => $cat->getIs_public()
                    ]);
                    if( parent::Update('category')){
                        return 'success';
                    }
                }else{
                    return 'Categoria não encontrada';
                }
            }
		}

		static public function remove(Category $cat){
            
            if(!empty($cat->getId())){
                
                $data = self::find($cat);

                if(is_array($data) && count($data) > 0){

                    // deletando o tipo de tarefa
                    $where = ['id' => $cat->getId(), 'user' => $cat->getUser()->getId() ];
                    parent::array_to_sql_where('category',$where);
				    return parent::Delete('category');
                }else{
                    return 'Esta categoria não existe';
                }
			}
        }
	}
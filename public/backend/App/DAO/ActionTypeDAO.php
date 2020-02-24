<?php 
	namespace App\DAO;

	use App\DAO\DAO;
	use App\Model\User;
    use App\Model\ActionType;

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
	class ActionTypeDAO extends DAO {

		static public function create(ActionType $at){
            /**
             * Verifica se já existe no banco uma ação que seja:
             *  do mesmo usuário + do mesmo tipo + com o mesmo nome + do mesmo código publico
             */
            $a = new ActionType( $at->getUser(),
                [
                    'is_public' => $at->getIs_public(),
                    'name'      => $at->getName(),
                    'type'      => $at->getType(),
                ]
            ); 
            $res = self::find($a);
            if(is_array($res) && count($res) > 0){
                return 'Este tipo de ação ja existe!';
            }else{
                parent::array_to_sql_create($at->getAttributesAsArray());
		        return parent::Insert('action_type');
            }
            
		}

		static public function find(ActionType $at){

            $where = $at->getAttributesAsArray();
            
            if($at->getIs_public() == 1){ unset($where['user']); }

            parent::array_to_sql_where('action_type',$where);
            self::columns($at->getPublicColumns());
			return parent::Select('action_type');
		}

		static public function edit(ActionType $at){
            if(is_numeric($at->getId())){
                $res = self::find(new ActionType($at->getUser(),['id' => $at->getId()]));

                if(is_array($res) && count($res) > 0){
                    parent::array_to_sql_update($at->getAttributesAsArray());
                    parent::array_to_sql_where('action_type',['id' => $at->getId() ]);
                    return parent::Update('action_type');
                }
            }
		}

		static public function remove(ActionType $at){
            
            if(!empty($at->getId())){
                
                $data = self::find($at);

                if(is_array($data) && count($data) > 0){
                    $where = ['id' => $at->getId(), 'user' => $at->getUser()->getId() ];
                    parent::array_to_sql_where('action_type',$where);
				    return parent::Delete('action_type');
                }else{
                    return 'Este tipo de ação não existe';
                }
			}
        }
	}
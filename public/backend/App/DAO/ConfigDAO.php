<?php 
	namespace App\DAO;

	use App\DAO\DAO;
	use App\Model\Config;

	class ConfigDAO extends DAO {

		static public function find(Config $config){
			return parent::base_find('config',$config);
		}

		static public function edit(Config $config){

            $ret = "";

            if(empty($config->getValue())) $ret .= 'o valor deve ser informado!';

            if($ret != "") return $ret;
    
            if( parent::base_edit('config',$config)){
                
                return 'success';

            }else{

                return "nao foi possivel editar a configuração. ";

            }
		}
	}
<?php 
	namespace App\DAO;

	use App\DAO\DAO;
	use App\Model\Grupo;

	class GrupoDAO extends DAO {

		static public function create(Grupo $grupo){
			$cols = "cd_grupo, nm_grupo, status_grupo,ds_grupo,created_at";

			$vals =  "NULL";
			$vals .=",'".$grupo->getNome()."'";
			$vals .=",'".$grupo->getStatus()."'";
			$vals .=",'".$grupo->getDescricao()."'";
			$vals .=",'".$grupo->getCreatedAt()."'";
			
			return parent::insert('grupo',$cols,$vals);
		}

		static public function find(Grupo $grupo){
			
			$where = " cd_grupo > 0 ";

			if(!empty($grupo->getId())){ $where = " cd_grupo = '{$grupo->getId()}'"; }
			if(!empty($grupo->getNome())){ $where .= " AND nm_grupo = '{$grupo->getNome()}'"; }
			if(!empty($grupo->getStatus())){ $where .= " AND status_grupo = '{$grupo->getStatus()}'"; }

			return parent::Select('grupo','*',false,$where);
		}

		static public function edit(Grupo $grupo){
			if(is_numeric($grupo->getId())){
				$set = " nm_grupo = '".$grupo->getNome()."'";
				$set .= ",status_grupo = '".$grupo->getStatus()."'";
				$set .= ",ds_grupo = '".$grupo->getDescricao()."'";
				$set .= ",updated_at = '".date('Y-m-d h:i:s')."'";

				$where = "cd_grupo = '".$grupo->getId()."'";

				return parent::Update('grupo',$set,$where);
			}
		}

		static public function remove(Grupo $grupo){
			if(is_numeric($grupo->getId())){
				$where = "cd_grupo = '{$grupo->getId()}'";
				if(parent::Delete('grupo_modulo', $where)){
					return parent::Delete('grupo', $where);
				}
			}
		}
	}
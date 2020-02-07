<?php 
	namespace App\DAO;

	use App\DAO\DAO;
	use App\Model\Financial;
	use App\Model\User;

	/**
	 * @method Insert($tbl, $cols, $vals)
	 * @method Select($tbl, $cols, $where, $order, $limit)
	 * @method Update($tbl, $cols,$where)
	 * @method Delete($tbl, $where)
	 * @method query($sql)
	 * @method array_to_sql_create($data)
	 * @method array_to_sql_update($data)
	 * @method array_to_sql_where($data)
	 */
	class FinancialDAO extends DAO {

		static public function create(Financial $f){
			$sql = parent::array_to_sql_create($f->getAttributesAsArray());
			return parent::Insert('financial', $sql['cols'], $sql['vals']);
		}

	}
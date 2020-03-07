<?php
    namespace App\DAO;
    
    use App\DB;
    //Essa classe contem metodos básicos usados por todas as classes DAO.
    class DAO extends DB{
        
        static private $DB;
        static private $where     = 'id > 0 ';
        static private $cols      = '';
        static private $vals      = "";
        static private $orderBy   = " id ASC";
        static private $limit     = "";
        static private $join      = "";
                
        //metodo para insert, todo insert do sistema passa por este metodo
        static protected function insert($table){
            
            $sql = "INSERT INTO $table (".self::$cols.") VALUES (".self::$vals.")";

            // echo $sql;

            if(self::query($sql) ){

                self::$cols     = 'id';
                self::$orderBy  = "id DESC";
                self::$limit    = 'LIMIT 1';

                /* pega o item registrado */
                $row = self::Select($table);

                /* retorna id do item*/
                if(is_array($row)) return $row[0]['id'];
            }
        }

        static public function clearParams(){
            self::$where     = 'id > 0 ';
            self::$cols      = '';
            self::$vals      = "";
            self::$orderBy   = "";
            self::$limit     = "";
            self::$join      = "";
        }

        static protected function select($table){

            /* monta a query apenas com as colunas e o nome da tabela.  */
            $sql = "SELECT ".self::$cols." FROM ". $table;

            /* se houver JOIN, adiciona a tabela.                       */
            if(!empty(self::$join)) $sql .= " ".self::$join;

            /* se houver condição para o select, adiciona a condição.   */
            if(!empty(self::$where)) $sql .= " WHERE ".self::$where;
            
            /* se houver uma ordenação no select, a adiciona na query.  */
            if(!empty(self::$orderBy)) $sql .= " ORDER BY ". self::$orderBy;
            
            /* se houver um limite, a adiciona na query.                */
            if(!empty(self::$limit)) $sql .= " ".self::$limit;

            /* ponto de debug */
            if($table == 'user') echo $sql."<br><br>";

            return self::query($sql);
        }
        
        static protected function update($tabela){
            $sql = "UPDATE $tabela SET ". self::$vals." WHERE ".self::$where." ";
            return self::query($sql);
        }
        
        static protected function delete($tabela){
            $sql = "DELETE FROM $tabela WHERE ".self::$where;
            // echo $sql;
            return self::query($sql);
        }

        static protected function query($sql){
            $result = false;
            try {
                
                self::$DB = self::connectDB()->prepare($sql);

                if(self::$DB->execute()){
                       
                    $rows = self::$DB->fetchAll(\PDO::FETCH_ASSOC);                    
                    $result = true;
                    
                    if(is_array($rows) && count($rows) > 0) $result = $rows;
                    
                }else{
                   $result = self::$DB->errorInfo()[2];
                }
                
            } catch (\Exception $e) {
                $result = $e->getMessage();
            }

            self::clearParams();
            return $result;
        }

        static protected function array_to_sql_create($data){

            $arr['cols'] = "";
            $arr['vals'] = "";
            $virgula = false;

            foreach($data as $col => $val){
                
                if(!empty($val)){                    
                    
                    if(!is_array($val) && !is_object($val) && !empty($val)){
                        if($virgula){
                            $arr['cols'] .= ",";
                            $arr['vals'] .= ",";
                        }

                        $arr['cols'] .= $col;
                        $arr['vals'] .= "'".$val."'";

                        $virgula = true;
                    }                    
                }
            }

            self::$cols = $arr['cols'];
            self::$vals = $arr['vals'];
        }

        static protected function array_to_sql_update($data){

            $update = "";
            $virgula = false;

            foreach($data as $col => $val){

                if(!is_array($val) && $val != null){
                    if($virgula) $update .= ",";
                    
                        $virgula = true;
                    
                    $update .= $col." = '".$val."' ";
                }
            }

            self::$vals = $update;
        }

        static protected function array_to_sql_where($tbl, $data){
            
            self::$where = $tbl.".".self::$where;

            foreach($data as $col => $val){

                if(!is_null($val)){
                    $where = '';

                    if(!is_array($val)){
                        $where .= $tbl.".".$col ." = '".$val."' ";
                        if(!empty($where)) self::$where .= " AND ".$where;
                    }
                }
            } 
        }

        static protected function columns($tbl, $cols){
            
            $fields = "";
            if(!empty(self::$cols)) $fields = ",";
            
            $virgula = false;

            foreach($cols as $col => $value){
                if(!is_array($value)){

                    if($virgula) $fields .= ",";

                    $virgula = true;
                    $fields .= $tbl.".".$col." as ". $tbl."_".$col;     
                }
            }

            self::$cols .= $fields;
        }

        static protected function values($vals){
            self::$vals = $vals;
        }

        static protected function join($tbl){
            self::$join .= " $tbl ";
        }

        static protected function where($where){
            self::$where .= " $where ";
        }

        static protected function orderBy($order){
            self::$orderBy = $order;
        }

        static protected function limit($limit){
            self::$limit = $limit;
        }


        static public function base_create($tbl, $obj){
            if(is_object($obj) && method_exists($obj,'get_attributes_as_array')){
                self::array_to_sql_create($obj->get_attributes_as_array());
                return self::insert($tbl);    
            }
        }

        static protected function base_find($tbl, $obj){
            
            $ret = array();

            if(is_object($obj) && method_exists($obj,'get_attributes_as_array')){
                
                self::array_to_sql_where($tbl,$obj->get_attributes_as_array());
                self::columns($tbl, $obj->get_attributes_as_array());
                
                $res = self::select($tbl);

                if(is_array($res)) $ret = $res;
                
                return $ret;
            }
        }

        static public function base_edit($tbl, $obj){
            if(is_object($obj) && method_exists($obj,'get_attributes_as_array')){
                self::array_to_sql_update($obj->get_attributes_as_array());
                self::array_to_sql_where($tbl,['id' => $obj->getId() ]);
                return self::update($tbl);
            }
        }

        static public function base_remove($tbl, $obj){
            if(is_object($obj) && method_exists($obj,'get_attributes_as_array')){
                self::array_to_sql_where($tbl,['id' => $obj->getId()]);
                return self::delete($tbl);
            }
        }
    }

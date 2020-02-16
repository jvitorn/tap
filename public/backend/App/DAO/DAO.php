<?php
    namespace App\DAO;
    
    use App\DB;
    //Essa classe contem metodos básicos usados por todas as classes DAO.
    class DAO extends DB{
        
        static private $DB;
        static private $where     = 'id > 0 ';
        static private $cols      = '';
        static private $vals      = "";
        static private $orderBy   = "";
        static private $limit     = "";
        static private $join      = "";
        static private $set       = "";
                
        //metodo para insert, todo insert do sistema passa por este metodo
        static protected function Insert($table){
            
            $sql = "INSERT INTO $table (".self::$cols.") VALUES (".self::$vals.")";
            // echo $sql."\r\n";

            if(self::query($sql)){

                self::$cols     = 'id';
                self::$orderBy  = "id DESC";
                self::$limit    = 'LIMIT 1';

                /* pega o item registrado */
                $row = self::Select($table);

                /* retorna id do item*/
                if(is_array($row)) return $row[0]['id'];
            }
        }

        static protected function Select($table){

            /* monta a query apenas com as colunas e o nome da tabela.  */
            $sql = "SELECT ".self::$cols." FROM ". $table;
            
            /* se houver condição para o select, adiciona a condição.   */
            if(!empty(self::$where)) $sql .= " WHERE ".self::$where;
            
            /* se houver uma ordenação no select, a adiciona na query.  */
            if(!empty(self::$orderBy)) $sql .= " ORDER BY ". self::$orderBy;
            
            /* se houver um limite, a adiciona na query.                */
            if(!empty(self::$limit)) $sql .= " ".self::$limit;

            /* ponto de debug */
            // echo $sql;

            return self::query($sql);
        }
        
        static protected function Update($tabela){
            $sql = "UPDATE $tabela SET ". self::$set." WHERE ".self::$where." ";
            return self::query($sql);
        }
        
        static protected function Delete($tabela){
            $sql = "DELETE FROM $tabela WHERE ".self::$where;
            // echo $sql;
            return self::query($sql);
        }

        static protected function query($sql){
            try {
                
                self::$DB = self::connectDB()->prepare($sql);
                if(self::$DB->execute()){
                    
                    $rows = self::$DB->fetchAll(\PDO::FETCH_ASSOC);
                    
                    $res = true;
                    if(is_array($rows) && count($rows) > 0){  return $rows; }

                    return $res;

                }else{
                   return self::$DB->errorInfo()[2];
                }
                
            } catch (\Exception $e) {
                return $e->getMessage();
            }                
        }

        static protected function array_to_sql_create($data){

            $arr['cols'] = "";
            $arr['vals'] = "";
            $virgula = false;

            foreach($data as $col => $val){
                
                if(!empty($val)){
                    if($virgula){
                        $arr['cols'] .= ",";
                        $arr['vals'] .= ",";
                    }
                    
                    $arr['cols'] .= $col;

                    if(is_array($val)){
                        $arr['vals'] .= "'".$val['id']."'";
                    }else{
                        $arr['vals'] .= "'".$val."'";
                    }

                    $virgula = true;
                }
                
            }

            self::$cols = $arr['cols'];
            self::$vals = $arr['vals'];
        }

        static protected function array_to_sql_update($data){

            $update = "";
            $virgula = false;

            foreach($data as $col => $val){
                if(!empty($val)){
                    if($virgula) $update .= ",";

                    if(is_array($val)){
                        $update .= $col." = '".$val['id']."' ";
                    }else{
                        $update .= $col." = '".$val."' ";    
                    }
                    
                    $virgula = true;
                }
            }

            self::$set = $update;
        }

        static protected function array_to_sql_where($tbl, $data){

            foreach($data as $col => $val){
            
                if(!empty($val)){
                    self::$where .= " AND  ";

                    if(is_array($val)){
                        self::$where .= $tbl.".".$col ." = '".$val['id']."' ";
                    }else{
                        self::$where .= $tbl.".".$col ." = '".$val."' ";
                    }
                } 
            } 
        }

        static protected function columns($cols){
            self::$cols .= $cols;
        }

        static protected function values($vals){
            self::$vals = $vals;
        }

        static protected function join($tbl){
            self::$join .= " $tbl ";
        }

        static protected function where($where){
            self::$where .= " AND $where ";
        }

        static protected function orderBy($order){
            self::$orderBy = $order;
        }

        static protected function limit($limit){
            self::$limit = $limit;
        }
    }

<?php
    namespace App\DAO;
    
    use App\DB;
    //Essa classe contem metodos básicos usados por todas as classes DAO.
    class DAO extends DB{
        
        static private $DB;
        static private $arr_retorno = ["error" => ""];

        static private $where     = 'id > 0 ';
        static private $cols      = 'id';
        static private $vals      = '';
        static private $orderBy   = '';
        static private $limit     = '';
                
        //metodo para insert, todo insert do sistema passa por este metodo
        static protected function Insert($tbl){
            
            $sql = "INSERT INTO $tbl (".self::$cols.") VALUES (".self::$vals.")";
            // echo $sql."\r\n";

            if(self::query($sql)){
                self::$cols     = 'id';
                self::$orderBy  = "id DESC";
                self::$limit    = 'LIMIT 1';

                /* pega o id do registro inserido */
                self::Select($tbl);
            }
            if(isset(self::$arr_retorno['res'][0]['id'])){
                $id = self::$arr_retorno['res'][0]['id'];
                self::$arr_retorno['res'] = ['id' => $id];
            }
            return self::$arr_retorno;
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

            self::$arr_retorno['res'] = self::query($sql);
            return self::$arr_retorno;
        }
        
        static protected function Update($tabela, $set, $where = 0){
            $sql = "UPDATE $tabela SET $set WHERE $where";
            self::unsetArray();
            self::$arr_retorno['res'] = self::query($sql);
            return self::$arr_retorno;
        }
        
        static protected function Delete($tabela, $where = 0){
            $res = false;
            $sql = "DELETE FROM $tabela WHERE $where";
            self::$DB = self::connectDB()->prepare($sql);
            if(self::$DB->execute()) $res = true;
            self::unsetArray();
            return $res;
        }

        static protected function query($sql){
            try {
                
                self::$DB = self::connectDB()->prepare($sql);
                if(self::$DB->execute()){
                    
                    $rows = self::$DB->fetchAll(\PDO::FETCH_ASSOC);
                    
                    $arr = null;

                    foreach($rows as $row) $arr[] = $row;

                    if(is_array($arr)){
                        return $arr;
                    }else{
                        return true;
                    }

                }else{
                    self::$arr_retorno['error'] = self::$DB->errorInfo()[2];
                }
                
            } catch (Exception $e) {
                self::$arr_retorno['error'] = $e->getMessage();
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

            return $update;
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

        static protected function queryParams($data){ 
            if(isset($data['join'])){ self::$join = $data['join']; }
            if(isset($data['where'])){ self::$where .= " AND ". $data['where']; }
            if(isset($data['cols'])){ self::$cols = $data['cols']; }
            if(isset($data['vals'])){ self::$vals = $data['vals']; }
            if(isset($data['orderBy'])){ self::$orderBy = $data['orderBy']; }
            if(isset($data['limit'])){ self::$limit = $data['limit']; }
        }
    }

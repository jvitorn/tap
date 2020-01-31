<?php
    namespace App\DAO;
    
    use App\DB;
    //Essa classe contem metodos básicos usados por todas as classes DAO.
    class DAO extends DB{
        
        static private $DB;
        static private $arr_retorno = ["error" => ""];
        static private $colArray;
                
        //metodo para insert, todo insert do sistema passa por este metodo
        static protected function Insert($tbl, $cols, $vals){
            
            $sql = "INSERT INTO $tbl ($cols) VALUES ($vals)";
            // echo $sql."\r\n";

            if(self::query($sql)){
                $where = self::array_to_sql_where($tbl, self::$colArray);

                /* pega o id do registro inserido */
                self::$arr_retorno['res'] = self::Select($tbl,'id',$where)[0]['id'];
            }

            self::unsetArray();
            return self::$arr_retorno;
        }

        static protected function Select($tbl,$cols,$where = false, $order = false, $limit = false){

            /* monta a query apenas com as colunas e o nome da tabela.  */
            $sql = "SELECT $cols FROM $tbl ";
            
            /* se houver condição para o select, adiciona a condição.   */
            if($where) $sql .= " WHERE $where ";
            
            /* se houver uma ordenação no select, a adiciona na query.  */
            if($order) $sql .= " ORDER BY $order ";
            
            /* se houver um limite, a adiciona na query.                */
            if($limit) $sql .= " LIMIT $alcance ";

            /* ponto de debug */
            // echo $sql;
            self::unsetArray();
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
                        echo "nao encontrou";
                        return true;
                    }

                }else{
                    self::$arr_retorno['error'] = self::$DB->errorInfo()[2];
                }
                
            } catch (Exception $e) {
                self::$arr_retorno['error'] = $e->getMessage();
            }                
        }

        static private function saveArray($data){
            self::$colArray = $data;
        }

        static private function unsetArray(){
            self::$colArray = null;
        }

        static protected function array_to_sql_create($data){
            self::saveArray($data);

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

            return $arr;
        }

        static protected function array_to_sql_update($data){
            self::saveArray($data);

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
            self::saveArray($data);

            $where = "";
            $and = false;
            
            foreach($data as $col => $val){
                
                if(!empty($val)){

                    if($and) $where .= " AND  ";

                    if(is_array($val)){
                        $where .= $tbl.".".$col ." = '".$val['id']."' ";
                    }else{
                        $where .= $tbl.".".$col ." = '".$val."' ";
                    }

                    $and = true;
                } 
                
            }

            return $where;
        }
    }

<?php
    namespace App\DAO;
    
    use App\DB;
    //Essa classe contem metodos básicos usados por todas as classes DAO.
    class DAO extends DB{
        
        static private $DB;
        static private $arr_retorno = ["error" =>""];
                
        //metodo para insert, todo insert do sistema passa por este metodo
        static protected function insert($tabela, $colunas, $valores){
            //monta a query
            $sql = "INSERT INTO $tabela ($colunas) VALUES ($valores)";
            
            // echo $sql."\r\n";

            //tenta executar o trecho de codigo
            try{

                self::$DB = self::connectDB()->prepare($sql);
                self::$DB->execute();

                //transforna a variavel $colunas em array, separando onde tem virgula
                $cols = explode(",",$colunas);
                
                self::$arr_retorno['data'] = self::returnLastInsert( $tabela, $cols[0]);

                return self::$arr_retorno;

            //se nao conseguir executar o codigo acima, captura o erro
            }catch(\PDOException $e){
                // //mostra o erro
                self::$arr_retorno = ["error" => $e];
                return self::$arr_retorno;
            } 
        }
        //metodo para retornar o ultimo registro inserido.
        static private function returnLastInsert($tabela, $col){
            $sql = "SELECT * FROM $tabela ORDER BY $col DESC LIMIT 1";
            self::$DB = self::connectDB()->prepare($sql);
            self::$DB->execute();
            $fetch = self::$DB->fetch(\PDO::FETCH_ASSOC);
            return $fetch;
        }
        
        static protected function Select($tabela,$cols,$where = false, $order = false, $limit = false){

            $res = false;
            //começa montando a query apenas com as colunas e o nome da tabela
            $sql = "SELECT $cols FROM $tabela ";
            //se houver condição para o select, adiciona a condição na query.
            if($where) $sql .= " WHERE $condicao ";
            //se houver uma ordenação no select, a adiciona na query.
            if($order) $sql .= " ORDER BY $order ";
            //se houver um limite para a quantidade de linhas selecionadas, a adiciona na query.
            if($limit) $sql .= " LIMIT $alcance ";
            //o atributo DB recebe a conexao com o DB e prepara a query

            self::$DB = self::connectDB()->prepare($sql);
            //executa a query.
            self::$DB->execute();
            //se a query retornar 1 ou mais linhas...
            if(self::$DB->rowCount() > 0){
                //enquanto houver "linha", adicione o conteudo dela no array $res;
                while($fetch = self::$DB->fetch(\PDO::FETCH_ASSOC)) $res[] = $fetch;
            }
            //retorna o array com as informações do DB.
            return $res;
        }
        
        static protected function Update($tabela, $set, $where = 0){
            $res = false;
            $sql = "UPDATE $tabela SET $set WHERE $where";
            self::$DB = self::connectDB()->prepare($sql);
            if(self::$DB->execute()) $res = true;
            return $res;  
        }
        
        static protected function Delete($tabela, $where = 0){
            $res = false;
            $sql = "DELETE FROM $tabela WHERE $where";
            self::$DB = self::connectDB()->prepare($sql);
            if(self::$DB->execute()) $res = true;
            return $res;
        }
    }

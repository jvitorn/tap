<?php
    namespace Src\Classes;
    
    class ClassRender {

        private $data = [];
        private $layout = "_layout.php";
        private $main = "";
        private $footer = "_footer.php";

        public function setLayout($layout){ $this->layout = $layout.$this->layout; }
        public function setMain($main){ $this->main = $main.".php"; }
        public function setFooter($footer){ $this->footer = $footer. $this->footer; }

        /**
         * @method view
         * Este método é responsavel por carregar a página renderizada
         */
        public function view($main, $dataArray = null){
            $this->setMain($main);
            if(is_array($dataArray)) $this->data = $dataArray;
            $this->renderizar();
        }
        
        /**
         * @method renderizar
         * Este metodo renderiza a página, carregando o metodo main e footer junto
         */
        public function renderizar(){
            if(file_exists(DIR_LAYOUTS.$this->layout)) require_once DIR_LAYOUTS.$this->layout;
        }

        /**
         * @method main
         * Este método é responsavel por carregar o conteudo principal da view
         */
        public function main(){
            if(file_exists(DIR_VIEW.$this->main)) include_once DIR_VIEW.$this->main;
        }

        /**
         * @method footer
         * Este método chama modals ou scripts especificos 
         */
        public function footer(){
            if(file_exists(DIR_VIEW.$this->footer )) include_once DIR_VIEW.$this->footer;
        }

        /**
         * @method get
         * Este método é responsavel por verifiacar se a variavel chamada dentro da view existe.
         */
        private function get($var){
            if(array_key_exists($var, $this->data)) return $this->data[$var];
            return "!!chave [$var] não existe!!";
        }
    }
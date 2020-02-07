<?php 
	namespace Src\Traits;
	
	trait TraitGetAttributesAsArray {

		public function getAttributesAsArray(){
			
			$attr = get_object_vars($this);
			$ret;
			
			foreach($attr as $name => $value){

				if(is_object($value)){
					$ret[$name] = $value->getAttributesAsArray();
				}else{
					$ret[$name] = $value;
				}
			}

			return $ret;
		}
		
	}
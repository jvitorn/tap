<?php 
	namespace Src\Traits;
	
	trait TraitGetAttributesAsArray {

		public function get_attributes_as_array(){
			
			$attr = get_object_vars($this);
			
			foreach($attr as $name => $value){

				if(is_object($value)){
					$ret[$name] = $value->get_attributes_as_array();
				}else{
					$ret[$name] = $value;
				}
			}

			return $ret;
		}
		
	}
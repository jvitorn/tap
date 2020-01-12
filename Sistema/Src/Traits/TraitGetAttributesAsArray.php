<?php 
	namespace Src\Traits;
	
	trait TraitGetAttributesAsArray {

		public function getAttributesAsArray(){
			return get_object_vars($this);
		}
		
	}
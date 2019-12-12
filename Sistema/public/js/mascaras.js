(function() {
	if($('#cpf').length){
		VMasker(document.getElementById("cpf")).maskPattern('999.999.999-99');
	}

	if($('#cnpj').length){
		VMasker(document.getElementById("cnpj")).maskPattern('99.999.999/9999-99');
	}
})();
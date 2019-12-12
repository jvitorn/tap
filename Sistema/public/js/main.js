function requisicaoAjax(url, arrayData, onSuccess, onError = null){
	
	$.ajax({
		url: url,
		method:'post',
		dataType:'json',
		data: arrayData,
		success: function( data ){
			onSuccess( data );
		},
		error: function( data ){			
			if(onError != null) onError();
		}
	});
}

function sucesso(data){
	console.log('sucesso:'+data.nome);
}

function erro(){
	console.log("algo de errado nao esta certo");
}

function exemplo(){
	var url = "Home/teste";
	var data = {'nome':'jdc'}
	requisicaoAjax(url,data,sucesso,erro);
}

exemplo();
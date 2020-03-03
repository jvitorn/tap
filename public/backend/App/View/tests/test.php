<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
LOGIN<br>
<form id="formLogin">
    <input type="email" name="email">
    <input type="password" name="password">
    <button>Logar</button>
</form>

<hr>
FUNCOES DO ADMIN<br>
<button class="btnAction" endpoint="admin/user" method="get">listar usuarios</button>
<input type="text" name="id" id="excluir_id"><button>Excluir usuário</button>

<hr>
FUNCOES DO USUÁRIO<br>
<button class="btnAction">editar usuario</button>
<button class="btnAction">excluir usuario </button>
<button id="btnDeslogar">Deslogar</button>

<script>
    token = '';
    base_url = <?= "'".DIR_PAGE."'"; ?>;

    $(document).on('submit','#formLogin',function(e){
        e.preventDefault();

        var form = $('#formLogin').serialize();

        $.ajax({
            url: base_url + 'auth/login',
            dataType: 'json',
            type :'POST',
            data: form,
            success: function(data){
                if(data.token){
                    token = data.token;
                }
                console.log(data);
            },
            error: function(error){
                console.log(error);
            }
        });
        
    });

    $(document).on('click','.btnAction',function(){
        var endpoint = $(this).attr('endpoint');
        var type     = $(this).attr('method');
        ajax(endpoint,type);
    });

    function ajax(endpoint, method){
        $.ajax({
            url: base_url + endpoint,
            dataType: 'html',
            type :method,
            headers: {
                "Authorization":"Bearer "+ token
            },
            success: function(data){
                console.log(data);
            },
            error: function(error){
                console.log(error);
            }
        });
    }
</script>
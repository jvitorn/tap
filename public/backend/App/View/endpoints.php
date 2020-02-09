<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Endpoints</title>
</head>
<body>
    <div style="text-align:center">
    <h1>Lista de endpoints disponiveis</h1>
    </div>
    <hr>
    
    <ol>
        <h2>Usuários</h2>
        <li>
            <span>Listar usuários [GET]</span>
            /backend/user
            <p>parâmetros obrigatórios:<br>
            parâmetros opcionais: [id, name, email, type("user","adm"), gender("f","m") ]</p>
        </li>
        <li>
            <span>Cadastrar usuário [POST]</span>
            /backend/user <br>
            <p>parâmetros obrigatórios: [name, email, password]<br>
            parâmetros opcionais: [gender("f","m"), height, weight, dt_birth]</p>
        </li>
        <li>
            <span>Atualizar usuário [PUT]</span>
            /backend/user <br>
            <p>parâmetros obrigatórios: [id]<br>
            parâmetros opcionais: [name, email, password, gender("f","m"), height, weight, dt_birth]</p>
        </li>
        <li>
            <span>Deletar usuário [DELETE]</span>
            /backend/user/{id} <br>
            <p>parâmetros obrigatórios: [id]<br>
            parâmetros opcionais: </p>
        </li>
    </ol>
</body>
<style>
    span{
        font-size: 18px;
        font-weight: bold;
    }
</style>
</html>
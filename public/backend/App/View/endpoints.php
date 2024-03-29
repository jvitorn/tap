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
        <h2>Administrador [necessário estar logado como admin para acessar estas funções]</h2>
        <li> USUÁRIOS
            <ol>
                <li>
                    <span>Listar usuários [GET]</span><br>
                    /backend/admin/user/list
                    <p>
                    parâmetros obrigatórios:<br>
                    parâmetros opcionais: [id, name, email, type("user","adm"), gender("f","m") ]<br>
                    os parâmetros atuarão como filtros para selecionar os usuarios.
                    </p>
                </li>
                <li>
                    <span>Deletar usuário [DELETE]</span><br>
                    /backend/admin/user/remove/{id} <br>
                    <p>parâmetros obrigatórios: [id]<br>
                    parâmetros opcionais: <br>
                    esta função irá deletar um usuário selecionado.
                    </p>
                </li>
            </ol>
        </li>
        <li> TIPOS DE AÇÃO<br>
            são categorias de ação ou movimentação<br>
            exemplo Task: estudos, trabalhos, exercicios...<br>
            exemplo Financial: contas de casa, compras, comida, internet...

            <ol>
                <li>
                    <span>Adicionar tipo de movimentação PUBLICA [POST]</span><br>
                    /backend/admin/action-type
                    <p>
                    parâmetros obrigatórios:[name, type, active]<br>
                    parâmetros opcionais: [description, value, dt_initial, dt_final]<br>
                    </p>
                </li>
                <li>
                    <span>Adicionar tipo de movimentação PUBLICA [POST]</span><br>
                    /backend/admin/action-type
                    <p>
                    parâmetros obrigatórios:[name, type, active]<br>
                    parâmetros opcionais: [description, value, dt_initial, dt_final]<br>
                    </p>
                </li>
            </ol>
        </li>
        
    </ol>

    <hr>
    
    <ol>
        <h2>Usuários</h2>
        <li>
            <span>Cadastrar usuário [POST]</span><br>
            /backend/user/register <br>
            <p>parâmetros obrigatórios: [name, email, password]<br>
            parâmetros opcionais:</p>
        </li>
        <li>
            <span>Confirmar cadastro do usuário [POST]</span><br>
            /backend/user/confirm-account <br>
            <p>parâmetros obrigatórios: [code, email]<br>
            parâmetros opcionais:</p>
        </li>
        <li>
            <span>Atualizar usuário [PUT][NECESSÁRIO ESTAR LOGADO]</span><br>
            /backend/user/update <br>
            <p>parâmetros obrigatórios: [id, name]<br>
            parâmetros opcionais: [password, gender("f","m"), height, weight, dt_birth]</p>
        </li>
        <li>
            <span>Requisitar redefinição de senha [POST]</span><br>
            /backend/user/reset-password-request <br>
            <p>parâmetros obrigatórios: [email]<br>
            parâmetros opcionais: <br>
            esta função enviará um código para o email do usuário.
            </p>
        </li>
        <li>
            <span>Verificar código de redefinição de senha [POST]</span><br>
            /backend/user/verify-reset-password-code <br>
            <p>parâmetros obrigatórios: [email, code]<br>
            parâmetros opcionais: <br>
            esta função verificará o código enviado para o email do usuário.
            </p>
        </li>
        <li>
            <span>redefinir senha [PUT]</span><br>
            /backend/user/reset-password<br>
            <p>parâmetros obrigatórios: [email, code, password]<br>
            parâmetros opcionais: <br>
            esta função irá redefinir a senha do usuário
            </p>
        </li>
        <li>
            <span>Requisitar código de exclusão de conta [GET][NECESSÁRIO ESTAR LOGADO]</span><br>
            /backend/user/delete-account-request<br>
            <p>parâmetros obrigatórios: <br>
            parâmetros opcionais: <br>
            esta função irá enviar um código para o email do usuário.
            </p>
        </li>
        <li>
            <span>Deletar usuário [DELETE][NECESSÁRIO ESTAR LOGADO]</span><br>
            /backend/user/delete-account/{code} <br>
            <p>parâmetros obrigatórios: [code]<br>
            parâmetros opcionais: <br>
            esta função irá deletar a conta do usuário.
            </p>
        </li>
    </ol>

    <hr>
    
    <ol>
        <h2>Autenticações</h2>
        <li>
            <span>Logar [POST]</span><br>
            /backend/auth/login <br>
            <p>parâmetros obrigatórios: [email, password]<br>
            parâmetros opcionais:</p>
        </li>
        <li>
            <span>Logout [POST] [NECESSÁRIO ESTAR LOGADO]</span><br>
            /backend/auth/logout <br>
            <p>parâmetros obrigatórios: <br>
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
# TAP - WEBSERVICE

Recursos disponiveis
* **Usuários**
* **Categorias**
* **Ações**
* **Administradores**
* **Configurações**
* **Emails**

## Usuário
- Registrar usuário 
	**[/backend/user] [POST]**
	Necessário token de acesso: NÃO
	Campos obrigatórios: name, email, password
	Campos opcionais:

- Confirmar Conta 
	**[/backend/user/confirm-account] [POST]**
	Necessário token de acesso: NÃO
	Campos obrigatórios: auth
	Campos opcionais:

- Entrar na conta 
	**[/backend/auth/login] [POST]**(Gera o token de acesso)
	Necessário token de acesso: NÃO
	Campos obrigatórios: email, password
	Campos opcionais:

- Editar conta 
	**[/backend/user] [PUT]**
	Necessário token de acesso: SIM
	Campos obrigatórios: name
	Campos opcionais: height, weight, gender, dt_birth, password

- Pedido de redefinição de senha 
	**[/backend/user/reset-password-request] [POST]**
	Necessário token de acesso: NÃO
	Campos obrigatórios: email
	Campos opcionais:

- Validar código de redefinição de senha 
	**[/backend/user/verify-reset-password-code] [POST]**
	Necessário token de acesso: NÃO
	Campos obrigatórios: auth
	Campos opcionais:

- Redefinir senha 
	**[/backend/user/reset-password] [PUT]**
	Necessário token de acesso: NÃO
	Campos obrigatórios: password, auth
	Campos opcionais:

- Pedido para deletar conta 
	**[/backend/user/delete-account-request] [GET]**
	Necessário token de acesso: SIM
	Campos obrigatórios: 
	Campos opcionais:

- Deletar conta 
	**[/backend/user/{code}] [GET]**
	Necessário token de acesso: SIM
	Campos obrigatórios: code
	Campos opcionais:

- **[ADMIN]** Listar usuários
	**[/backend/admin/user] [GET]**
	Necessário token de acesso: SIM
	Campos obrigatórios:
	Campos opcionais: name, type, active

- **[ADMIN]** Deletar usuário 
	**[/backend/admin/user/{code}] [GET]**
	Necessário token de acesso: SIM
	Campos obrigatórios: code
	Campos opcionais:

## Categoria

- **[USER]**Criar nova categoria 
	**[/backend/category] [POST]**
	Necessário token de acesso: SIM
	Campos obrigatórios: name, type, description
	Campos opcionais: value, dt_initial, dt_final

- **[USER]**Listar todas as disponiveis categorias 
	**[/backend/category/categories] [GET]**
	Necessário token de acesso: SIM
	Campos obrigatórios:
	Campos opcionais: name, type

- **[USER]**Listar categorias criadas pelo usuário
	**[/backend/category/my-categories] [GET]**
	Necessário token de acesso: SIM
	Campos obrigatórios:
	Campos opcionais: name, type

- **[USER]**Listar categorias 
	**[/backend/category/detailed-categories] [GET]**
	Necessário token de acesso: SIM
	Campos obrigatórios:
	Campos opcionais: name, type

- **[USER]**Editar categoria
  **[/backend/category] [PUT]**
	Necessário token de acesso: SIM
	Campos obrigatórios: name, type
	Campos opcionais: value, dt_initial, dt_final

- **[USER]**Excluir categoria
	**[/backend/category/{id}] [DELETE]**
	Necessário token de acesso: SIM
	Campos obrigatórios: id
	Campos opcionais:

- **[ADMIN]**Criar nova categoria publica
	**[/backend/admin/category] [POST]**
	Necessário token de acesso: SIM
	Campos obrigatórios: name, type, description
	Campos opcionais: value, dt_initial, dt_final

- **[ADMIN]**Listar todas categorias publicas
	**[/backend/category] [GET]**
	Necessário token de acesso: SIM
	Campos obrigatórios:
	Campos opcionais: name, type

- **[ADMIN]**Editar categoria publica
	**[/backend/admin/category] [PUT]**
	Necessário token de acesso: SIM
	Campos obrigatórios: name, type
	Campos opcionais: value, dt_initial, dt_final

- **[ADMIN]**Excluir categoria publica
	**[/backend/admin/category/{id}] [DELETE]**
	Necessário token de acesso: SIM
	Campos obrigatórios: id
	Campos opcionais:

## Ação

- **[USER/ADMIN]** Criar nova ação
	**[/backend/action] [POST]**
	Necessário token de acesso: SIM
	Campos obrigatórios: name, category
	Campos opcionais: description, active, repeats, active_days, active_hours, dthr_initial, dthr_final,
		value

- **[USER/ADMIN]** Listar ações
	**[/backend/action] [GET]**
	Necessário token de acesso: SIM
	Campos obrigatórios:
	Campos opcionais: name, category,active, repeats, value

- **[USER/ADMIN]** Editar ação
	**[/backend/action] [PUT]**
	Necessário token de acesso: SIM
	Campos obrigatórios: name, category
	Campos opcionais: description, active, repeats, active_days, active_hours, dthr_initial, dthr_final,
		value

- **[USER/ADMIN]** Excluir ações
	**[/backend/action/{id}] [DELETE]**
	Necessário token de acesso: SIM
	Campos obrigatórios: id
	Campos opcionais:

## Configuração

- **[ADMIN]** Listar configurações
	**[/backend/admin/config] [GET]**
	Necessário token de acesso: SIM
	Campos obrigatórios:
	Campos opcionais: name, active, type

- **[ADMIN]** Editar configuração
	**[/backend/admin/config] [PUT]**
	Necessário token de acesso: SIM
	Campos obrigatórios: name, value, active, type
	Campos opcionais: 

## Email

- **[ADMIN]** Listar emails
	**[/backend/admin/email] [GET]**
	Necessário token de acesso: SIM
	Campos obrigatórios:
	Campos opcionais: title,type, active

- **[ADMIN]** Editar email
	**[/backend/admin/email] [PUT]**
	Necessário token de acesso: SIM
	Campos obrigatórios: title, content, type, active
	Campos opcionais:

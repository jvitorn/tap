<?php 
	use Src\Classes\ClassRoutes;

	/**
	 * chama o metodo apontado na url, caso nao existe chama o index do Controller
	 * exemplos:
	 * 	ClassRoutes::GET('home','ControllerHome@list');
	 *	ClassRoutes::POST('user/new','ControllerHome@add');
	 */
    
     /**
	 *	Rotas Home
	 */
        ClassRoutes::GET('','ControllerHome@index');
        ClassRoutes::GET('tests','ControllerHome@tests');

	/**
	 *	Rotas Admin
	 */
        ClassRoutes::GET('admin/user/list','ControllerAdmin@list');
        ClassRoutes::DELETE('admin/user/remove','ControllerAdmin@remove_user');

        /* rotas para criar, editar, listar e excluir tipos de ações privadas */
        ClassRoutes::POST('admin/action-type','ControllerAdmin@new_action_type');
        ClassRoutes::PUT('admin/action-type','ControllerAdmin@edit_action_type');
        ClassRoutes::GET('admin/action-type','ControllerAdmin@list_action_type');
        ClassRoutes::DELETE('admin/action-type','ControllerAdmin@remove_action_type');
    /**
	 *	Rotas Usuário
	 */
        /* rotas para criação da conta */
        ClassRoutes::POST('user/register','ControllerUser@add');
        ClassRoutes::POST('user/confirm-account','ControllerUser@confirm_account');
        
        /* rota para atualizar dados da conta */
        ClassRoutes::PUT('user/update','ControllerUser@edit');

        /* rotas para redefinir senha*/
        ClassRoutes::POST('user/reset-password-request','ControllerUser@reset_password_request');
        ClassRoutes::POST('user/verify-reset-password-code','ControllerUser@verify_reset_password_code');
        ClassRoutes::PUT('user/reset-password','ControllerUser@reset_password');

        /*rotas para exclusão da conta (necessário estar logado)*/
		ClassRoutes::GET('user/delete-account-request','ControllerUser@delete_account_request');
        ClassRoutes::DELETE('user/delete-account','ControllerUser@delete_account');

        /* rotas para criar, editar, listar e excluir tipos de ações privadas */
        ClassRoutes::POST('action-type','ControllerUser@new_action_type');
        ClassRoutes::PUT('action-type','ControllerUser@edit_action_type');
        ClassRoutes::GET('action-type','ControllerUser@list_action_type');
        ClassRoutes::DELETE('action-type','ControllerUser@remove_action_type');

    /**
	 *	Rotas Auth
	 */
        ClassRoutes::POST('auth/login','ControllerAuth@login');
        ClassRoutes::POST('auth/logout','ControllerAuth@logout');

    /**
     *  Rotas para tipos de ações
     */ 
        
        ClassRoutes::POST('admin/action-type/new','ControllerActionType@public_action_type');
        ClassRoutes::POST('admin/action-type/new','ControllerActionType@public_action_type_list');
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
        ClassRoutes::GET('admin/user','ControllerAdmin@list_users');
        ClassRoutes::DELETE('admin/user','ControllerAdmin@remove_user');

        ClassRoutes::GET('admin/config','ControllerAdmin@list_configs');
        ClassRoutes::PUT('admin/config','ControllerAdmin@edit_config');

        /* rotas para criar, editar, listar e excluir categorias publicas */
        ClassRoutes::POST('admin/category','ControllerCategory@admin_new_category');
        ClassRoutes::PUT('admin/category','ControllerCategory@admin_edit_category');
        ClassRoutes::GET('admin/category','ControllerCategory@list_public_categories');
        ClassRoutes::DELETE('admin/category','ControllerCategory@admin_remove_category');
    /**
	 *	Rotas Usuário
	 */
        /* rotas para criação da conta */
        ClassRoutes::POST('user','ControllerUser@add');
        ClassRoutes::POST('user/confirm-account','ControllerUser@confirm_account');
        
        /* rota para atualizar dados da conta */
        ClassRoutes::PUT('user','ControllerUser@edit');

        /* rotas para redefinir senha*/
        ClassRoutes::POST('user/reset-password-request','ControllerUser@reset_password_request');
        ClassRoutes::POST('user/verify-reset-password-code','ControllerUser@verify_reset_password_code');
        ClassRoutes::PUT('user/reset-password','ControllerUser@reset_password');

        /*rotas para exclusão da conta (necessário estar logado)*/
		ClassRoutes::GET('user/delete-account-request','ControllerUser@delete_account_request');
        ClassRoutes::DELETE('user','ControllerUser@delete_account');

        /* rotas para criar, editar, listar e excluir categorias privadas */
        ClassRoutes::POST('category','ControllerCategory@user_new_category');
        ClassRoutes::PUT('category','ControllerCategory@user_edit_category');
        ClassRoutes::GET('category/categories','ControllerCategory@list_all_categories');
        ClassRoutes::GET('category/my-categories','ControllerCategory@list_private_categories');
        ClassRoutes::GET('category/detailed-categories','ControllerCategory@list_detailed_categories');
        ClassRoutes::DELETE('category','ControllerCategory@user_remove_category');

        /* Rotas para criar, editar, listar e excluir ações */
        ClassRoutes::POST('action','ControllerAction@create');
        ClassRoutes::PUT('action','ControllerAction@edit');
        ClassRoutes::GET('action','ControllerAction@find');
        ClassRoutes::DELETE('action','ControllerAction@remove');

    /**
	 *	Rotas Auth
	 */
        ClassRoutes::POST('auth/login','ControllerAuth@login');
        // ClassRoutes::POST('auth/logout','ControllerAuth@logout');
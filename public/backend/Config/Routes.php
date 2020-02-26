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

        /* rotas para criar, editar, listar e excluir categorias publicas */
        ClassRoutes::POST('admin/category','ControllerAdmin@new_category');
        ClassRoutes::PUT('admin/category','ControllerAdmin@edit_category');
        ClassRoutes::GET('admin/category','ControllerAdmin@list_categories');
        ClassRoutes::DELETE('admin/category','ControllerAdmin@remove_category');
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

        /* rotas para criar, editar, listar e excluir categorias privadas */
        ClassRoutes::POST('category','ControllerUser@new_category');
        ClassRoutes::PUT('category','ControllerUser@edit_category');
        ClassRoutes::GET('category','ControllerUser@list_categories');
        ClassRoutes::GET('category/my-categories','ControllerUser@my_categories');
        ClassRoutes::DELETE('category','ControllerUser@remove_category');

    /**
	 *	Rotas Auth
	 */
        ClassRoutes::POST('auth/login','ControllerAuth@login');
        ClassRoutes::POST('auth/logout','ControllerAuth@logout');
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

	/**
	 *	Rotas Admin
	 */
        ClassRoutes::GET('admin/user','ControllerAdmin@list');
        ClassRoutes::DELETE('admin/user','ControllerAdmin@remove');
    /**
	 *	Rotas Usuário
	 */
        
        ClassRoutes::PUT('user','ControllerUser@edit');

        /* rotas para redefinir senha*/
        ClassRoutes::POST('user/reset-password-request','ControllerUser@reset_password_request');
        ClassRoutes::POST('user/reset-password-code','ControllerUser@verify_reset_password_code');
        ClassRoutes::PUT('user/reset-password','ControllerUser@reset_password');
        
        /* rotas para criação da conta */
        ClassRoutes::POST('user','ControllerUser@add');
        ClassRoutes::POST('user/confirm-account','ControllerUser@confirm_account');

        /*rotas para exclusão da conta (necessário estar logado)*/
		ClassRoutes::GET('user/delete-account-request','ControllerUser@delete_account_request');
        ClassRoutes::DELETE('user/delete-account','ControllerUser@delete_account');
    /**
	 *	Rotas Auth
	 */
        ClassRoutes::POST('auth/login','ControllerAuth@login');
        ClassRoutes::POST('auth/logout','ControllerAuth@logout');
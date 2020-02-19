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
        ClassRoutes::POST('user','ControllerUser@add');
        ClassRoutes::PUT('user','ControllerUser@edit');
        ClassRoutes::GET('user/reset-password','ControllerUser@reset_password');
        ClassRoutes::DELETE('user','ControllerUser@remove');
    /**
	 *	Rotas Auth
	 */
        ClassRoutes::POST('auth/login','ControllerAuth@login');
        ClassRoutes::POST('auth/logout','ControllerAuth@logout');
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
	 *	Rotas Dashboard
	 */

    /**
	 *	Rotas Usuário
	 */
        ClassRoutes::POST('user','ControllerUser@add');
        ClassRoutes::GET('user','ControllerUser@list');
        ClassRoutes::PUT('user','ControllerUser@edit');
        ClassRoutes::DELETE('user','ControllerUser@remove');

    /**
	 *	Rotas Auth
	 */
	    ClassRoutes::POST('auth/login','ControllerAuth@login');
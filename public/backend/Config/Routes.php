<?php 
	use Src\Classes\ClassRoutes;
	/**
	 *	@example 
	 *  simples:
	 *	ClassRoutes::route('url','controller')
	 *	
	 *	direcionando para um método especifico
	 *	ClassRoutes::route('url','controller','action')
	 */

	/**
	 * chama o metodo apontado na url, caso nao existe chama o index do Controller
	 * exemplos:
	 * 	ClassRoutes::GET('home','ControllerHome@list');
	 *	ClassRoutes::POST('user/new','ControllerHome@add');
	 */
    
    ClassRoutes::GET('','ControllerHome@index');
	ClassRoutes::GET('user','ControllerUser@list');
	ClassRoutes::POST('user','ControllerUser@add');
	ClassRoutes::PUT('user','ControllerUser@edit');
	ClassRoutes::DELETE('user','ControllerUser@remove');

    ClassRoutes::POST('auth/login','ControllerAuth@login');

	/**
	 *	chama o metodo metho1 do controller
	 */
	// ClassRoutes::route('info','ControllerHome','method1');
	// ClassRoutes::route('login','ControllerPainel','login');
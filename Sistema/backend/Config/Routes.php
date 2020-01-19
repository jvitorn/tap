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
	 * 	ClassRoutes::GET('','ControllerHome','index');
	 *	ClassRoutes::GET('user/listar','ControllerHUser','index');
	 *	ClassRoutes::POST('user','ControllerHome','add');
	 */

	ClassRoutes::GET('','ControllerHome@index');

	ClassRoutes::GET('user','ControllerHome@user');
	ClassRoutes::POST('user','ControllerHome@add',['id','nome']);
	ClassRoutes::PUT('user','ControllerHome@add',['id','nome']);
	ClassRoutes::DELETE('user','ControllerHome@add',['id','nome']);

	// ClassRoutes::route('Home','ControllerHome');
	// ClassRoutes::route('Painel','ControllerPainel');

	/**
	 *	chama o metodo metho1 do controller
	 */
	// ClassRoutes::route('info','ControllerHome','method1');
	// ClassRoutes::route('login','ControllerPainel','login');
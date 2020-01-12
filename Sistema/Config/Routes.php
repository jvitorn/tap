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
	 */
	// ClassRoutes::route('','ControllerHome');

	ClassRoutes::route('','ControllerUser','new');

	ClassRoutes::route('Home','ControllerHome');
	ClassRoutes::route('Painel','ControllerPainel');

	/**
	 *	chama o metodo metho1 do controller
	 */
	ClassRoutes::route('info','ControllerHome','method1');
	ClassRoutes::route('login','ControllerPainel','login');
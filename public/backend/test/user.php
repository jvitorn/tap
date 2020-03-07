<?php 
	require_once 'base.php';


	use App\Model\User;
	use App\Model\Category;
	use App\Model\Action;

	use App\DAO\UserDAO;
	use App\DAO\CategoryDAO;
	use App\DAO\ActionDAO;


	/**
	 * Cadastrando o usuário
	 */
		$user = new User(
			[
				'name' 		=> "sssssssss",
				'email'		=> 'jdc@idse843.com',
				'password'	=> 'w2e43rr'
			]
		);

		$res = UserDAO::create($user);
		echo "<pre>";
		print_r($res);
		echo "<pre>";	


	/**
	 * Listando o Usuario
	 */
		$res = UserDAO::find($user)[0];
		echo "<pre>";
		print_r($res);
		echo "<pre>";

	/**
	 * Editando o Usuario
	 */
		$user->setId($res['user_id']);
		$user->setName('dsadsadsa');
		$res = UserDAO::edit($user);
		echo "<pre>";
		print_r($res);
		echo "<pre>";
	/**
	 * Excluindo o Usuário
	 */
		// $res = UserDAO::remove($user);
		// echo "<pre>";
		// print_r($res);
		// echo "<pre>";

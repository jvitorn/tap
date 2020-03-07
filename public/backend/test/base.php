<?php
    session_start();
    
    header("Access-Control-Allow-Origin: *");
    
    require_once "../Src/vendor/autoload.php";
    
    /**
     * Load system settings
     *  - path to folders
     *  - DB access data
     *  - email sending data
     */
    require_once '../Config/config.php';

    /**
     * Load system routes
     */
    require_once '../Config/Routes.php';
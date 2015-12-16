<?php

    require_once 'Database.php';

    function _print_r($var){
        echo '<pre>';
        print_r($var);
        echo '</pre>';
   }

    //$users = Database::getInstance()->select('users' , array('users=>''username' , 'password'), array('username' , '=' , 'alex'))->_print_r();
    
    //$users = Database::getInstance()->insert('users' , array( 'username' => 'ishi',
    //										 				  'password' => 'password'));

    //$users = Database::getInstance()->select('users')->_print_r();


    $users = Database::getInstance()->select_multiple( array('user' , 'info') , array('users'=>'username') , array( 'users' => 'id' , '=' , 'info' => 'userid' ));









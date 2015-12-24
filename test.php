<?php

    require_once 'Database.php';

    function _print_r($var){
        echo '<pre>';
        print_r($var);
        echo '</pre>';
   }

    //$users = Database::getInstance()->select('users' , array('users=>''username' , 'password'), array('username' , '=' , 'alex'))->_print_r();
    
    //$users = Database::getInstance()->insert('users' , array( 'username' => 'ishan',
    //										 				  'password' => 'password'));

    //$users = Database::getInstance()->select('users')->_print_r();


    $users = Database::getInstance()->select_multiple( array('users' , 'info') , array('*') , array( 'users' => 'id' , '=' , 'info' => 'userid' ));
    //$users = Database::getInstance()->select('users' , array('*') , array('username' , '=' , 'ishan') );
    //$name = $users->username;
    _print_r($users->results());

    //how to use 

    //use query 
    //query('select * from ? where ? = ?' , array('foo' , 'foo1' , 'bar1'));

    // insert into foo ('foo1' , 'foo2') values('bar1' , 'bar2')
    //insert('users' , array('foo1' => 'bar1' , 'foo2' => 'bar2'));   

 	// delete from foo where foo1 = bar1
 	//delete('foo' , array('foo1' , '=' , 'bar1'))

 	//update foo set foo1 = bar1 where foo2 = bar2
 	//update('foo' , array('foo1' => 'bar1') , array('foo2' ,'=' , 'bar2'))					



<?php

class Database {
    private static $_instance = null;
    private     $_pdo,
                $_query,
                $_error = false,
                $_results,
                $_count = 0,
                $_config_path = "config.ini.php",
                $_mysql_error_info = null;

    private function __construct(){
        try{
            // PDO(String, username, password)

            $config = parse_ini_file($this->_config_path , true);


            @$this->_pdo = new PDO('mysql:host='.$config["database"]["host"].';dbname='.$config["database"]["database"], $config["database"]["username"] , $config["database"]["password"]);
            //$this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);



        }catch(PDOException $e){
            $err = $e->getMessage();
            echo(" <script> console.log('Database  :  {$err}') </script> ");
            die();
        }

        echo(" <script> console.log('Database  :  Connection Established') </script> ");
    }

    // Following a singleton pattern
    public static function getInstance(){

        if(!isset(self::$_instance)){
            self::$_instance = new Database();
        }

        return self::$_instance;
    }

    public function query($sql, $params = array()){

        // pending
        $this->_error = false;
        $this->_mysql_error_info= null;

        if($this->_query = $this->_pdo->prepare($sql)){
            $x = 1;
            if(count($params)){
                foreach($params as $param){
                    $this->_query->bindValue($x, $param);
                    $x++;
                }

            }



            if($this->_query->execute()){
                $this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
                $this->_count = $this->_query->rowCount();
            }else{
                $this->_error = true;
                $this->_mysql_error_info = $this->_query->errorInfo();
            }

        }

        return $this;
    }

    public function mysql_error_info()
    {
        return $this->_mysql_error_info[2];
    }

    private function action($action, $table, $where = array()){

        if(count($where) === 3){
            $operators = array('=', '>', '<', '>=', '<=' , 'like');

            $field      = $where[0];
            $operator   = $where[1];
            $value      = $where[2];

            if(in_array($operator, $operators)){
                $sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";
                if(!$this->query($sql, array($value))->error()){
                    return $this;
                }
            }
        }
        return false;
    }

    public function select_all($table, $where){
        return $this->action('SELECT *', $table, $where);
    }

    public function select($table , $fields=array() , $where = array()){
        if(!count($where) && !count($fields)){
            return $this->query("SELECT * FROM {$table}");
        }elseif(!count($where) && count($fields)){

            $sql_fields = '';

            foreach($fields as $field){
                $sql_fields .= $field . ', ';
            }



            $sql_fields = rtrim($sql_fields , ', ');

            $select = "SELECT {$sql_fields} FROM {$table}";

            if(!$this->query($select , array($table))->error()){

                return $this;
            }



        }elseif(count($where) && count($fields)){

                $sql_fields = '';

                foreach($fields as $field){
                    $sql_fields .= $field . ', ';
                }

                $sql_fields = rtrim($sql_fields , ', ');

                $operators = array('=', '>', '<', '>=', '<=' , 'like');

                $field      = $where[0];
                $operator   = $where[1];
                $value      = $where[2];

                if(in_array($operator, $operators)){
                    $sql = "SELECT {$sql_fields} FROM {$table} WHERE {$field} {$operator} ?";
                    if(!$this->query($sql, array($value))->error()){
                        return $this;
                    }
                }


        }else
            return false;
    }

    public function delete($table, $where = array()){
        return $this->action('DELETE', $table, $where);
    }

    /*
     * return true/false
     * */
    public function insert($table, $fields = array()){

        if(count($fields)){
            $keys   = array_keys($fields);
            $values = '';
            $x      = 1;

            foreach($fields as $field){
                $values .= '?';
                if($x < count($fields)){
                    $values .= ', ';
                }
                $x++;
            }

            $sql = "INSERT INTO {$table} (".implode(', ', $keys).") VALUES ({$values})";


            if(!$this->query($sql, $fields)->error()){
                return true;
            }
        }

        return false;
    }

    public function update($table, $id, $fields){
        $set = '';
        $x = 1;

        foreach($fields as $name => $value){
            $set .= "{$name} = ?";
            if($x < count($fields)) {
                $set .= ', ';
            }
            $x++;
        }

        $sql = "UPDATE {$table} SET {$set} WHERE id = {$id}";

        if(!$this->query($sql, $fields)->error()){
            return true;
        }
    }

    public function error(){
        return $this->_error;
    }

    public function count(){
        return $this->_count;
    }

    public function results(){
        return $this->_results;
    }

    public function first(){
        return $this->results()[0];
    }

    public function enhanced_var_dump(){
        echo '<pre>';
        var_dump($this);
        echo '</pre>';
    }

    public function enhanced_print_r(){
        echo '<pre>';
        print_r($this);
        echo '</pre>';
    }





} 




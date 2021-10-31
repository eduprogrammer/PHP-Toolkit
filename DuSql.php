<?php

    /* 
        Copyright 2021. Eduardo Programador
        www.eduardoprogramador.com 
        consultoria@eduardoprogramador.com

        DuSql -> Library for use of MySQL Database in Web Servers
        Written by Eduardo Programador. All rights reserved.

        Instructions:

        1. Upload DuSql.php to your WebServer;
        2. Include it on your Php File: require 'DuSql.php';
        3. Initialize the Class wih the SQL constuctor: $dusql = new DuSql('user','password');
        4. Set the server address info (ip): $dusql->infoServer('localhost');
        5. Say the database name: $dusql->infoDatabase('dbname');
        6. Set the debug option: 'yes' to show database logs and 'no' for no response: $duSql->debugMode('yes');
        7. Connect to database: $dusql->start();
        8. Insert some values or update: $duSql->operation('insert into mytable (id,test) values ('a','b')');
        9. Retrieve some values: $duSql->select(status: 'select * from mytable', array of string colums here: array('id','test'));
        
    */

class DuSql {
        
    private $sql_user;
    private $sql_pass;
    private $sql_server;
    private $db;
    private $con;
    private $mode;
    
    function __construct($user, $pass) {
        $this->sql_user = $user;
        $this->sql_pass = $pass;
    }

    function debugMode($answer) {
        if(strcmp($answer,'yes') == 0) {
            $this->mode = TRUE;
        } else {
            $this->mode = FALSE;
        }
    }

    public function getValidString($str) : string {

        $res = strip_tags($str);
        $res = htmlentities($res);
        return mysqli_real_escape_string($this->con,$res);
    }        
    
    public function infoServer($server) : void {
        $this->sql_server = $server;
    }

    public function infoDatabase($db) : void {
        $this->db = $db;
    }

    public function start() : bool {

        $this->con = new mysqli($this->sql_server,$this->sql_user,$this->sql_pass,$this->db);
        if($this->con->connect_error) {
            if($this->mode == TRUE) {                
                die("Connection Failed: " . $this->con->connect_error);
            }
            return FALSE;
        } else {                
            if($this->mode == TRUE) {
                echo("Connection Ok!");
            }
            return TRUE;
        }                    
    }

    public function stop() : void {
        $this->con->close();
    }

    public function operation($command) : bool {            
        $query = $command;
        if($this->con->query($query) === TRUE) {                
            if($this->mode == TRUE) {
                echo("Inserted");
            }
            return TRUE;
        } else {                
            if($this->mode == TRUE) {
                echo("Error: " . $query . $this->con->error);
            }
            return FALSE;
        }            
    }

    public function select($command, $arrayOfColumns) : array {
        
        $temp = array();            
        $tempStr = array();            
        $i = 0;
        $len = count($arrayOfColumns);

        $len = count($arrayOfColumns);

        $res = $this->con->query($command);

        if($res->num_rows > 0) {
            while($row = $res->fetch_assoc()) {

                foreach($arrayOfColumns as $key) {                                                                      
                                            
                    $tempStr[] = $row[$key]; 
                    
                }     
                
                $temp[] = $tempStr;      
                $tempStr = array();              
            }
            
            
            return $temp;

        } else {
            return NULL;
        }
    }



}

?>
<?php

    $conn=NULL;
    $db_servername = "localhost";
    $db_username = "jeeva";
    $db_password = "1234";
    $db_name="testapi";

    function getdbConnection(){
        // echo __FILE__;
        global $conn;
        global $db_servername;
        global $db_username;
        global $db_password;
        global $db_name;

        if ($conn != NULL){
            // echo "Already Exists";
            return $conn;
        }else{
            $conn = mysqli_connect($db_servername,$db_username,$db_password,$db_name);
            if(!$conn){
                echo "Not Connected";
            }else{
                // echo "Connected Successfully\n";
                return $conn;
            }
        }
    }



<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/testapi/lib/database.php');

class Fetch{

    function __construct(){
        $this->db =getdbConnection();

    }

    function fetch(){
        $query ="SELECT username,email FROM apis.users;";
        $result =mysqli_query($this->db,$query);
        // var_dump($result);
        
        if($result){
            $data=array();
            $rowcount=mysqli_num_rows($result);
            if($rowcount>0){

            
                while($row = mysqli_fetch_array($result)){
                    // print("Username : " . $row['username']." Email : " . $row['email']."<br>");
                    $data['Username']=$row['username'];
                    $data['Email']=$row['email'];
                    echo json_encode($data);

                }
                
            }
            else{
                //No Entry in Database-1
                return 1;
            }

        }
        else{
            //Mysql Fail-2
            return 2;
        }
        
    }
}
<?php


require_once($_SERVER['DOCUMENT_ROOT'].'/testapi/Lib/Dbfetch.php');


${basename(__FILE__,'.php')}=function(){

            $a= new Fetch();
            $row=$a->fetch();
            if($row==1){
                $data=[
                    "Message"=>"No Entry in Database"
                ];
                $this->response($this->json($data),200);
            }elseif($row==2){
                $data=[
                    "Error"=>"Mysql Fail"
                ];
                $this->response($this->json($data),500);
            }else{
                $this->response('',200);
            }

};


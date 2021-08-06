<?php


require_once($_SERVER['DOCUMENT_ROOT'].'/testapi/Lib/olymp.class.php');


${basename(__FILE__,'.php')}=function(){

    if(($this->get_request_method()=="POST")){

        if($this->isAuthenticaed()){

            if(isset($this->_request['country'])){

                try{

                $a=new Olympics();
                $country=$_POST['country'];
                $data=$a->add_country($country);
                
                $data = $this->json($data);
                $this->response($data,200);
                }catch(Exception $e){
                    $data=[
                        "Error"=>$e->getMessage()
                    ];
                    $data = $this->json($data);
                    $this->response($data,409);
                }

            }

            else{
                $data=[
                    "Error"=>"Invalid Request"
                ];
                $data = $this->json($data);
                    $this->response($data,400);

            }
            
        }
        else{
            throw new Exception('Unauthorized');
        }
            

    }else{
        $data=[
            "Error"=>"Method Not Accepted"
        ];
        $data = $this->json($data);
            $this->response($data,405);
    }

    



            
};
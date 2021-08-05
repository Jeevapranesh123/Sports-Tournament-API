<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/testapi/Lib/olymp.class.php');


${basename(__FILE__,'.php')}=function(){

    if(($this->get_request_method()=="POST")){
        // if($this->isAuthenticaed()){

        if(isset($this->_request['Country']) and isset($this->_request['Game']) and isset($this->_request['Medal'])){
            // print_r($this->_request);
            if($this->isAuthenticaed()){
                try{
                    $a=new Olympics();
                    if($row=$a->add_medal($this->_request['Country'],$this->_request['Game'],$this->_request['Medal'])){
                        $data=[
                            "Message"=>"Medal Updated",
                            "Country"=>$this->_request['Country'],
                            "Game"=>$this->_request['Game'],
                            "Medal"=>$this->_request['Medal'],
                            "Gold"=>$row['Gold'],
                            "Silver"=>$row['Silver'],
                            "Bronze"=>$row['Bronze']
                        ];
                        $data = $this->json($data);
                        $this->response($data,200);
                    }
                }catch(Exception $e){
                    $data=[
                        "Error"=>$e->getMessage()
                        
                    ];
                    $data = $this->json($data);
                    $this->response($data,409);

                }
            
            }else{
                throw new Exception('Unauthorized');
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
        $data=[
            "Error"=>"Method Not Accepted"
        ];
        $data = $this->json($data);
            $this->response($data,405);
    }

    
};

<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/testapi/Lib/database.php');

Class Olympics{
    
    public function __construct(){
        $this->db =getdbConnection();
    }

    public function search($country){
        $query="SELECT * FROM `points` WHERE Country='$country'";
        $result=mysqli_query($this->db,$query);
        if($result){
            if(mysqli_num_rows($result)){
                $val=mysqli_fetch_assoc($result);
                $data=$this->list_medals($country,$val);
                return $data;
            
            }else{
                throw new Exception("Country Not Found");
            }
        }else{
            echo mysqli_error($this->db);
        }

    }

    public function list_medals($country,$val){

        $data=[
            "Country"=>$country,
                
            "Gold"=>[
                'Count'=>$val['Gold'],
                'Game'=>[]
            ],
            'Silver'=>[
                'Count'=>$val['Silver'],
                'Game'=>[]
            ],
            "Bronze"=>[
                'Count'=>$val['Bronze'],
                'Game'=>[]
            ]
        ];

        $query="SELECT * FROM `games` WHERE Country='$country' and `Medal`='Gold'";
        $result=mysqli_query($this->db,$query);
        if($result){
            
            $temp=array();
            while($row=mysqli_fetch_assoc($result)){
                $x=$row['Game'];
                array_push($temp,$x);  
            }

            foreach($temp as $m){
                array_push($data['Gold']['Game'],$m);  
            }
             
        }

        $query="SELECT * FROM `games` WHERE Country='$country' and `Medal`='Silver'";
        $result=mysqli_query($this->db,$query);
        if($result){
          
            $temp=array();
            while($row=mysqli_fetch_assoc($result)){
                $x=$row['Game'];
                array_push($temp,$x);  
            }

            foreach($temp as $m){
                array_push($data['Silver']['Game'],$m);  
            }
             
        }

        $query="SELECT * FROM `games` WHERE Country='$country' and `Medal`='Bronze'";
        $result=mysqli_query($this->db,$query);
        if($result){
        
            $temp=array();
            while($row=mysqli_fetch_assoc($result)){
                $x=$row['Game'];
                array_push($temp,$x);  
            }

            foreach($temp as $m){
                array_push($data['Bronze']['Game'],$m);  
            }
             
        }

        // $data=json_encode($data);
        // print_r($data);
        return $data;

}



    public function add_country($country){
        $query="INSERT INTO `points`(`Country`) VALUES ('$country')";
        $result=mysqli_query($this->db,$query);

        if($result){
            $last_id = mysqli_insert_id($this->db);
            $data=[
                "Message"=>"Country Added",
                "Country"=>$country,
                "Id"=>$last_id
            ];
            return $data;

        }elseif(mysqli_errno($this->db)==1062){
            throw new Exception("Country Already Exist");
        }
        else{
            echo "Shit";
        }
    }

    public function display(){
        // echo __FILE__;
        $query="SELECT * FROM `points` ORDER BY Gold DESC,Silver DESC,Bronze DESC";
        $result=mysqli_query($this->db,$query);
        if($result){
            $rowcount=mysqli_num_rows($result);
            if($rowcount>0){
                $arr = array();
                while($row=mysqli_fetch_assoc($result)){
                    $data = array(
                        "id" => $row['id'],
                        "Country" => $row['Country'],
                        "Gold" => $row['Gold'],
                        "Silver" => $row['Silver'],
                        "Bronze" => $row['Bronze'],
                        
                    );

                    array_push($arr, $data);

                }
            }
            
            return $arr;
        }else{
            echo mysqli_error($this->db);
        }
    }


    public function add_medal($country,$game,$medal){

        
        
            
            $query="SELECT * FROM `points` WHERE Country = '$country'";
            
            $result=mysqli_query($this->db,$query);
            if($result){
                $rowcount=mysqli_num_rows($result );
                

                if(mysqli_num_rows($result )==1){
                    

                    $row=mysqli_fetch_assoc($result);
                    $totalgold=$row['Gold'];
                    $totalsilver=$row['Silver'];
                    $totalbronze=$row['Bronze'];
                    
                    if($medal=="Gold" or $medal=="GOLD" or $medal=="gold"){
                        $totalgold=$totalgold+1;
                        
                    }
                    if($medal=="Silver" or $medal=="SILVER" or $medal=="silver"){
                        $totalsilver=$totalsilver+1;
                        
                    }
                    if($medal=="Bronze" or $medal=="Bronze" or $medal=="bronze"){
                        $totalbronze=$totalbronze+1;
                        
                    }

                    $query="UPDATE `points` SET `Gold`='$totalgold',`Silver`='$totalsilver',`Bronze`='$totalbronze' WHERE Country='$country'";
                    $result=mysqli_query($this->db,$query);
                    if($result){
                        $query="INSERT INTO `games`(`Country`, `Game`, `Medal`) VALUES ('$country','$game','$medal')";
        
                        $result=mysqli_query($this->db,$query);
                        if($result){
                            return array("Gold"=>$totalgold,"Silver"=>$totalsilver,"Bronze"=>$totalbronze);
                        }else {
                            echo mysqli_error($this->db)." 1 ";
                        }
                    }
                    else {
                        echo mysqli_error($this->db)." 2 ";
                    }
                }
                else {
                    throw new Exception('Country Not Found');
                }
            }
            else {
                echo mysqli_error($this->db)." 4 ";
            }
        



    }

    public function update(){

    }


    
}
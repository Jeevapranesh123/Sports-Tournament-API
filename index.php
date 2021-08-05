<?php
$servername = "localhost";
$username = "jeeva";
$password = "1234";
$db_name="Olympics";
$conn=NULL;
if ($conn != NULL){
  echo "Already Exists";
  return $conn;
}else{
  $conn = mysqli_connect($servername,$username,$password,$db_name);
}
  
?>

<!DOCTYPE html>
<html>
<head>
<style>
table, th, td {
  border: 1px solid black;
}
</style>
</head>
<body>

<h2>Olympics Medal list</h2>

<?php
echo '<table border="0" cellspacing="2" cellpadding="2"> 
        <tr> 
          <td> <font face="Arial">Id</font> </td> 
          <td> <font face="Arial">Country</font> </td> 
          <td> <font face="Arial">Gold</font> </td> 
          <td> <font face="Arial">Silver</font> </td> 
          <td> <font face="Arial">Bronze</font> </td> 
        </tr>
      ';
?>
<?php
      $query="SELECT * FROM `points` ORDER BY Gold DESC,Silver DESC,Bronze DESC ";
        $result=mysqli_query($conn,$query);
        if($result){
            $rowcount=mysqli_num_rows($result);
            if($rowcount>0){
              $i=1;
                while($row=mysqli_fetch_assoc($result)){
                  $field1name = $i;
                  $field2name = $row["Country"];
                  $field3name = $row["Gold"];
                  $field4name = $row["Silver"];
                  $field5name = $row["Bronze"]; 
                  $i=$i+1;
                  ?>
<?php               
echo '<tr> 
        <td>'.$field1name.'</td>
        <td>'.$field2name.'</td> 
        <td>'.$field3name.'</td> 
        <td>'.$field4name.'</td> 
        <td>'.$field5name.'</td> 
      </tr>
     
      ';
      ?>
                 <?php   
                }
                echo '</table>';
            }
          }
          ?>

</body>
</html>
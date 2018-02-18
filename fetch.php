<?php
//fetch.php
$connect = mysqli_connect("localhost", "root", "", "search_engine");
$output = '';


if(isset($_POST["query"]))
{
     $search = mysqli_real_escape_string($connect, $_POST["query"]);
     $query = "
      SELECT * FROM table2 
      WHERE title LIKE '%".$search."%'
      OR link LIKE '%".$search."%' 
 ";
}
else
{
 $query = "
  SELECT * FROM table2
 ";
}



    $result = mysqli_query($connect, $query);


if(mysqli_num_rows($result) > 0)
{
 $output .= '
  <div class="table-responsive">
   <table class="table table bordered">
    <tr>
    <th>Title</th>
     <th>Link</th>   
    </tr>
 ';



 while($row = mysqli_fetch_array($result))
 {
  $output = "
   <tr>
   <td>$row[title]</td>
    <td>
       <a target = \"blank\"  href = \"$row[link]\">$row[link]</a>
    </td>
    
   </tr>
  ";
 echo $output;
}

}
else
{
 echo 'No name found';
}

?>
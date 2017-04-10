<?php


session_start();

if (!isset($_SESSION["username"])) {  //Check whether the admin has logged in
    header("Location: login.html"); 
}



include '../inc/dbConnection.php';

$conn = getDBConnection("blockbuster");

include 'includes/phpfunctions.php';

function addMovie(){
    global $conn;
       $sql = "INSERT INTO movies(
       id,
       title,
       year,
       rating,
       runtime,
       genre,
       director, 
       review, 
       rentalCost,
       checkoutStatus
       )
       VALUES (
           :id,:title,:year,:rating,:runtime,:genre,:director,:review,:rentalCost,:checkoutStatus
           )";
    $movieArray = array() ;
    $movieArray[':id']= $_GET['id'];
    $movieArray[':title']= $_GET['title'];
    $movieArray[':year']= $_GET['year'];
    $movieArray[':rating']= $_GET['rating'];
    $movieArray[':runtime']= $_GET['runtime'];
    $movieArray[':genre']= $_GET['genre'];
    $movieArray[':director']= $_GET['director'];
    $movieArray[':review']= $_GET['review'];
    $movieArray[':rentalCost']= $_GET['rentalCost'];
    $movieArray[':checkoutStatus']= $_GET['checkoutStatus'];
    
    $stmt = $conn -> prepare ($sql);
    $stmt -> execute($movieArray);
}

?>


<!DOCTYPE html>
<html>
    <head>
        <title> Admin: Add New MOVIE </title>
    </head>
    <body>
        
        <h1> Add New Movie</h1>
        
        <form>
            
            Movie Id: <input type = "text" name = "id"/>
        
            <br/>

            Title: <input type = "text" name = "title"/>
            <br/>
            Year: <input type = "text" name = "year"/>
                        <br/>
            Rating:<select name = "rating">
                <option value = "G">G</option>
                <option value = "PG">PG</option>
                <option value = "PG-13">PG-13</option>
                <option value = "R">R</option>
                <option value = "NC-17">NC-17</option>
            </select>
            <br/>
            Runtime: <input type = "text" name = "runtime"/>
                        <br/>
            Genre: <input type = "text" name = "genre"/>
                        <br/>
            Director: <input type = "text" name = "director"/>
                        <br/>
            Review: <input type = "text" name = "review"/>
                        <br/>           
            Rental Cost: <input type = "text" name = "rentalCost"/>
                        <br/>
            CheckOut Status: 
              <input type="radio" name="checkoutStatus" value="1"> Available<br>
              <input type="radio" name="checkoutStatus" value="0"> Unavailable<br>
              
            <br/>
            
             <input type="submit" name="Submit"> 
        </form>
    <?php
        if (isset($_GET['Submit'])){
            //echo "form was submitted";
            addMovie();
            echo "THe user was added sucessfully!";
        }
    ?>



    </body>
</html>
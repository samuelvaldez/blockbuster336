<?php
session_start();

if (!isset($_SESSION["username"])) {  //Check whether the admin has logged in
    header("Location: login.html"); 
}

include '../inc/dbConnection.php';

$dbConn = getDBConnection("blockbuster");


function getAllMovies() {
    global $dbConn;
    $sql = "SELECT * FROM movies ORDER BY title";
    $statement = $dbConn->prepare($sql);
    $statement->execute();
    $records = $statement->fetchAll(PDO::FETCH_ASSOC);
    
    return $records;
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title> ADMIN PAGE </title>
    </head>
    <body>

        <h1> NOT ADMIN </h1>
        
        <h3> Welcome <?=$_SESSION['adminName']?> </h3>
        
        
        
        
        <hr>
        
        <form action="addNewMovie.php">
          <input type="submit" value="Add New Movie" />
        </form>
        
        
        
        
        <div style="float:left">
        <?php
        
        $movies = getAllMovies();
        
        
        foreach($movies as $movie){
            echo $movie['title'] . " ".  $movie['checkoutStatus'];
            echo "<a href='userUpdate.php?userId=". $user['userid']. "' >[Update] </a>";
        }
        
        ?>
       
        </div>
        
        <div style="float:right">
        <iframe src ="" width="400" height="400"name = "userInfoFrame"></iframe>
        </div>
    </body>
</html>
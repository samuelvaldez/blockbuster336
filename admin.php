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
        <title> ADMIN PANEL </title>
    </head>
    <body>

        <h1> Admin Panel </h1>
        
        <h3> Welcome <?=$_SESSION['adminName']?> </h3>

        <hr>
        
        <form action="addNewMovie.php">
          <input type="submit" value="Add New Movie" />
        </form>
        
        <div style="float:left">
        <table>
        <tr>
            <td>
                <strong>Movie Title</strong>
            </td>
            <td>
                <strong>Status</strong>
            </td>
            <td>
                <strong>Update</strong>
            </td>
        </tr>
        <?php
        
        $movies = getAllMovies();
        foreach($movies as $movie){
            echo "<tr>";
            echo "<td>";
            echo $movie['title'];
            echo "</td>";
            echo "<td>";
            echo $movie['checkoutStatus'];
            echo "</td>";
            echo "<td>";
            echo "<a href='movieUpdate.php?id=". $movie['id']. "' >[Update]</a>";
            echo "</td>";
            echo "</tr>";
        }
        
        ?>
        </table>
        
        </div>
    </body>
</html>
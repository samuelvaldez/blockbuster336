<?php

session_start();  //start or resume an existing session

/*  May be neded
if ($_SESSION['cartItems'] == null) {
    $_SESSION['cartItems'] = array();
    echo "Testing!";
}
*/

include '../inc/dbConnection.php';
$dbConn = getDBConnection("blockbuster");

function getAllVideoGames() {
    global $dbConn;
    $sql = "SELECT * FROM videoGames ORDER BY title";
    $statement = $dbConn->prepare($sql);
    $statement->execute();
    $records = $statement->fetchAll(PDO::FETCH_ASSOC);
    
    return $records;
}

// Adds the movie to the cart
if (isset($_GET['addToCart'])){
    addCartItem($_GET['itemToCart']);
    header("Location: shoppingCart.php");
}

function addCartItem($id) {
    global $dbConn;
    $sql = "SELECT * FROM movies WHERE id = " . $id;
    $statement = $dbConn->prepare($sql);
    $statement->execute();
    $record = $statement->fetch(PDO::FETCH_ASSOC);
    
    // Not working
    if (!in_array($record, $_SESSION)) {
        array_push ($_SESSION['cartItems'], $record);    
    }    
}


?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Blockbuster</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">

</head>
<body>

    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="mainPage.php">Blockbuster</a>
            </div>
            
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li>
                        <a href="movies.php">Movies</a>
                    </li>
                    <li>
                        <a href="vg.php">Video Games</a>
                    </li>
                    <li>
                        <a href="tv.php">TV</a>
                    </li>
                    <li>
                        <a href="search.php">Search</a>
                    </li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a href="shoppingCart.php">Cart</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <div class="container">

        <div class="row">
            <div class="col-lg-12 text-center">
                <h1>Check out our newest movies!</h1>
                <div>
                    <table class='table'>
                    <tr>
                        <td>
                            <strong>Movie Title</strong>
                        </td>
                        <td>
                            <strong>Status</strong>
                        </td>
                        <td>
                            <strong>Price</strong>
                        </td>
                    </tr>
                    <?php
                    
                    $videoGames = getAllVideoGames();
                    foreach($videoGames as $games){
                        echo "<tr>";
                        echo "<td>";
                        echo "<a href='vgInfo.php?id=" . $games['id'] . "' >" . $games['title'] . "</a>";
                        echo "</td>";
                        echo "<td>";
                        echo $games['checkoutStatus'];
                        echo "</td>";
                        echo "<td>";
                        echo $games['price'];
                        echo "</td>";
                        echo "<td>";
                        echo "<a href='?addToCart&itemToCart=" . $games['id'] . "' >Add to cart</a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                    
                    ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
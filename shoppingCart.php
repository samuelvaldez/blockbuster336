<?php

session_start();  //start or resume an existing session

if ($_SESSION['cartItems'] == null) {
    $_SESSION['cartItems'] = array();
}

include '../inc/dbConnection.php';
$dbConn = getDBConnection("blockbuster");

if(isset($_GET['id'])) {
    global $dbConn;
    $sql = "SELECT * FROM movies WHERE id = " . $_GET['id'];
    $statement = $dbConn->prepare($sql);
    $statement->execute();
    $record = $statement->fetch(PDO::FETCH_ASSOC);
    
    // Not working
    if (!in_array($record, $_SESSION)) {
        array_push ($_SESSION['cartItems'], $record);    
    }    
}

if (isset($_GET['removeItem'])) {
    unset($_SESSION['cartItems'][$_GET['key']]);
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
                <h1>Shopping Cart</h1>
                <div>
                   <?php
                    echo "<table class='table'>";
                    echo "<tr>";
                    echo "<td><strong>Title</strong></td>";
                    echo "<td><strong>Rental Cost</strong></td>";
                    echo "<td></td>";
                    echo "</tr>";
                    
                    $cartTotal = 0;
                    foreach($_SESSION['cartItems'] as $key => $item) {
                        $cartTotal += $item['rentalCost'];
                        
                        echo "<tr>";
                        echo "<td>" . $item['title'] . "</td>";
                        echo "<td>" . $item['rentalCost'] . "</td>";
                        echo "<td><a href='?removeItem&key=" . $key . "'>Remove from cart</a>";
                        echo "</tr>";
                    }
                    
                    echo "<tr>";
                    echo "<td></td>";
                    echo "<td>" . $cartTotal . "</td>";
                    echo "<td></td>";
                    echo "</tr>";
                    echo "</table>";
                    
                    ?>
                    
                </div>
            </div>
        </div>
    </div>
</body>
</html>
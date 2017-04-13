<?php

session_start();  //start or resume an existing session

include '../inc/dbConnection.php';
$dbConn = getDBConnection("blockbuster");

function getContent(){
    global $dbConn;
    
    // Named paramter array
    $nameOfarray = array();
    
    $sql = "select * from " . $_GET['type'] . " where title like :title";
    
    // Check if year is filled in
    if (!empty($_GET['year'])) {
        echo "Testing!";
        $nameOfarray[':year'] = $_GET['year'];
        $sql .= " and year = :year";
    }
    
    // Check if genre is filled in
    if (!empty($_GET['genre'])) {
        $nameOfarray[':genre'] = $_GET['genre'];
        $sql .= " and genre = :genre";
    }
    
    
    if($_GET['order'] == 'a'){
       
        $sql .= " ORDER BY title ASC";
    
    }else{
        $sql .= " ORDER BY title DESC";
    }

    
    $nameOfarray[':title'] = "%" . $_GET['title'] . "%";
    $statement = $dbConn->prepare($sql);
    $statement->execute($nameOfarray);
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
    if ($_GET['type'] == 'movies') {
        $sql = "SELECT * FROM movies WHERE id = " . $id;    
    } else if ($_GET['type'] == 'videoGames') {
        $sql = "SELECT * FROM videoGames WHERE id = " . $id;    
    } else {
        $sql = "SELECT * FROM shows WHERE id = " . $id;    
    }
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
            <div class="col-lg-2 text-center"></div>
            <div class="col-lg-8 text-center">
                <h1>Search</h1>
                <form>
                    <div class="form-group">
                        <label for="title" >Title</label>
                        <input type="text" name="title" class="form-control" id="title" placeholder="Title">
                    </div>
                    <div class="form-group">
                        <label for="year">Year</label>
                        <input type="text" name="year" class="form-control" id="year" placeholder="Year">
                    </div>
                    <div class="form-group">
                        <label for="genre">Genre</label>
                        <input type="text" name="genre" class="form-control" id="genre" placeholder="Genre">
                    </div>
                    
                    
                    <label class="radio-inline"><input type="radio" name="type" value="movies" checked>Movies</label>
                    <label class="radio-inline"><input type="radio" name="type" value="videoGames">Video Games</label>
                    <label class="radio-inline"><input type="radio" name="type" value="shows">Television</label>

                    <select name = "order">
                    <option value="a">A-Z</option>
                    <option value="z">Z-A</option>
                    
                    </select>
                    
                    <br /><br />
                    <button type="submit" name="Submit" class="btn btn-default">Submit</button>
                </form>
                <br />
                <?php
                    if (isset($_GET['Submit'])){
                        $content = getContent();
                        
                        // We will need to display different content depending on if they were
                        // searching for a game, movie, or tv show.
                        
                        echo "    <table class='table'>";
                        echo "<tr>";
                        echo "    <td>";
                        echo "        <strong>Movie Title</strong>";
                        echo "    </td>";
                        echo "    <td>";
                        echo "        <strong>Status</strong>";
                        echo "    </td>";
                        echo "    <td>";
                        echo "        <strong></strong>";
                        echo "    </td>";
                        echo "</tr>";
                        
                        foreach($content as $item){
                            echo "<tr>";
                            echo "<td>";
                            if ($_GET['type'] == 'movies') {
                                echo "<a href='movieInfo.php?id=" . $item['id'] . "' >" . $item['title'] . "</a>";    
                            } else if ($_GET['type'] == 'videoGames') {
                                echo "<a href='vgInfo.php?id=" . $item['id'] . "' >" . $item['title'] . "</a>";
                            } else {
                                echo "<a href='tvInfo.php?id=" . $item['id'] . "' >" . $item['title'] . "</a>";
                            }
                            echo "</td>";
                            echo "<td>";
                            echo $item['checkoutStatus'];
                            echo "</td>";
                            echo "<td>";
                            echo "<a href='?addToCart&itemToCart=" . $item['id'] . "&type=" . $_GET['type'] . "' >Add to cart</a>";
                            echo "</td>";
                            echo "</tr>";
                        }
                        echo "<table>";
                    }
                ?>
            </div>
        </div>
    </div>
</body>
</html>
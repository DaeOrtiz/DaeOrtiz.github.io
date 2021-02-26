<!--
	Author: Collom, Edward; Ortiz, Erick;
	Last Modified: 02/24/21
	Class: Senior Project 4950
-->
<?php
session_start();
 ?>

<!DOCTYPE html>
<html>
    <head>
        <title> Main Menu </title>
        <link rel="stylesheet" type="text/css" href="css/main.css"/>
    </head>
    <body>

<h1 id = "welcomeMessage"> Hello <?php echo $_SESSION["firstname"] ?> </h1>

        <li><a href="SignIn.php">Sign In</a></li>
        <h1>Test Main Menu</h1>
        <hr />
        <h2><a href="adminSection.php"> Admin Section </a></h2>
        <hr />
        <h2><a href="inventoryscreen.php">Inventory Management</a>  </h2>
        <hr />
        <h2> Warehouse Setup </h2>
        <hr />
        <h2> Reports </h2>
        <hr />
    </body>
</html>

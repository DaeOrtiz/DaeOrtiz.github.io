<!--
	Author: Collom, Edward; Ortiz, Erick;
	Last Modified: 02/24/21
	Class: Senior Project 4950
-->

<!DOCTYPE html>
<?php session_start(); ?>

<html>
<head>
<title>Admin Overview</title>
<link rel="stylesheet" type="text/css" href="css/main.css"/>
</head>
<body>

<h1> Hello <?php echo $_SESSION["firstname"] ?></h1>
<p>Welcome to the Admin Interface</p>


<table>
    <tr>
        <th>First Name </th>
        <td><?php echo $_SESSION["firstname"] ?> </td>
    </tr>
    <tr>
        <th>Last Name</th>
        <td><?php echo $_SESSION["lastname"] ?> </td>
    </tr>
    <tr>
        <th>User ID</th>
        <td> <?php echo $_SESSION["user"] ?> </td>
    </tr>
</table>

</body>
</html>

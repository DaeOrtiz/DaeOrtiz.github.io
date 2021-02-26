<!--
	Author: Collom, Edward; Ortiz, Erick;
	Last Modified: 02/24/21
	Class: Senior Project 4950
-->

<?php
    // Start the session
    session_start();
    //suppress simple errors, further explanation: https://www.php.net/manual/en/function.error-reporting.php
    error_reporting(E_ERROR | E_WARNING | E_PARSE);
    //prevent resending form inputs on refresh, further explanation: https://www.php.net/manual/en/function.ob-start.php
    ob_start()
?>

<!DOCTYPE html>
<html>
    <head>
    <title>Inventory Screen</title>
    	<meta name="viewport";
    	charset="UTF8";
    	content="width=device-width, initial-scale=1.0"> <!-- makes webpage responsive-->
        <link rel="stylesheet" type="text/css" href="css/main.css"/>
    </head>
    <body>
    	<ul>
    		<li><a href="inventoryscreen.php">Item Display</a></li>
    		<li><a href="MainMenu.php"> Main Menu </a></li>
    	</ul>
        <?php
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "inventorymanagement";

            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);
            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error . "<br>");
            }
            $sql = "SELECT * FROM item";
        	$return = $conn->query($sql);

        	//echo "This is return: ".$return;

        	if ($return->num_rows > 0) {
        	// output data of each row
        ?>

        <?php
            $description = $date = $descriptionErr = $dateErr = $quantityErr = $weightErr = "";
            $quantity = $weight = 0;
        ?>
        <div class="inputForm">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                <div class="description">
                    <label for="description">Item Description: </label><br>
                    <input type="text" name="description" value="">
                    <span class="error"><?php echo $descriptionErr;?></span>
                </div>
                <div class="formStats">
                    <label for="date">Expiration Date: </label><br>
                    <input type="date" name="date" value="">
                    <span class="error"><?php echo $dateErr;?></span>
                    <br><br>
                    <label for="quantity">Quantity: </label><br>
                    <input type="number" name="quantity" value="">
                    <span class="error"><?php echo $quantityErr;?></span>
                    <br><br>
                    <label for="weight">Weight in lbs.: </label><br>
                    <input type="number" name="weight" value="" step="0.01">
                    <span class="error"><?php echo $weightErr;?></span>
                    <br><br>
                </div>

                <br><br>
                <input type="submit" name="submit" value="Submit" class="inputButton">
            </form>
            <?php

                if (isset($_POST['submit'])){
                    $description = test_input($_POST["description"]);
                    $quantity = test_input($_POST["quantity"]);
                    $date = test_input($_POST["date"]);
                    $weight = test_input($_POST["weight"]);

                    submitFormPHP("item",
                        "item_name, item_quantity, item_expiration, item_weight",
                        "'".$description."', ".$quantity.", '".$date."', ".$weight.""
                        );
                }



            ?>
        </div>

        <table>
            <tr>
                <th>Item Desciption</th>
                <th>Expiration</th>
                <th>Quantity</th>
                <th>Weight</th>
                <th>Total Weight</th>
            </tr>

        <?php
        while($row = $return->fetch_assoc()) {
                ?>
                <tr>
                    <form method="post">
                            <td><h4 class="text-info"><?php echo $row["item_name"]; ?></h4></td>

                            <td><h4 class="text-info"><?php echo $row["item_expiration"]; ?></h4></td>

                            <td><h4 class="text-info"><?php echo $row["item_quantity"]; ?></h4></td>

                            <td><h4 class="text-info"><?php echo $row["item_weight"]; ?> lbs.</h4></td>

                            <td><h4 class="text-info"><?php echo ($row["item_weight"] * $row["item_quantity"]); ?> lbs.</h4></td>

                            <td>
                                <input type="number" name="quantity" value="1" class="form-control" />
                                <input type="submit" name="add_to_inventory" value="Add" />
                                <input type="submit" name="remove_from_inventory" value="Remove" />
                                <input type="hidden" id="itemId" name="itemId" value="<?php echo $row["item_id"]; ?>">
                            </td>
                    </form>
                </tr>
                <?php
        }
    	} else {
    		echo "Inventory could not be retrieved from database.";
    	}

        if (isset($_POST['add_to_inventory'])){
            updateQuantity($_POST['itemId'], $_POST['quantity'], FALSE);
        }
        if (isset($_POST['remove_from_inventory'])){
            updateQuantity($_POST['itemId'], $_POST['quantity'], TRUE);
        }

        #PHP Methods
        //Update the quantity of a particular item in the database
        function updateQuantity($item_id, $quantity, $negative)
        {
            $servername = "localhost";
        	$username = "root";
        	$password = "";
        	$dbname = "inventorymanagement";

        	// Create connection
        	$conn = new mysqli($servername, $username, $password, $dbname);
        	// Check connection
        	if ($conn->connect_error) {
        		die("Connection failed: " . $conn->connect_error . "<br>");
        	}

            if($negative){
                $quantity = $quantity * -1;
            }

            $sql = "
                    UPDATE item
                    SET item_quantity= item_quantity+".$quantity."
                    WHERE item_id=".$item_id."
                    ";

            if(mysqli_query($conn, $sql)){
                echo "Records inserted successfully.";
            } else{
                echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
            }
            header('Location: inventoryscreen.php');
        }

        //Insert new entries into the database
        function submitFormPHP($table, $query, $values)
        {
            #Check for empty or null values
            if (empty($_POST["description"])) {
				$descriptionErr = "* Description is required";
                return;
			}
            if (empty($_POST["date"])) {
				$dateErr = "* Date is required";
                return;
			}
            if (($_POST["quantity"]) == 0) {
				$quantityErr = "* Quantity is required";
                return;
			}
            if (($_POST["weight"]) == 0) {
				$weightErr = "* Weight is required";
                return;
			}

        	$servername = "localhost";
        	$username = "root";
        	$password = "";
        	$dbname = "inventorymanagement";

        	// Create connection
        	$conn = new mysqli($servername, $username, $password, $dbname);
        	// Check connection
        	if ($conn->connect_error) {
        		die("Connection failed: " . $conn->connect_error . "<br>");
        	}

            //check that the user is not attempting to create a duplicate entry in the database
            $sql = "SELECT * FROM item WHERE item_name='". $_POST["description"] ."'";
        	$return = $conn->query($sql);

        	//echo "This is return: ".$return;

            //if the entry is a duplicate
        	if ($return->num_rows > 0){
                echo "ERROR: Could not able to insert values. Duplicate entries should be placed in the same storage bin.";
                return;
            }


            //insert new entry into the database
        	$sql = "INSERT INTO ". $table ." (". $query .") VALUES (". $values .")";

            if(mysqli_query($conn, $sql)){
                echo "Records inserted successfully.";
            } else{
                echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
            }
            header('Location: inventoryscreen.php');
        }
        function test_input($data)
        {
        	$data = trim($data);
        	$data = stripslashes($data);
        	$data = htmlspecialchars($data);
        	return $data;
        }
    ?>

    </body>
</html>

<!--
	Author: Collom, Edward; Ortiz, Erick;
	Last Modified: 02/24/21
	Class: Senior Project 4950
-->
<?php
// Start the session
session_start();
?>

<!DOCTYPE html>
<html>
<head>
<title>Sign In</title>
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
		// define variables and set to empty values
		$passErr = $emailErr = "";
		$pass = $email = "";

		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			if (empty($_POST["pass"])) {
				$passErr = "Password is required";
			}
			else {
				$pass = test_input($_POST["pass"]);
				// check if pass only contains letters and whitespace
				if (!preg_match("/^[a-zA-Z ]*$/",$pass)) {
					$passErr = "Only letters and white space allowed";
				}
			}

			if (empty($_POST["email"])) {
				$emailErr = "Email is required";
			}
			else {
				$email = test_input($_POST["email"]);
				// check if e-mail address is well-formed
				if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
					$emailErr = "Invalid Username and/or Password";
				}
			}
		}

		function test_input($data) {
		  $data = trim($data);
		  $data = stripslashes($data);
		  $data = htmlspecialchars($data);
		  return $data;
		}

		function isValidLogin($email, $pass)
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

			//echo "The email is:". $email ."<br>";
			//echo "The password is:". $pass ."<br>";

			$sql = "SELECT id,firstname,lastname FROM users WHERE username =". $email ." AND password=". $pass;
			print $sql;
			$return = $conn->query($sql);

			if (mysqli_num_rows($return) > 0) {
	// output data of each row

while($row = mysqli_fetch_assoc($return)) {
    //print "This is the user id: " . $row["id"]. " This is their firstname" . $row["firstname"] "This is the lastname: " . $row["lastname"].;

		$_SESSION["user"] = $row["id"];
		$_SESSION["firstname"] = $row["firstname"];
		$_SESSION["lastname"] = $row["lastname"];
   }
	header('Location: MainMenu.php');
} else {
				//the login information was NOT correct
				echo $conn->error ."<br>";
				return 0;
		}
}
	?>


	<h2>Sign In</h2>
	<p><span class="error">* required field</span></p>
	<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		Username: <input type="text" name="email" value="<?php echo $email;?>">
		<span class="error">* <?php echo $emailErr;?></span>
		<br><br>
		Password: <input type="password" name="pass" value="<?php echo $pass;?>">
		<span class="error">* <?php echo $passErr;?></span>
		<br><br>

		<br><br>
		<input type="submit" name="submit" value="Submit">
	</form>

<?php
	isValidLogin("'".test_input($email)."'", "'".test_input($pass)."'");
?>

</body>
</html>

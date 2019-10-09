<?php
// Change this to your connection info.
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'phplogin';
// Try and connect using the info above.
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
	// If there is an error with the connection, stop the script and display the error.
	die ('Failed to connect to MySQL: ' . mysqli_connect_error());
}
if (!isset($_POST['username'], $_POST['password'], $_POST['email'])) {
	// Could not get the data that should have been sent.
	die ('Please complete the registration form!');
}
// Make sure the submitted registration values are not empty.
if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email'])) {
	// One or more values are empty.
	die ('Please complete the registration form');
}
if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
	die ('Email is not valid!');
}
if (preg_match('/[A-Za-z0-9]+/', $_POST['username']) == 0) {
    die ('Username is not valid!');
}
if (strlen($_POST['password']) > 20 || strlen($_POST['password']) < 5) {
	die ('Password must be between 5 and 20 characters long!');
}
$_len=strlen($_REQUEST['password']);
	$s1= $_REQUEST['username'];
	$s3= $_REQUEST['number'];
	$s4=$_REQUEST['password'];
    $_alphaSmall = $s4;            // small letters
    $_alphaCaps  = strtoupper($_alphaSmall);                // CAPITAL LETTERS
    $_numerics   = '0978654123';                            // numerics
	$_m ='19';
	$_y ='9887';
	$_d='012345';
    $_specialChars = '@$&~!*()-_=+|';   // Special Characters
    $p2=array();         // will contain the desired pass

    for($i = 0; $i < 4; $i++) { // Loop till the length mentioned
		if ($i==0) {
			$password1=  substr($_alphaCaps,0,1).substr($_alphaSmall,1,$_len).substr($s3,0,rand(0,9));
			$p2[]=$password1;
			$password1=' ';
		}
		elseif ($i==1){
			$password1= substr($_alphaCaps,0,1).substr($_alphaSmall,1,rand(2,$_len)).substr($_numerics,0,rand(1,8));
			$p2[]=$password1;
			$password1=' ';
		}
		elseif ($i==2){
			$password1= substr($s1,0,4).substr($_specialChars,0,1).$s3;
			$p2[]=$password1;
			$password1=' ';
			
		}
		else{
			$password1=substr($_alphaSmall,0,rand(3,$_len-4)).substr($_specialChars,0,rand(0,1)).substr($_m,0).substr($_y,0,rand(1,3));
			$password1=str_replace('s','$',$password1);
			$password1=str_replace('a','@',$password1);
			
			$p2[]=$password1;
			$password1=' ';
		}
		echo $p2[$i];
		echo "<br>";
		
		
	}
	//echo $_alphaSmall;
	$_pa=$p2[0];
	$_pa1=$p2[2];
	$_pa2=$p2[3];
	
	
	

if ($stmt = $con->prepare('SELECT id, password FROM accounts WHERE username = ?')) {
	// Bind parameters (s = string, i = int, b = blob, etc), hash the password using the PHP password_hash function.
	$stmt->bind_param('s', $_POST['username']);
	$stmt->execute();
	$stmt->store_result();
	// Store the result so we can check if the account exists in the database.
	if ($stmt->num_rows > 0) {
		// Username already exists
		echo 'Username exists, please choose another!';
	} else {
		// Insert new account
		$username = mysqli_real_escape_string($con, $_REQUEST['username']);
		$number = mysqli_real_escape_string($con, $_REQUEST['number']);
		$email = mysqli_real_escape_string($con, $_REQUEST['email']);
		$password = mysqli_real_escape_string($con, $_REQUEST['password']);
		$password1= password_hash($_POST['password'], PASSWORD_DEFAULT);
		$password2= password_hash($_pa, PASSWORD_DEFAULT);
		$password3= password_hash($_pa1, PASSWORD_DEFAULT);
		$password4= password_hash($_pa2, PASSWORD_DEFAULT);
 
		// Attempt insert query execution
		$sql = "INSERT INTO accounts(username,PhNo,email,password,password2,password3,password4) VALUES ('$username', '$number', '$email','$password1','$password2','$password3','$password4')";
		if(mysqli_query($con, $sql)){
			echo 'You have successfully registered, you can now login!';
}
	}
	$stmt->close();
} else {
	// Something is wrong with the sql statement, check to make sure accounts table exists with all 3 fields.
	echo 'Could not prepare statement!';
}
$con->close();
?>
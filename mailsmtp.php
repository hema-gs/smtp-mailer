<?php

require_once "Mail.php";

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "employee";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
$sql = "SELECT * FROM `employees` where DATE_FORMAT(dob, '%m-%d') >= DATE_FORMAT(NOW(), '%m-%d')";
$result = $conn->query($sql);
$from = 'apa.engch@gmail.com';

$smtp = Mail::factory('smtp', array(
	'host' => 'ssl://smtp.gmail.com',
	'port' => '465',
	'auth' => true,
	'username' => 'apa.engch@gmail.com',
	'password' => 'apaeng2018'
));
$actualLink = "http://$_SERVER[HTTP_HOST]";
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "id: " . $row["employee_id"]. " - Name: " . $row["emp_name"]. " " . $row["email"]. "<br>";
		$to = $row["email"];
		$subject = 'Happy Birthday!! - '. $row["emp_name"];
		$userAvatar = $actualLink.'/avatar/'.$row["employee_id"].'.jpg';
		$body = '<div style="width:80%;align-content: center;border: 2px solid red;min-height: 250px;">
					<div style="float:left; padding-left: 15px;">
					<h2 style="color:blue">APS Wishes</h2>
					<h3 style="color:orange"><p>'.$row["emp_name"].'( '.date('d-M-Y', strtotime($row["dob"])).')</p>
					<p style="color:blue">A Very Happy Birthday!</p>
					<p style="color:blue">Have a great day & glorious year ahead</p></h3>
					</div>
					<div style="float:right;  padding-right: 15px; padding-top:15px;"><img width=200 height=160 src="$userAvatar" /></div>
				</div>';
		$headers = array(
			'From' => $from,
			'To' => $to,
			'Subject' => $subject,
			'Content-Type' => 'text/html; charset=iso-8859-1 '
		);
		$mail = $smtp->send($to, $headers, $body);
		if (PEAR::isError($mail)) {
			echo('<p>' . $mail->getMessage() . '</p>');
		} else {
			echo('<p>Message successfully sent!</p>');
		}
    }
} else {
    echo "0 results";
}
$conn->close();
echo "Connected successfully";
?>
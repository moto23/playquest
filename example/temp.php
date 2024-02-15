<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$database = "playquest";

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Your SQL query to fetch the image (replace 'your_table' and 'your_id' with your actual table and ID)
$sql = "SELECT question_image FROM question WHERE question_id =2";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // Set the appropriate headers to indicate it's an image
    header("Content-type: image/*"); // Change to the appropriate image format (jpeg, png, gif, etc.)

    // Output the image data
    echo $row["question_image"];
}

// Close the database connection
$conn->close();
?>


<?php
    // Connect to the database
    $conn = new mysqli("host", "username", "password", "database");

    // Prepare the message data
    $sender_id = $_POST['sender_id'];
    $receiver_id = $_POST['receiver_id'];
    $message = $_POST['message'];
    $timestamp = date("Y-m-d H:i:s");

    // Insert the message into the database
    $sql = "INSERT INTO messages (sender_id, receiver_id, message, timestamp) 
    VALUES ('$sender_id', '$receiver_id', '$message', '$timestamp')";
    $conn->query($sql);

    // Close the database connection
    $conn->close();
?>

<?php
    // Connect to the database
    $conn = new mysqli("host", "username", "password", "database");

    // Prepare the user IDs
    $sender_id = $_GET['sender_id'];
    $receiver_id = $_GET['receiver_id'];

    // Retrieve the messages from the database
    $sql = "SELECT * FROM messages WHERE (sender_id='$sender_id' AND receiver_id='$receiver_id') 
    OR (sender_id='$receiver_id' AND receiver_id='$sender_id')";
    $result = $conn->query($sql);

    // Format the messages as an array
    $messages = array();
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }

    // Close the database connection
    $conn->close();
?>
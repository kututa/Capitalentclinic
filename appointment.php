<?php




// END OF PHP MAILER CODE 

// Replace these values with your actual database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hospital";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $date = $_POST["date"];
    $department = $_POST["department"];
    $doctor = $_POST["doctor"];
    $message = $_POST["message"];

    // Set recipient email address
    $to = "vinnykututa@gmail.com";  // Replace with your actual email address

    // Subject for your email
    $subject = "New Appointment Request";

    // Build the email content
    $email_content = "Name: $name\n";
    $email_content .= "Email: $email\n";
    $email_content .= "Phone: $phone\n";
    $email_content .= "Date: $date\n";
    $email_content .= "Department: $department\n";
    $email_content .= "Doctor: $doctor\n";
    $email_content .= "Message: $message\n";

    // Additional headers
    $headers = "From: $email" . "\r\n" .
               "Reply-To: $email" . "\r\n" .
               "X-Mailer: PHP/" . phpversion();

    // Send the email to your address
    mail($to, $subject, $email_content, $headers);

    // Send a confirmation email to the user
    $user_subject = "Appointment Request Confirmation";
    $user_message = "Dear $name,\n\nThank you for your appointment request. We will get back to you shortly.\n\nBest regards,\nCapitalentclinic-fortis";
    mail($email, $user_subject, $user_message, $headers);

    // Insert data into the database using prepared statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO appointments (name, email, phone, appointment_date, department, doctor, message) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $name, $email, $phone, $date, $department, $doctor, $message);

    if ($stmt->execute()) {
        echo "Appointment booking successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

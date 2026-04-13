<?php
// Handle contact form submission
header('Content-Type: application/json');

// Only accept POST requests
if($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Sanitize input
$name = htmlspecialchars(trim($_POST['name'] ?? ''));
$email = htmlspecialchars(trim($_POST['email'] ?? ''));
$subject = htmlspecialchars(trim($_POST['subject'] ?? ''));
$message = htmlspecialchars(trim($_POST['message'] ?? ''));

// Validate fields
$errors = [];

if(empty($name)) {
    $errors['name'] = 'Name is required';
}

if(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Valid email is required';
}

if(empty($subject)) {
    $errors['subject'] = 'Subject is required';
}

if(empty($message)) {
    $errors['message'] = 'Message is required';
}

if(!empty($errors)) {
    http_response_code(400);
    echo json_encode($errors);
    exit;
}

// Email configuration
$to = 'lucas.g.greulich@gmail.com';
$headers = "From: " . $email . "\r\n";
$headers .= "Reply-To: " . $email . "\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

$email_subject = "[Portfolio Contact] " . $subject;
$email_body = "You have received a new message from your portfolio contact form.\n\n";
$email_body .= "Name: " . $name . "\n";
$email_body .= "Email: " . $email . "\n";
$email_body .= "Subject: " . $subject . "\n\n";
$email_body .= "Message:\n" . $message;

// Send email
if(mail($to, $email_subject, $email_body, $headers)) {
    http_response_code(200);
    echo json_encode(['success' => 'Message sent successfully']);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to send message']);
}
?>

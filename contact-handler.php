<?php
/**
 * Contact Form Handler for Pittsfield AASR Website
 * Handles form submissions and sends emails
 */

// Error reporting for debugging (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 0); // Set to 1 for debugging

// Load configuration
$config = require_once 'config.php';

// Set response headers
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

/**
 * Send JSON response and exit
 */
function sendResponse($success, $message, $data = []) {
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ]);
    exit();
}

/**
 * Validate and sanitize input
 */
function validateInput($data, $config) {
    $errors = [];
    
    // Check required fields
    foreach ($config['validation']['required_fields'] as $field) {
        if (!isset($data[$field]) || empty(trim($data[$field]))) {
            $errors[] = ucfirst($field) . ' is required.';
        }
    }
    
    // Validate email
    if (isset($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }
    
    // Validate subject
    if (isset($data['subject']) && !array_key_exists($data['subject'], $config['validation']['allowed_subjects'])) {
        $errors[] = 'Please select a valid subject.';
    }
    
    // Check message length
    if (isset($data['message']) && strlen($data['message']) > $config['validation']['max_message_length']) {
        $errors[] = 'Message is too long. Maximum ' . $config['validation']['max_message_length'] . ' characters allowed.';
    }
    
    return $errors;
}

/**
 * Check rate limiting
 */
function checkRateLimit($config) {
    if (!$config['security']['rate_limit']['enabled']) {
        return true;
    }
    
    $ip = $_SERVER['REMOTE_ADDR'];
    $rate_file = sys_get_temp_dir() . '/contact_rate_' . md5($ip);
    
    $current_time = time();
    $submissions = [];
    
    // Read existing submissions
    if (file_exists($rate_file)) {
        $submissions = json_decode(file_get_contents($rate_file), true) ?: [];
    }
    
    // Filter submissions within time window
    $submissions = array_filter($submissions, function($timestamp) use ($current_time, $config) {
        return ($current_time - $timestamp) < $config['security']['rate_limit']['time_window'];
    });
    
    // Check if limit exceeded
    if (count($submissions) >= $config['security']['rate_limit']['max_submissions']) {
        return false;
    }
    
    // Add current submission
    $submissions[] = $current_time;
    file_put_contents($rate_file, json_encode($submissions));
    
    return true;
}

/**
 * Send email using PHP mail() function
 */
function sendEmailPHP($to, $subject, $message, $headers) {
    return mail($to, $subject, $message, $headers);
}

/**
 * Send email using SMTP (requires PHPMailer or similar)
 * This is a basic implementation - you may want to use PHPMailer for production
 */
function sendEmailSMTP($config, $to, $subject, $message, $from_email, $from_name) {
    // For SMTP, you would typically use PHPMailer
    // This is a placeholder - install PHPMailer via Composer for production use
    /*
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    
    $mail = new PHPMailer(true);
    
    try {
        $mail->isSMTP();
        $mail->Host = $config['smtp']['host'];
        $mail->SMTPAuth = $config['smtp']['auth'];
        $mail->Username = $config['smtp']['username'];
        $mail->Password = $config['smtp']['password'];
        $mail->SMTPSecure = $config['smtp']['encryption'];
        $mail->Port = $config['smtp']['port'];
        
        $mail->setFrom($from_email, $from_name);
        $mail->addAddress($to);
        $mail->Subject = $subject;
        $mail->Body = $message;
        
        return $mail->send();
    } catch (Exception $e) {
        error_log("SMTP Error: " . $mail->ErrorInfo);
        return false;
    }
    */
    
    // Fallback to PHP mail() if SMTP is not properly configured
    $headers = "From: $from_name <$from_email>\r\n";
    $headers .= "Reply-To: $from_email\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    
    return mail($to, $subject, $message, $headers);
}

// Main processing starts here
try {
    // Only accept POST requests
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        sendResponse(false, 'Invalid request method.');
    }
    
    // Check rate limiting
    if (!checkRateLimit($config)) {
        sendResponse(false, 'Too many submissions. Please try again later.');
    }
    
    // Get and decode input data
    $input = file_get_contents('php://input');
    $data = [];
    
    // Handle both JSON and form data
    if (!empty($input)) {
        $json_data = json_decode($input, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            $data = $json_data;
        }
    }
    
    // Fallback to POST data
    if (empty($data)) {
        $data = $_POST;
    }
    
    // Validate input
    $errors = validateInput($data, $config);
    if (!empty($errors)) {
        sendResponse(false, 'Validation errors: ' . implode(' ', $errors));
    }
    
    // Sanitize input
    $name = htmlspecialchars(trim($data['name']), ENT_QUOTES, 'UTF-8');
    $email = filter_var(trim($data['email']), FILTER_SANITIZE_EMAIL);
    $lodge = isset($data['lodge']) ? htmlspecialchars(trim($data['lodge']), ENT_QUOTES, 'UTF-8') : '';
    $subject_key = $data['subject'];
    $subject_text = $config['validation']['allowed_subjects'][$subject_key];
    $message = htmlspecialchars(trim($data['message']), ENT_QUOTES, 'UTF-8');
    
    // Prepare email content
    $email_subject = $config['email']['subject_prefix'] . $subject_text;
    
    $email_body = "New contact form submission from the Pittsfield AASR website:\n\n";
    $email_body .= "Name: $name\n";
    $email_body .= "Email: $email\n";
    if (!empty($lodge)) {
        $email_body .= "Blue Lodge: $lodge\n";
    }
    $email_body .= "Subject: $subject_text\n";
    $email_body .= "Submitted: " . date('Y-m-d H:i:s') . "\n\n";
    $email_body .= "Message:\n";
    $email_body .= wordwrap($message, 70) . "\n\n";
    $email_body .= "---\n";
    $email_body .= "This message was sent via the contact form at pittsfield-aasr.org\n";
    $email_body .= "Sender IP: " . $_SERVER['REMOTE_ADDR'] . "\n";
    
    // Send main email
    $email_sent = false;
    
    if ($config['smtp']['use_smtp']) {
        $email_sent = sendEmailSMTP(
            $config,
            $config['email']['to'],
            $email_subject,
            $email_body,
            $config['email']['from_email'],
            $config['email']['from_name']
        );
    } else {
        // Use PHP mail()
        $headers = "From: " . $config['email']['from_name'] . " <" . $config['email']['from_email'] . ">\r\n";
        $headers .= "Reply-To: $name <$email>\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
        
        $email_sent = mail($config['email']['to'], $email_subject, $email_body, $headers);
    }
    
    if (!$email_sent) {
        throw new Exception('Failed to send email.');
    }
    
    // Send auto-reply if enabled
    if ($config['notifications']['send_auto_reply']) {
        $auto_reply_headers = "From: " . $config['email']['from_name'] . " <" . $config['email']['from_email'] . ">\r\n";
        $auto_reply_headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
        
        mail(
            $email,
            $config['notifications']['auto_reply_subject'],
            $config['notifications']['auto_reply_message'],
            $auto_reply_headers
        );
    }
    
    // Log successful submission
    error_log("Contact form submission from $name ($email) - Subject: $subject_text");
    
    sendResponse(true, 'Thank you for your message. We will respond within 2-3 business days.');
    
} catch (Exception $e) {
    error_log("Contact form error: " . $e->getMessage());
    sendResponse(false, 'Sorry, there was an error sending your message. Please try again later.');
}
?>
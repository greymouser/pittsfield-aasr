<?php
// Configuration file for Pittsfield AASR Contact Form
// Edit these values to match your server settings

return [
    // Email settings
    'email' => [
        'to' => 'secretary@pittsfield-aasr.org',
        'from_name' => 'Pittsfield AASR Website',
        'from_email' => 'noreply@pittsfield-aasr.org',
        'subject_prefix' => '[Pittsfield AASR] ',
    ],
    
    // SMTP settings (if using SMTP instead of PHP mail())
    // Set 'use_smtp' to true to enable SMTP
    'smtp' => [
        'use_smtp' => false,  // Set to true to use SMTP, false to use PHP mail()
        'host' => 'smtp.yourmailserver.com',
        'port' => 587,
        'username' => 'your-smtp-username',
        'password' => 'your-smtp-password',
        'encryption' => 'tls', // 'tls' or 'ssl'
        'auth' => true,
    ],
    
    // Security settings
    'security' => [
        'allowed_origins' => [
            'https://pittsfield-aasr.org',
            'https://www.pittsfield-aasr.org',
            'http://localhost', // For local testing
        ],
        'rate_limit' => [
            'enabled' => true,
            'max_submissions' => 5, // Max submissions per IP per hour
            'time_window' => 3600, // Time window in seconds (1 hour)
        ],
    ],
    
    // Notification settings
    'notifications' => [
        'send_auto_reply' => true,
        'auto_reply_subject' => 'Thank you for contacting the Valley of Pittsfield',
        'auto_reply_message' => 'Thank you for your inquiry to the Valley of Pittsfield, Ancient and Accepted Scottish Rite. We have received your message and will respond within 2-3 business days.

Best regards,
The Valley of Pittsfield AASR',
    ],
    
    // Form validation
    'validation' => [
        'required_fields' => ['name', 'email', 'subject', 'message'],
        'max_message_length' => 2000,
        'allowed_subjects' => [
            'membership' => 'Membership Information',
            'meetings' => 'Meeting Schedule',
            'degrees' => 'Degree Work',
            'general' => 'General Inquiry',
            'other' => 'Other',
        ],
    ],
];
?>
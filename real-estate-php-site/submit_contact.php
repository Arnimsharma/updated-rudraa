<?php
require_once __DIR__ . '/includes/functions.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/PHPMailer/src/Exception.php';
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';

$errors = [];
$success = false;
$propertyId = $_POST['property_id'] ?? 'Not specified';
$propertyTitle = $_POST['property_title'] ?? 'Not specified';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $message = trim($_POST['message'] ?? '');

    // Basic validation
    if ($name === '' || $email === '' || $phone === '' || $message === '') {
        $errors[] = "All fields are required.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email address.";
    }

    if (!$errors) {
        // Save contact in JSON
        $contactData = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'message' => $message,
            'property_id' => $propertyId,
            'property_title' => $propertyTitle,
            'type' => 'property_inquiry'
        ];
        save_contact($contactData);

        // Send email using PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'arnimsharma090@gmail.com';
            $mail->Password = 'bhyu ewrt ctbh vuwc';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('arnimsharma090@gmail.com', 'Rudraa Housing Website');
            $mail->addAddress('arnimsharma90@gmail.com', 'Admin');

            $mail->isHTML(true);
            $mail->Subject = "New Property Inquiry from $name";
            $mail->Body = "
                <h2>New Property Inquiry</h2>
                <p><strong>Name:</strong> $name</p>
                <p><strong>Email:</strong> $email</p>
                <p><strong>Phone:</strong> $phone</p>
                <p><strong>Message:</strong><br>$message</p>
                <p><strong>Property:</strong> $propertyTitle (#$propertyId)</p>
            ";

            $mail->send();
            $success = true;
        } catch (Exception $e) {
            $errors[] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}

// Validate $propertyId for redirect
$redirectUrl = $propertyId !== 'Not specified' ? "property.php?id=" . urlencode($propertyId) : "index.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submission Status - Rudraa Housing India</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeInUp {
            animation: fadeInUp 0.5s ease-out forwards;
        }
        .custom-teal {
            background-color: #008080; /* Teal for buttons and backgrounds */
        }
        .custom-teal-hover:hover {
            background-color: #006666; /* Darker teal for hover */
        }
        .custom-teal-focus {
            --tw-ring-color: #008080; /* Teal focus ring */
        }
        .text-teal {
            color: #008080; /* Teal text */
        }
        .bg-submission-hero {
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('https://via.placeholder.com/1920x1080?text=Submission+Status+Hero') center/cover no-repeat; /* Replace with actual image */
        }
        .alert {
            margin: 1.5rem auto;
            padding: 1rem;
            border-radius: 8px;
            font-size: 0.95rem;
            text-align: center;
            max-width: 600px;
        }
        .alert.success {
            border-left: 4px solid #28a745;
            background: #e9f9ef;
            color: #1f7a36;
        }
        .alert.error {
            border-left: 4px solid #cc0000;
            background: #ffecec;
            color: #a30000;
        }
        .popup {
            display: <?php echo $success ? 'flex' : 'none'; ?>;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 50;
            align-items: center;
            justify-content: center;
        }
        .popup-content {
            background: linear-gradient(135deg, #ffffff, #e6fafa); /* Subtle teal gradient */
            max-width: 450px;
            margin: 0 auto;
            padding: 32px;
            border-radius: 16px;
            text-align: center;
            box-shadow: 0 8px 24px rgba(0, 128, 128, 0.2);
        }
        .popup-content h2 {
            font-size: 1.75rem;
            font-weight: bold;
            color: #008080;
            margin-bottom: 1rem;
        }
        .popup-content p {
            color: #4b5563;
            font-size: 1rem;
            margin-bottom: 1rem;
        }
        .popup-content a {
            display: inline-block;
            padding: 12px 24px;
            background: #008080;
            color: white;
            border-radius: 8px;
            font-weight: 600;
            transition: background 0.3s ease, transform 0.2s ease;
        }
        .popup-content a:hover {
            background: #006666;
            transform: translateY(-2px);
        }
        /* Loader */
        .loader {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.6);
            z-index: 100;
            align-items: center;
            justify-content: center;
        }
        .loader.show {
            display: flex;
        }
        .loader-content {
            background: linear-gradient(135deg, #ffffff, #e6fafa); /* Subtle teal gradient */
            padding: 24px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 8px 24px rgba(0, 128, 128, 0.2);
        }
        .building-loader {
            width: 80px;
            height: 80px;
            position: relative;
            margin: 0 auto 12px;
        }
        .building {
            width: 50px;
            height: 50px;
            background: #008080;
            position: absolute;
            bottom: 0;
            left: 15px;
            border-radius: 4px;
            animation: buildUp 1.5s infinite ease-in-out;
        }
        .crane {
            width: 12px;
            height: 24px;
            background: #006666;
            position: absolute;
            top: 0;
            left: 34px;
            border-radius: 2px;
            animation: craneMove 1.5s infinite ease-in-out;
        }
        @keyframes buildUp {
            0% { height: 15px; }
            50% { height: 50px; }
            100% { height: 15px; }
        }
        @keyframes craneMove {
            0% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
            100% { transform: translateY(0); }
        }
    </style>
</head>
<body class="font-sans antialiased bg-white">
    <?php include __DIR__ . '/includes/header.php'; ?>

    <!-- Hero Section -->
    <section class="bg-submission-hero min-h-[50vh] flex items-center justify-center text-white">
        <div class="container mx-auto px-4 text-center animate-fadeInUp">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Submission Status</h1>
            <p class="text-lg md:text-xl max-w-2xl mx-auto">
                Thank you for reaching out about <?php echo htmlspecialchars($propertyTitle); ?>. We'll get back to you soon.
            </p>
        </div>
    </section>

    <!-- Submission Status Section -->
    <section class="min-h-[50vh] flex items-center justify-center">
        <div id="loader" class="loader">
            <div class="loader-content animate-fadeInUp">
                <div class="building-loader">
                    <div class="building"></div>
                    <div class="crane"></div>
                </div>
                <p class="text-teal font-semibold text-lg">Processing Your Request...</p>
            </div>
        </div>

        <?php if ($success): ?>
            <div class="popup">
                <div class="popup-content animate-fadeInUp">
                    <h2>Thank You!</h2>
                    <p>Your inquiry about <?php echo htmlspecialchars($propertyTitle); ?> has been received. We'll get back to you soon.</p>
                    <p>Redirecting back to the property page...</p>
                    <a href="<?php echo htmlspecialchars($redirectUrl); ?>" class="custom-teal-hover">Return Now</a>
                </div>
            </div>
            <script>
                setTimeout(() => {
                    window.location.href = '<?php echo htmlspecialchars($redirectUrl); ?>';
                }, 3000);
            </script>
        <?php else: ?>
            <div class="alert error"><?php echo implode(' • ', array_map('htmlspecialchars', $errors)); ?></div>
            <div class="text-center">
                <a href="<?php echo htmlspecialchars($redirectUrl); ?>" class="inline-block custom-teal text-white py-3 px-8 rounded-lg custom-teal-hover transition">Back to Property</a>
            </div>
        <?php endif; ?>
    </section>

    <?php include __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
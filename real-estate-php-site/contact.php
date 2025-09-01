<?php
require_once __DIR__ . '/includes/functions.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer manually (no Composer needed)
require __DIR__ . '/PHPMailer/src/Exception.php';
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';

$errors = [];
$success = false;

// Get property info from GET or POST
$propertyId = $_POST['property_id'] ?? $_GET['property_id'] ?? 'Not specified';
$propertyTitle = $_POST['property_title'] ?? $_GET['property_title'] ?? 'Not specified';

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
            'property_title' => $propertyTitle
        ];
        save_contact($contactData);

        // Send email using PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'arnimsharma090@gmail.com'; // Sender Gmail
            $mail->Password = 'bhyu ewrt ctbh vuwc'; // App Password
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Rudraa Housing India</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeInUp {
            animation: fadeInUp 0.5s ease-out forwards;
        }
        .hover-scale {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .hover-scale:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 20px rgba(0, 128, 128, 0.3); /* Teal shadow */
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
        .bg-contact-hero {
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('https://via.placeholder.com/1920x1080?text=Contact+Us+Hero') center/cover no-repeat; /* Replace with actual image */
        }
        /* Form Styles */
        .contact-section {
            max-width: 600px;
            margin: 60px auto;
            padding: 0 16px;
        }
        .contact-card {
            background: linear-gradient(135deg, #ffffff, #e6fafa); /* Subtle teal gradient */
            padding: 32px;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(0, 128, 128, 0.15);
            transition: transform 0.3s ease;
        }
        .contact-card:hover {
            transform: translateY(-5px);
        }
        .contact-card h1 {
            font-size: 2.5rem;
            font-weight: bold;
            text-align: center;
            margin-bottom: 1.5rem;
            color: #008080;
        }
        .contact-card label {
            display: block;
            font-size: 1.1rem;
            font-weight: 600;
            color: #1f2937; /* text-gray-800 */
            margin-bottom: 0.5rem;
        }
        .contact-card input,
        .contact-card textarea {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #d1d5db; /* border-gray-300 */
            border-radius: 8px;
            font-size: 1rem;
            color: #1f2937;
            background: #f8fafc; /* bg-gray-50 */
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }
        .contact-card input::placeholder,
        .contact-card textarea::placeholder {
            color: #6b7280; /* placeholder-gray-500 */
        }
        .contact-card input:focus,
        .contact-card textarea:focus {
            border-color: #008080;
            box-shadow: 0 0 0 3px rgba(0, 128, 128, 0.1);
            outline: none;
        }
        .contact-card textarea {
            resize: vertical;
            min-height: 120px;
        }
        .contact-card button {
            width: 100%;
            padding: 14px;
            background: #008080;
            color: white;
            font-weight: 600;
            font-size: 1.1rem;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s ease;
        }
        .contact-card button:hover {
            background: #006666;
            transform: translateY(-2px);
        }
        /* Alerts */
        .alert {
            margin-bottom: 1.5rem;
            padding: 1rem;
            border-radius: 8px;
            font-size: 0.95rem;
            text-align: center;
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
        /* Popup */
        .popup {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 50;
        }
        .popup-content {
            background: linear-gradient(135deg, #ffffff, #e6fafa); /* Subtle teal gradient */
            max-width: 450px;
            margin: 100px auto;
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
        .popup.show {
            display: flex;
            align-items: center;
            justify-content: center;
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
    <section class="bg-contact-hero min-h-[50vh] flex items-center justify-center text-white">
        <div class="container mx-auto px-4 text-center animate-fadeInUp">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Get in Touch with Rudraa Housing</h1>
            <p class="text-lg md:text-xl max-w-2xl mx-auto">
                Have questions about <?php echo htmlspecialchars($propertyTitle); ?> or other properties? We're here to help you find your dream home.
            </p>
        </div>
    </section>

    <!-- Contact Form Section -->
    <section class="contact-section">
        <div id="loader" class="loader">
            <div class="loader-content animate-fadeInUp">
                <div class="building-loader">
                    <div class="building"></div>
                    <div class="crane"></div>
                </div>
                <p class="text-teal font-semibold text-lg">Building Your Request...</p>
            </div>
        </div>

        <?php if ($success): ?>
            <div id="thank-you-popup" class="popup show">
                <div class="popup-content animate-fadeInUp">
                    <h2>Thank You!</h2>
                    <p>Your inquiry has been received. We’ll get back to you soon.</p>
                    <p>Redirecting back to the property page...</p>
                </div>
            </div>
            <script>
                setTimeout(() => {
                    window.location.href = 'property.php?id=<?php echo urlencode($propertyId); ?>';
                }, 3000);
            </script>
        <?php else: ?>
            <?php if ($errors): ?>
                <div class="alert error"><?php echo implode(' • ', array_map('htmlspecialchars', $errors)); ?></div>
            <?php endif; ?>

            <div class="contact-card animate-fadeInUp">
                <h1>Inquire About <?php echo htmlspecialchars($propertyTitle); ?></h1>
                <form id="contact-form" method="post" action="" class="space-y-6">
                    <input type="hidden" name="property_id" value="<?php echo htmlspecialchars($propertyId); ?>">
                    <input type="hidden" name="property_title" value="<?php echo htmlspecialchars($propertyTitle); ?>">

                    <div>
                        <label for="name">Your Name</label>
                        <input type="text" name="name" placeholder="Enter your name" required>
                    </div>
                    <div>
                        <label for="email">Your Email</label>
                        <input type="email" name="email" placeholder="Enter your email" required>
                    </div>
                    <div>
                        <label for="phone">Your Phone</label>
                        <input type="text" name="phone" placeholder="Enter your phone number" required>
                    </div>
                    <div>
                        <label for="message">Your Message</label>
                        <textarea name="message" placeholder="Tell us about your inquiry" required></textarea>
                    </div>
                    <button type="submit" class="hover-scale">Send Inquiry</button>
                </form>
                <script>
                    document.getElementById('contact-form').addEventListener('submit', function() {
                        document.getElementById('loader').classList.add('show');
                    });
                </script>
            </div>
        <?php endif; ?>
    </section>

    <?php include __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
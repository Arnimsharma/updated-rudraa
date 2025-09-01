<?php
$base = dirname($_SERVER['SCRIPT_NAME']);
if ($base === DIRECTORY_SEPARATOR) $base = '';

require_once __DIR__ . '/includes/functions.php';
require __DIR__ . '/PHPMailer/src/Exception.php';
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$errors = [];
$success = false;

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
            'type' => 'partner_inquiry'
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
            $mail->Subject = "New Partner Inquiry from $name";
            $mail->Body = "
                <h2>New Partner Inquiry</h2>
                <p><strong>Name:</strong> $name</p>
                <p><strong>Email:</strong> $email</p>
                <p><strong>Phone:</strong> $phone</p>
                <p><strong>Message:</strong><br>$message</p>
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
    <title>Partner with Us - Rudraa Housing India</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeInUp {
            animation: fadeInUp 0.8s ease-out forwards;
        }
        .hover-scale {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .hover-scale:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 20px rgba(0, 128, 128, 0.3); /* Teal shadow */
        }
        .bg-partner-hero {
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('https://via.placeholder.com/1920x1080?text=Partner+With+Us+Hero') center/cover no-repeat;
        }
        .benefit-card {
            background: linear-gradient(135deg, #e6fafa, #ccffff); /* Teal-based gradient */
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
        .star-rating {
            color: #facc15; /* Gold for star rating */
        }
        .alert {
            margin: 0 0 20px;
            padding: 15px;
            border-radius: 8px;
            font-size: 15px;
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
            background: white;
            max-width: 400px;
            margin: 100px auto;
            padding: 24px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
        }
        .popup-content h2 {
            font-size: 24px;
            font-weight: bold;
            color: #008080; /* Teal text */
            margin-bottom: 16px;
        }
        .popup-content p {
            color: #4b5563;
            margin-bottom: 16px;
        }
        .popup.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .loader {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 100;
            align-items: center;
            justify-content: center;
        }
        .loader.show {
            display: flex;
        }
        .loader-content {
            background: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 4px 20px rgba(0, 128, 128, 0.3);
        }
        .building-loader {
            width: 60px;
            height: 60px;
            position: relative;
            margin: 0 auto 10px;
        }
        .building {
            width: 40px;
            height: 40px;
            background: #008080;
            position: absolute;
            bottom: 0;
            left: 10px;
            animation: buildUp 1.5s infinite ease-in-out;
        }
        .crane {
            width: 10px;
            height: 20px;
            background: #006666;
            position: absolute;
            top: 0;
            left: 25px;
            animation: craneMove 1.5s infinite ease-in-out;
        }
        @keyframes buildUp {
            0% { height: 10px; }
            50% { height: 40px; }
            100% { height: 10px; }
        }
        @keyframes craneMove {
            0% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0); }
        }
    </style>
</head>
<body class="font-sans antialiased bg-white">
    <?php include __DIR__ . '/includes/header.php'; ?>

    <!-- Hero Section -->
    <section class="bg-partner-hero min-h-screen flex items-center justify-center text-white">
        <div class="container mx-auto px-4 text-center animate-fadeInUp">
            <h1 class="text-5xl md:text-6xl font-bold mb-6">Partner with Rudraa Housing India</h1>
            <p class="text-xl md:text-2xl mb-8 max-w-3xl mx-auto">
                Join our family of channel partners and associates to grow together, elevate communities, and unlock rewarding opportunities in real estate.
            </p>
            <a href="#contact-form" class="inline-block custom-teal text-white py-3 px-8 rounded-full text-lg font-semibold custom-teal-hover transition">Become a Partner</a>
        </div>
    </section>

    <!-- Partner Program Section -->
    <section id="partner-program" class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-4xl font-bold text-center text-teal mb-12 animate-fadeInUp">Why Partner with Us?</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div class="animate-fadeInUp">
                    <p class="text-lg text-gray-600 mb-6">
                        Our Channel Partners and Associates are an integral part of our business, and we believe in growing together by establishing long-term partnerships and incentivizing performance. We have successfully established a vast presence across India and helped enhance the lifestyle and elevate the socio-economic landscape in various cities.
                    </p>
                    <p class="text-lg text-gray-600 mb-6">
                        Rudraa Housing India makes our channel partners and associates a member of our family. The secret to our success is treating our partners well, providing great support to their clients, and making the relationship financially rewarding.
                    </p>
                    <p class="text-lg text-gray-600">
                        Our integration program is user-friendly and enables anyone with an interest in real estate or a willingness to become a part of the real estate growth story to join us and earn handsomely.
                    </p>
                </div>
                <div class="animate-fadeInUp" style="animation-delay: 0.2s;">
                    <img src="https://via.placeholder.com/600x400?text=Partner+Program" alt="Partner Program" class="rounded-xl shadow-lg w-full hover-scale">
                </div>
            </div>
        </div>
    </section>

    <!-- Benefits Section -->
    <section class="py-20 bg-gradient-to-r from-white to-teal-50">
        <div class="container mx-auto px-4">
            <h2 class="text-4xl font-bold text-center text-teal mb-12 animate-fadeInUp">Benefits of Partnering</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="benefit-card p-6 rounded-xl shadow-md text-center hover-scale animate-fadeInUp">
                    <h3 class="text-xl font-semibold text-teal mb-2">Attractive Incentives</h3>
                    <p class="text-gray-600">Earn handsome commissions and rewards for your efforts.</p>
                </div>
                <div class="benefit-card p-6 rounded-xl shadow-md text-center hover-scale animate-fadeInUp" style="animation-delay: 0.1s;">
                    <h3 class="text-xl font-semibold text-teal mb-2">Extensive Support</h3>
                    <p class="text-gray-600">Receive dedicated support to ensure client satisfaction.</p>
                </div>
                <div class="benefit-card p-6 rounded-xl shadow-md text-center hover-scale animate-fadeInUp" style="animation-delay: 0.2s;">
                    <h3 class="text-xl font-semibold text-teal mb-2">Trusted Brand</h3>
                    <p class="text-gray-600">Partner with a reputable name in real estate.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Reviews Section -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-4xl font-bold text-teal mb-12 animate-fadeInUp">What Our Partners Say</h2>
            <div class="animate-fadeInUp">
                <div class="flex justify-center mb-4">
                    <span class="star-rating text-3xl">★★★★★</span>
                </div>
                <p class="text-2xl font-semibold text-gray-600 mb-4">4.9 / 5 (1,219 Reviews)</p>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Our partners value the trust, support, and rewarding opportunities we provide, making Rudraa Housing India a preferred choice for collaboration.
                </p>
            </div>
        </div>
    </section>

    <!-- Contact Form Section -->
    <section id="contact-form" class="py-20 custom-teal text-white text-center">
        <div class="container mx-auto px-4 animate-fadeInUp">
            <h2 class="text-4xl font-bold mb-6">Become Our Channel Partner</h2>
            <p class="text-xl mb-8 max-w-2xl mx-auto">Join us today and start a rewarding journey in real estate.</p>
            <div id="loader" class="loader">
                <div class="loader-content">
                    <div class="building-loader">
                        <div class="building"></div>
                        <div class="crane"></div>
                    </div>
                    <p class="text-teal font-semibold">Building Your Request...</p>
                </div>
            </div>
            <?php if ($success): ?>
                <div id="thank-you-popup" class="popup show">
                    <div class="popup-content animate-fadeInUp">
                        <h2>Thank You!</h2>
                        <p>Your partner inquiry has been received. We'll get back to you soon.</p>
                        <p>Redirecting back to the partner page...</p>
                    </div>
                </div>
                <script>
                    setTimeout(() => {
                        window.location.href = '<?php echo $base; ?>/partner.php';
                    }, 3000);
                </script>
            <?php else: ?>
                <?php if ($errors): ?>
                    <div class="alert error"><?php echo implode(' • ', array_map('htmlspecialchars', $errors)); ?></div>
                <?php endif; ?>
                <div class="max-w-lg mx-auto bg-white p-6 rounded-xl shadow-lg">
                    <form id="partner-form" method="post" action="" class="space-y-4">
                        <div>
                            <input type="text" name="name" placeholder="Your Name" class="w-full p-3 rounded-lg border border-gray-300 text-gray-800 placeholder-gray-500 focus:ring-2 custom-teal-focus focus:border-transparent" required>
                        </div>
                        <div>
                            <input type="email" name="email" placeholder="Your Email" class="w-full p-3 rounded-lg border border-gray-300 text-gray-800 placeholder-gray-500 focus:ring-2 custom-teal-focus focus:border-transparent" required>
                        </div>
                        <div>
                            <input type="tel" name="phone" placeholder="Your Phone" class="w-full p-3 rounded-lg border border-gray-300 text-gray-800 placeholder-gray-500 focus:ring-2 custom-teal-focus focus:border-transparent" required>
                        </div>
                        <div>
                            <textarea name="message" placeholder="Why do you want to partner with us?" rows="4" class="w-full p-3 rounded-lg border border-gray-300 text-gray-800 placeholder-gray-500 focus:ring-2 custom-teal-focus focus:border-transparent" required></textarea>
                        </div>
                        <button type="submit" class="w-full custom-teal text-white py-3 rounded-lg custom-teal-hover transition">Submit Application</button>
                    </form>
                    <script>
                        document.getElementById('partner-form').addEventListener('submit', function() {
                            document.getElementById('loader').classList.add('show');
                        });
                    </script>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <?php include __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
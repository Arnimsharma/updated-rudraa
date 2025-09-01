<?php 
require __DIR__ . '/includes/functions.php';

// Get property ID from URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch all properties and find the matching one
$properties = get_all_properties();
$property = null;
foreach ($properties as $prop) {
    if ($prop['id'] === $id) {
        $property = $prop;
        break;
    }
}

if (!$property) {
    // Redirect or show error if not found
    header('Location: listings.php');
    exit;
}

// Fallback for missing fields
$property['images'] = $property['images'] ?? [$property['image']]; // Ensure at least one image
$property['amenities'] = $property['amenities'] ?? [
    ['name' => 'Spacious Plots', 'logo' => 'images/icons/plot.png'],
    ['name' => '24/7 Security', 'logo' => 'images/icons/security.png'],
    ['name' => 'Parks & Gardens', 'logo' => 'images/icons/park.png'],
    ['name' => 'Clubhouse', 'logo' => 'images/icons/clubhouse.png'],
    ['name' => 'Power Backup', 'logo' => 'images/icons/power.png']
];
$property['location'] = $property['location'] ?? $property['city'];
$property['location_advantages'] = $property['location_advantages'] ?? [
    'Close to city center',
    'Excellent connectivity',
    'Natural surroundings',
    'Near key landmarks'
];
$property['payment'] = $property['payment'] ?? [
    'plan_a' => [
        'area' => $property['area_sqft'],
        'total_price' => $property['price'],
        'booking_price' => $property['price'] * 0.1,
        'balance_price' => $property['price'] * 0.9,
        'timeline' => '12 months'
    ],
    'emi_option' => [
        'booking' => $property['price'] * 0.1,
        'installment_1' => $property['price'] * 0.2,
        'per_month_emi' => ($property['price'] * 0.7) / 60 // Approx 5-year EMI
    ]
];

// Function to fetch coordinates automatically using Nominatim API
function get_coordinates($address) {
    $url = "https://nominatim.openstreetmap.org/search?q=" . urlencode($address) . "&format=json&limit=1";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36");
    $response = curl_exec($ch);
    curl_close($ch);
    $data = json_decode($response, true);
    if (!empty($data) && isset($data[0]['lat']) && isset($data[0]['lon'])) {
        return ['lat' => $data[0]['lat'], 'lon' => $data[0]['lon']];
    }
    return null; // Fallback if API fails
}

// Fetch coordinates if not already stored
if (!isset($property['lat']) || !isset($property['lon'])) {
    $coords = get_coordinates($property['location']);
    if ($coords) {
        $property['lat'] = $coords['lat'];
        $property['lon'] = $coords['lon'];
    } else {
        // Default fallback coordinates (e.g., for Jaipur)
        $property['lat'] = 27.1751448;
        $property['lon'] = 75.7764354;
    }
}

// Generate map embed using fetched coordinates
$property['map_embed'] = '<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d10000!2d' . $property['lon'] . '!3d' . $property['lat'] . '!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sin!4v' . time() . '" width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"></iframe>';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($property['title']); ?> - Rudraa Housing India</title>
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
            box-shadow: 0 4px 20px rgba(0, 128, 128, 0.3); /* Updated to #008080 shadow */
        }
        .bg-property-hero {
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('<?php echo htmlspecialchars($property['image']); ?>') center/cover no-repeat;
        }
        .gallery-img {
            transition: transform 0.3s ease;
        }
        .gallery-img:hover {
            transform: scale(1.1);
        }
        .amenity-logo {
            width: 48px;
            height: 48px;
            object-fit: contain;
        }
        .advantage-item::before {
            content: '✔';
            color: #008080; /* Updated to teal */
            margin-right: 8px;
        }
        .custom-teal {
            background-color: #008080; /* Teal for buttons and backgrounds */
        }
        .custom-teal-hover:hover {
            background-color: #006666; /* Slightly darker teal for hover */
        }
        .custom-teal-focus {
            --tw-ring-color: #008080; /* Teal focus ring */
        }
        .text-teal {
            color: #008080; /* Teal text */
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
    <section class="bg-property-hero min-h-screen flex items-center justify-center text-white">
        <div class="container mx-auto px-4 text-center animate-fadeInUp">
            <h1 class="text-5xl md:text-6xl font-bold mb-6"><?php echo htmlspecialchars($property['title']); ?></h1>
            <p class="text-xl md:text-2xl mb-4">₹<?php echo number_format($property['price']); ?></p>
            <p class="text-lg mb-8"><?php echo htmlspecialchars($property['city']); ?> • <?php echo htmlspecialchars($property['type']); ?> • <?php echo (int)$property['area_sqft']; ?> sqft</p>
            <a href="contact.php?property_id=<?php echo $id; ?>&property_title=<?php echo urlencode($property['title']); ?>" class="inline-block custom-teal text-white py-3 px-8 rounded-full text-lg font-semibold custom-teal-hover transition">I'm Interested</a>
        </div>
    </section>

    <!-- Overview Section -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-4xl font-bold text-center text-teal mb-12 animate-fadeInUp">Project Overview</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div class="animate-fadeInUp">
                    <p class="text-lg text-gray-600 mb-6"><?php echo htmlspecialchars($property['description']); ?></p>
                    <ul class="space-y-2 text-gray-600">
                        <li><strong>Location:</strong> <?php echo htmlspecialchars($property['location']); ?></li>
                        <li><strong>Type:</strong> <?php echo htmlspecialchars($property['type']); ?></li>
                        <li><strong>Size:</strong> <?php echo (int)$property['area_sqft']; ?> sqft</li>
                    </ul>
                </div>
                <div class="animate-fadeInUp" style="animation-delay: 0.2s;">
                    <img src="<?php echo htmlspecialchars($property['image']); ?>" alt="<?php echo htmlspecialchars($property['title']); ?>" class="rounded-xl shadow-lg w-full">
                </div>
            </div>
        </div>
    </section>

    <!-- Amenities Section -->
    <section class="py-20 bg-gradient-to-r from-white to-teal-50">
        <div class="container mx-auto px-4">
            <h2 class="text-4xl font-bold text-center text-teal mb-12 animate-fadeInUp">Amenities & Features</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                <?php foreach ($property['amenities'] as $index => $amenity): ?>
                    <div class="bg-white p-6 rounded-xl shadow-lg text-center hover-scale animate-fadeInUp" style="animation-delay: <?php echo $index * 0.1; ?>s;">
                        <img src="<?php echo htmlspecialchars($amenity['logo']); ?>" alt="<?php echo htmlspecialchars($amenity['name']); ?>" class="amenity-logo mx-auto mb-4">
                        <h3 class="text-xl font-semibold text-teal"><?php echo htmlspecialchars($amenity['name']); ?></h3>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Gallery Section -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-4xl font-bold text-center text-teal mb-12 animate-fadeInUp">Project Gallery</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($property['images'] as $index => $img): ?>
                    <div class="overflow-hidden rounded-xl shadow-lg hover-scale animate-fadeInUp" style="animation-delay: <?php echo $index * 0.1; ?>s;">
                        <img src="<?php echo htmlspecialchars($img); ?>" alt="Gallery Image" class="w-full h-64 object-cover gallery-img">
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Location Section -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-4xl font-bold text-center text-teal mb-12 animate-fadeInUp">Location & Advantages</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <div class="animate-fadeInUp">
                    <p class="text-lg text-gray-600 mb-6"><?php echo htmlspecialchars($property['title']); ?> is located in <?php echo htmlspecialchars($property['location']); ?>, offering excellent connectivity and serene surroundings.</p>
                    <h3 class="text-2xl font-semibold text-teal mb-4">Location Advantages</h3>
                    <ul class="space-y-2 text-gray-600">
                        <?php foreach ($property['location_advantages'] as $index => $advantage): ?>
                            <li class="advantage-item"><?php echo htmlspecialchars($advantage); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="animate-fadeInUp" style="animation-delay: 0.2s;">
                    <?php echo $property['map_embed']; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Payment Section -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-4xl font-bold text-center text-teal mb-12 animate-fadeInUp">Payment Details</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <!-- Plan A -->
                <div class="bg-teal-50 p-6 rounded-xl shadow-lg animate-fadeInUp">
                    <h3 class="text-2xl font-semibold text-teal mb-6">Plan A: Full Payment</h3>
                    <ul class="space-y-4 text-gray-600">
                        <li><strong>Area:</strong> <?php echo (int)$property['payment']['plan_a']['area']; ?> sqft</li>
                        <li><strong>Total Price:</strong> ₹<?php echo number_format($property['payment']['plan_a']['total_price']); ?></li>
                        <li><strong>Booking Price:</strong> ₹<?php echo number_format($property['payment']['plan_a']['booking_price']); ?></li>
                        <li><strong>Balance Price:</strong> ₹<?php echo number_format($property['payment']['plan_a']['balance_price']); ?></li>
                        <li><strong>Timeline:</strong> <?php echo htmlspecialchars($property['payment']['plan_a']['timeline']); ?></li>
                    </ul>
                </div>
                <!-- Part B: EMI Option -->
                <div class="bg-teal-50 p-6 rounded-xl shadow-lg animate-fadeInUp" style="animation-delay: 0.2s;">
                    <h3 class="text-2xl font-semibold text-teal mb-6">Part B: EMI Option</h3>
                    <ul class="space-y-4 text-gray-600">
                        <li><strong>Booking:</strong> ₹<?php echo number_format($property['payment']['emi_option']['booking']); ?></li>
                        <li><strong>Installment 1:</strong> ₹<?php echo number_format($property['payment']['emi_option']['installment_1']); ?></li>
                        <li><strong>Per Month EMI:</strong> ₹<?php echo number_format($property['payment']['emi_option']['per_month_emi']); ?></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-20 custom-teal text-white text-center">
        <div class="container mx-auto px-4 animate-fadeInUp">
            <h2 class="text-4xl font-bold mb-6">Interested in This Property?</h2>
            <p class="text-xl mb-8 max-w-2xl mx-auto">Get in touch with us to learn more or schedule a site visit.</p>
            <div id="contact-form" class="max-w-lg mx-auto bg-white p-6 rounded-xl shadow-lg">
                <div id="loader" class="loader">
                    <div class="loader-content animate-fadeInUp">
                        <div class="building-loader">
                            <div class="building"></div>
                            <div class="crane"></div>
                        </div>
                        <p class="text-teal font-semibold text-lg">Processing Your Request...</p>
                    </div>
                </div>
                <form id="property-contact-form" method="post" action="submit_contact.php" class="space-y-6">
                    <input type="hidden" name="property_id" value="<?php echo htmlspecialchars($id); ?>">
                    <input type="hidden" name="property_title" value="<?php echo htmlspecialchars($property['title'] ?? 'Not specified'); ?>">
                    <div>
                        <label for="name" class="block text-left text-gray-700 font-medium mb-1">Your Name</label>
                        <input type="text" name="name" placeholder="Enter your name" class="w-full p-3 rounded-lg border border-gray-300 text-gray-800 placeholder-gray-500 focus:ring-2 custom-teal-focus focus:border-transparent" required>
                    </div>
                    <div>
                        <label for="email" class="block text-left text-gray-700 font-medium mb-1">Your Email</label>
                        <input type="email" name="email" placeholder="Enter your email" class="w-full p-3 rounded-lg border border-gray-300 text-gray-800 placeholder-gray-500 focus:ring-2 custom-teal-focus focus:border-transparent" required>
                    </div>
                    <div>
                        <label for="phone" class="block text-left text-gray-700 font-medium mb-1">Your Phone</label>
                        <input type="tel" name="phone" placeholder="Enter your phone number" class="w-full p-3 rounded-lg border border-gray-300 text-gray-800 placeholder-gray-500 focus:ring-2 custom-teal-focus focus:border-transparent" required>
                    </div>
                    <div>
                        <label for="message" class="block text-left text-gray-700 font-medium mb-1">Your Message</label>
                        <textarea name="message" placeholder="Tell us about your inquiry" rows="4" class="w-full p-3 rounded-lg border border-gray-300 text-gray-800 placeholder-gray-500 focus:ring-2 custom-teal-focus focus:border-transparent" required></textarea>
                    </div>
                    <button type="submit" class="w-full custom-teal text-white py-3 rounded-lg custom-teal-hover transition hover-scale">Submit Inquiry</button>
                </form>
                <script>
                    document.getElementById('property-contact-form').addEventListener('submit', function() {
                        document.getElementById('loader').classList.add('show');
                    });
                </script>
            </div>
        </div>
    </section>

    <?php include __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
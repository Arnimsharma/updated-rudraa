<?php
$base = dirname($_SERVER['SCRIPT_NAME']);
if ($base === DIRECTORY_SEPARATOR) $base = '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Global Presence - Rudraa Housing India</title>
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
        .bg-global-hero {
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('https://via.placeholder.com/1920x1080?text=Global+Presence+Hero') center/cover no-repeat; /* Replace with actual global image */
        }
        .global-card {
            background: linear-gradient(135deg, #e6fafa, #ccffff); /* Teal-based gradient */
        }
        .custom-teal {
            background-color: #008080; /* Teal for buttons and backgrounds */
        }
        .custom-teal-hover:hover {
            background-color: #006666; /* Darker teal for hover */
        }
        .text-teal {
            color: #008080; /* Teal text */
        }
        .interactive-map {
            transition: all 0.3s ease;
        }
        .interactive-map:hover {
            transform: scale(1.02);
        }
    </style>
</head>
<body class="font-sans antialiased bg-white">
    <?php include __DIR__ . '/includes/header.php'; ?>

    <!-- Hero Section -->
    <section class="bg-global-hero min-h-screen flex items-center justify-center text-white">
        <div class="container mx-auto px-4 text-center animate-fadeInUp">
            <h1 class="text-5xl md:text-6xl font-bold mb-6">Global Presence</h1>
            <p class="text-xl md:text-2xl mb-8 max-w-3xl mx-auto">
                Rudraa Housing India is expanding horizons, creating architectural excellence worldwide.
            </p>
            <a href="#our-global-story" class="inline-block custom-teal text-white py-3 px-8 rounded-full text-lg font-semibold custom-teal-hover transition">Explore Our Reach</a>
        </div>
    </section>

    <!-- Our Global Story Section -->
    <section id="our-global-story" class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-4xl font-bold text-center text-teal mb-12 animate-fadeInUp">Our Global Journey</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div class="animate-fadeInUp">
                    <p class="text-lg text-gray-600 mb-6">
                        Rudraa Housing India is constantly working towards creating new benchmarks of architectural excellence in the contemporary global environment. In this new environment, the demand for multi-faceted real estate development has become crucial for keeping pace with the progress. We have always taken new initiatives and emerged as one of the prominent entities.
                    </p>
                    <p class="text-lg text-gray-600 mb-6">
                        Rudraa Housing India is a brand synonym of reliability, quality & services with trust, to address the ever growing needs of real estate business. We are doing our best efforts to satisfy our customers in India as well as Abroad.
                    </p>
                    <p class="text-lg text-gray-600">
                        In order to serve our clients we go beyond our boundaries, we have developed relations not only in India, but we have tied up with global places like Dubai-(UAE), to provide our Abroad customers the opportunity to buy properties in India for their future investments.
                    </p>
                </div>
                <div class="animate-fadeInUp" style="animation-delay: 0.2s;">
                    <img src="https://via.placeholder.com/600x400?text=Global+Presence" alt="Global Presence Map" class="rounded-xl shadow-lg w-full interactive-map">
                </div>
            </div>
        </div>
    </section>

    <!-- Global Locations Section -->
    <section class="py-20 bg-gradient-to-r from-white to-teal-50">
        <div class="container mx-auto px-4">
            <h2 class="text-4xl font-bold text-center text-teal mb-12 animate-fadeInUp">Our International Footprint</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="global-card p-6 rounded-xl shadow-md text-center hover-scale animate-fadeInUp">
                    <img src="https://via.placeholder.com/300x200?text=India" alt="India Location" class="w-full h-40 object-cover rounded-lg mb-4">
                    <h3 class="text-xl font-semibold text-teal mb-2">India</h3>
                    <p class="text-gray-600">Our home base with innovative residential and commercial projects.</p>
                </div>
                <div class="global-card p-6 rounded-xl shadow-md text-center hover-scale animate-fadeInUp" style="animation-delay: 0.1s;">
                    <img src="https://via.placeholder.com/300x200?text=Dubai" alt="Dubai Location" class="w-full h-40 object-cover rounded-lg mb-4">
                    <h3 class="text-xl font-semibold text-teal mb-2">Dubai, UAE</h3>
                    <p class="text-gray-600">Strategic partnerships for international investments and client services.</p>
                </div>
                <div class="global-card p-6 rounded-xl shadow-md text-center hover-scale animate-fadeInUp" style="animation-delay: 0.2s;">
                    <img src="https://via.placeholder.com/300x200?text=Global+Expansion" alt="Future Expansion" class="w-full h-40 object-cover rounded-lg mb-4">
                    <h3 class="text-xl font-semibold text-teal mb-2">Future Expansions</h3>
                    <p class="text-gray-600">Exploring new horizons to bring our excellence worldwide.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action Section -->
    <section class="py-20 custom-teal text-white text-center">
        <div class="container mx-auto px-4 animate-fadeInUp">
            <h2 class="text-4xl font-bold mb-6">Connect Globally with Rudraa</h2>
            <p class="text-xl mb-8 max-w-2xl mx-auto">Whether in India or abroad, let's turn your real estate dreams into reality.</p>
            <a href="contact.php" class="inline-block bg-white text-teal py-3 px-8 rounded-full text-lg font-semibold custom-teal-hover transition">Get in Touch</a>
        </div>
    </section>

    <?php include __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
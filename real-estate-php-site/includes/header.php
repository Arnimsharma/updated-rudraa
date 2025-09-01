<?php
$base = dirname($_SERVER['SCRIPT_NAME']);
if ($base === DIRECTORY_SEPARATOR) $base = '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rudraa Housing India</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .nav-link {
            transition: color 0.3s ease, transform 0.3s ease;
        }
        .nav-link:hover {
            color: #008080; /* Teal hover color */
            transform: translateY(-2px);
        }
        .hamburger {
            display: none;
            flex-direction: column;
            gap: 5px;
            cursor: pointer;
        }
        .hamburger span {
            width: 25px;
            height: 3px;
            background: #008080; /* Teal for hamburger lines */
            transition: all 0.3s ease;
        }
        #nav-menu {
            transition: all 0.3s ease;
        }
        @media (max-width: 768px) {
            .hamburger {
                display: flex;
            }
            #nav-menu {
                display: none;
            }
            #nav-menu.active {
                display: flex;
                flex-direction: column;
                position: absolute;
                top: 80px;
                left: 0;
                right: 0;
                background: linear-gradient(135deg, #ffffff, #e6fafa); /* Teal gradient */
                box-shadow: 0 4px 6px rgba(0, 128, 128, 0.2);
                padding: 1rem;
                z-index: 40;
            }
            #nav-menu.active a {
                padding: 0.5rem 0;
                color: #008080; /* Teal text */
                font-weight: 600;
            }
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
    <header class="bg-white shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-4 py-4 flex items-center justify-between">
            <!-- Logo -->
            <div class="brand">
                <a href="<?php echo $base; ?>/index.php" class="flex items-center">
                    <img src="<?php echo $base; ?>/assets/images/Rudra-logo.webp" alt="Rudraa Housing Logo" class="h-12 w-auto">
                </a>
            </div>

            <!-- Navigation -->
            <nav id="nav-menu" class="flex items-center space-x-6">
                <a href="<?php echo $base; ?>/index.php" class="text-gray-800 font-semibold nav-link hover:text-teal-600">Home</a>
                <a href="<?php echo $base; ?>/about.php" class="text-gray-800 font-semibold nav-link hover:text-teal-600">About Us</a>
                <a href="<?php echo $base; ?>/global-presence.php" class="text-gray-800 font-semibold nav-link hover:text-teal-600">Global Presence</a>
                <a href="<?php echo $base; ?>/achievements.php" class="text-gray-800 font-semibold nav-link hover:text-teal-600">Achievements</a>
                <a href="<?php echo $base; ?>/partner.php" class="text-gray-800 font-semibold nav-link hover:text-teal-600">Partner With Us</a>
            </nav>

            <!-- Hamburger Menu for Mobile -->
            <div class="hamburger md:hidden">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>

        <!-- Loader -->
        <div id="loader" class="loader">
            <div class="loader-content animate-fadeInUp">
                <div class="building-loader">
                    <div class="building"></div>
                    <div class="crane"></div>
                </div>
                <p class="text-teal font-semibold text-lg">Processing Your Request...</p>
            </div>
        </div>
    </header>

    <main>

    <script>
        const hamburger = document.querySelector('.hamburger');
        const navMenu = document.querySelector('#nav-menu');
        hamburger.addEventListener('click', () => {
            navMenu.classList.toggle('active');
            hamburger.querySelectorAll('span').forEach(span => {
                span.classList.toggle('bg-teal-600'); /* Teal for active state */
            });
        });
    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rent 'n Repair - Welcome</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            margin: 0;
            font-family: Arial;
            background: black; /* Fallback black background */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #fff;
            position: relative;
            overflow: hidden; /* Prevent scrollbars */
        }

        /* Slideshow */
        .slideshow {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden; /* Hide overflow */
        }

        .slideshow img {
            position: absolute;
            top: 0;
            width: 100%;
            height: 100%;
            object-fit: cover; /* Ensures the images cover the background */
            opacity: 0;
            animation: fade 6s infinite; /* Total duration of each image cycle */
        }

        /* Keyframe for fade animation */
        @keyframes fade {
            0% {
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            40% {
                opacity: 1; /* Keep the first image visible longer */
            }
            45% {
                opacity: 0; /* Fade out starts earlier */
            }
            100% {
                opacity: 0; /* Ensure it stays at 0% until the next image */
            }
        }

        /* Apply delay to the second image */
        .slideshow img:nth-child(2) {
            animation-delay: 3s; /* Starts after 3 seconds */
        }

        /* Gradient overlay for filter effect */
        .gradient-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(
    to right,
    rgba(255,0,118,148, 0.5),  /* Black with 20% opacity */
    rgba(255, 137, 1,165, 0.5)  /* #FFAA00 with 20% opacity */
);
z-index: 0; /* Positioned below the container */

            z-index: 0; /* Positioned below the container */
        }

        .container {
            text-align: center;
            background-color: rgba(0, 0, 0, 0.8);
            padding: 50px;
            padding-bottom: 80px;
            box-shadow: 0 4px 8px rgba(255, 255, 255, 0.1);
            border-radius: 00px;
            position: relative;
            width: 100%;
            max-width: 2200px;
            z-index: 1;
            /* Center the container */
            margin: auto; /* Horizontal centering */
        }

        h1 {
            font-family: serif;
            color: #0096FF;
        }

        p {
            font-size: 18px;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .explore-btn {
            background-color: #00539CFF;
            color: #fff;
            padding: 15px 30px;
            border: none;
            border-radius: 5px;
            text-transform: uppercase;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.5s ease;
            position: absolute;
            bottom: 20px;
            left: 20px;
        }

        .explore-btn:hover {
            background-color: #003366;
        }

        .image-container {
            width: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>
<body>
    <!-- Slideshow container -->
    <div class="slideshow">
        <img src="assests/index1.jpg" alt="Slideshow Image 1">
        <img src="assests/index5.jpeg" alt="Slideshow Image 2">
    </div>

    <!-- Gradient overlay for filter effect -->
    <div class="gradient-overlay"></div>

    <div class="container">
        <h1>Welcome to Rent 'n Repair!</h1>
        <p>At Rent 'n Repair, we streamline the process of renting properties and managing repairs. 
        Our platform allows landlords to list rentals, track payments, and handle tenant needs effortlessly. 
        Tenants can easily browse properties, pay rent, and submit repair requests. We also connect users with 
        skilled technicians to ensure prompt, professional maintenance services. 
        Our mission is to make property management simple, efficient, and stress-free for everyone involved.</p>
        <button class="explore-btn" onclick="location.href='registration.php'">
            <i class="fas fa-arrow-right" style="margin-right: 8px;"></i> Explore More
        </button>
    </div>

</body>
</html>

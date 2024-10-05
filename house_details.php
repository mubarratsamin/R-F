<?php
$servername = "localhost:3307";
$username = "root";
$password = "105435";
$dbname = "rnf";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$message = "";
$success = false;

// Handle file upload and form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $location = $_POST['location'];
    $number = $_POST['number'];
    $house_details = $_POST['house_details'];
    $rent_per_month = $_POST['rent_per_month']; // New variable

    // Upload file
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["house_photo"]["name"]);
    
    // Check if the uploads directory exists, if not, create it
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0755, true);
    }

    if (move_uploaded_file($_FILES["house_photo"]["tmp_name"], $target_file)) {
        // Save to database
        $sql = "INSERT INTO house_details (name, location, number, house_photo, house_details, rent_per_month) VALUES ('$name', '$location', '$number', '$target_file', '$house_details', '$rent_per_month')";

        if ($conn->query($sql) === TRUE) {
            $message = "Your house has been added for rent successfully!";
            $success = true; // Set success flag
        } else {
            $message = "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        $message = "Sorry, there was an error uploading your file.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>House Details Submission</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background-color: #8901a5; /* Background color */
            font-family: Arial, sans-serif;
            padding: 20px; /* Added padding for better layout */
        }
        .container {
            width: 60%; /* Adjusted to make the form wider */
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff; /* Container background color */
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        input[type="text"], input[type="file"], textarea {
            width: 100%;
            padding: 12px; /* Increased padding for better usability */
            margin: 10px 0;
            border: 1px solid #007694; /* Border color matching the theme */
            border-radius: 5px;
            box-sizing: border-box; /* Include padding in width calculation */
        }
        input[type="submit"] {
            background-color: #007694; /* Submit button color */
            color: white;
            border: none;
            padding: 12px 15px; /* Increased padding for button */
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px; /* Increased font size for better visibility */
            transition: background-color 0.3s ease; /* Smooth transition for hover effect */
        }
        input[type="submit"]:hover {
            background-color: #005f6b; /* Darker shade for hover effect */
        }
        /* Modal styles */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgba(0, 0, 0, 0.5); /* Black w/ opacity */
        }
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto; /* 15% from the top and centered */
            padding: 20px;
            border: 1px solid #888;
            width: 80%; /* Could be more or less, depending on screen size */
            text-align: center;
        }
        .tick-icon {
            color: green;
            font-size: 30px;
            margin-bottom: 10px;
        }
        .home-icon {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 24px;
            color: #ffffff; /* Color of the icon */
            text-decoration: none;
        }
        .home-icon:hover {
            color: #ffd700; /* Color change on hover */
        }
    </style>
    <script>
        function showModal() {
            document.getElementById('modal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('modal').style.display = 'none';
            // Optionally redirect after closing
            window.location.href = "house_details.php"; // Redirect to refresh the page
        }

        // Show modal if success message is set
        window.onload = function() {
            if (<?php echo json_encode($success); ?>) {
                showModal();
            }
        };
    </script>
</head>
<body>

<a href="welcome.html" class="home-icon"><i class="fas fa-home"></i></a>

<div class="container">
    <h2>Submit House Details</h2>

    <form action="house_details.php" method="POST" enctype="multipart/form-data">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>

        <label for="location">Location:</label>
        <input type="text" id="location" name="location" required>

        <label for="number">Phone Number:</label>
        <input type="text" id="number" name="number" required>

        <label for="rent_per_month">Rent Per Month:</label>
        <input type="text" id="rent_per_month" name="rent_per_month" required>

        <label for="house_photo">House Photo:</label>
        <input type="file" id="house_photo" name="house_photo" accept="image/*" required>

        <label for="house_details">House Details:</label>
        <textarea id="house_details" name="house_details" rows="4" required></textarea>

        <input type="submit" value="Submit">
    </form>
</div>

<!-- Modal -->
<div id="modal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="tick-icon">✔️</span>
        <p><?php echo $message; ?></p>
        <button onclick="closeModal()">Close</button>
    </div>
</div>

</body>
</html>

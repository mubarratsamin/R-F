<?php
// technician_registration.php

// Database connection parameters
$servername = "localhost:3307"; // Adjust if necessary
$username = "root";
$password = "105435";
$dbname = "rnf"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables for form data
$name = '';
$number = '';
$email = '';
$address = '';
$workType = '';
$photo = '';
$message = '';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $name = $_POST['name'];
    $number = $_POST['number'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $workType = $_POST['workType'];

    // Handle file upload
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "uploads/"; // Directory to store uploaded photos
        $fileName = basename($_FILES["photo"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        // Check if the file is an image
        $allowedTypes = array('jpg', 'jpeg', 'png', 'gif');
        if (in_array(strtolower($fileType), $allowedTypes)) {
            // Move uploaded file to target directory
            if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFilePath)) {
                // Prepare and bind
                $stmt = $conn->prepare("INSERT INTO technicians (name, number, email, address, workType, photo) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssss", $name, $number, $email, $address, $workType, $targetFilePath);
                
                // Execute the statement
                if ($stmt->execute()) {
                    $message = "Registration successful!";
                    // Clear form data
                    $name = $number = $email = $address = $workType = '';
                } else {
                    $message = "Error: " . $stmt->error;
                }

                $stmt->close();
            } else {
                $message = "File upload failed, please try again.";
            }
        } else {
            $message = "Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.";
        }
    } else {
        $message = "Error uploading photo.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Technician Registration</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Include Font Awesome -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #8901a5 50%, #007694 50%);
            margin: 0;
            padding: 20px;
            color: white;
        }

        .container {
            max-width: 600px;
            margin: auto;
            padding: 20px;
            background-color: #007694; /* Background color */
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input, .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-group button {
            width: 100%;
            padding: 10px;
            background-color: #ede8d0; /* Button color */
            color: black; /* Button text color */
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .form-group button:hover {
            background-color: #a96d53; /* Darker color on hover */
        }

        .message {
            text-align: center;
            margin-top: 20px;
        }

        .home-icon {
            position: fixed; /* Positioning the home icon */
            top: 20px;
            right: 20px;
            font-size: 24px;
            color: #ffffff; /* Color of the icon */
            text-decoration: none;
            z-index: 1000; /* Ensure it's above other content */
        }

        .home-icon:hover {
            color: #ffd700; /* Color change on hover */
        }
    </style>
</head>
<body>

    <a href="welcome.html" class="home-icon"><i class="fas fa-home"></i></a> <!-- Home Icon -->

    <div class="container">
        <h2>Technician Registration</h2>

        <?php if ($message): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <form method="POST" action="technician_registration.php" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" required value="<?php echo htmlspecialchars($name); ?>">
            </div>
            <div class="form-group">
                <label for="number">Phone Number:</label>
                <input type="text" name="number" id="number" required value="<?php echo htmlspecialchars($number); ?>">
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required value="<?php echo htmlspecialchars($email); ?>">
            </div>
            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" name="address" id="address" required value="<?php echo htmlspecialchars($address); ?>">
            </div>
            <div class="form-group">
                <label for="workType">Work Type:</label>
                <select name="workType" id="workType" required>
                    <option value="" disabled selected>Select Work Type</option>
                    <option value="Plumber" <?php echo ($workType == 'Plumber') ? 'selected' : ''; ?>>Plumber</option>
                    <option value="Electrician" <?php echo ($workType == 'Electrician') ? 'selected' : ''; ?>>Electrician</option>
                    <option value="Carpenter" <?php echo ($workType == 'Carpenter') ? 'selected' : ''; ?>>Carpenter</option>
                    <option value="Cleaner" <?php echo ($workType == 'Cleaner') ? 'selected' : ''; ?>>Cleaner</option>
                </select>
            </div>
            <div class="form-group">
                <label for="photo">Upload Photo:</label>
                <input type="file" name="photo" id="photo" accept="image/*" required>
            </div>
            <div class="form-group">
                <button type="submit" name="submit">Register</button>
            </div>
        </form>
    </div>
</body>
</html>

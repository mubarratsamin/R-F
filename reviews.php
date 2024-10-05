<?php
// reviews.php
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

// Initialize variables for reviews
$owner_or_technician_name = '';
$review = '';

// Check if the review form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submitReview'])) {
    $owner_or_technician_name = $_POST['owner_or_technician_name'];
    $review = $_POST['review'];

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO reviews (owner_or_technician_name, review) VALUES (?, ?)");
    $stmt->bind_param("ss", $owner_or_technician_name, $review);
    
    // Execute the statement
    if ($stmt->execute()) {
        // Successfully added the review, set the session variable for the popup
        $thankYouMessage = true;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Fetch existing reviews from the database
$sql = "SELECT owner_or_technician_name, review, created_at FROM reviews ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #8901a5 50%, #007694 50%);
            margin: 0;
            padding: 20px;
        }

        .home-icon {
            position: absolute; /* Position it at the top right */
            top: 20px;
            right: 20px;
            font-size: 24px; /* Size of the icon */
            color: #ffffff; /* Color of the icon */
            text-decoration: none; /* Remove underline */
        }
        .home-icon:hover {
            color: #ffd700; /* Change color on hover */
        }

        .container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            background-color: #007694; /* Background color */
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            color: white; /* Text color for the container */
        }

        h2 {
            text-align: center;
            color: #ede8d0; /* Light color for the heading */
        }

        .review-card {
            background-color: rgba(255, 255, 255, 0.2); /* Slightly transparent background for reviews */
            border: 1px solid #ede8d0; /* Light border color */
            border-radius: 8px; /* Rounded corners */
            padding: 15px; /* Padding for review cards */
            margin-bottom: 15px; /* Space between reviews */
        }

        .review-card h3 {
            margin: 0;
            font-size: 1.2em; /* Slightly larger font for owner/technician name */
            color: #ede8d0; /* Light color for the owner/technician name */
        }

        .review-card p {
            margin: 5px 0;
            color: #ede8d0; /* Light color for the review text */
        }

        .review-form {
            margin-top: 20px;
        }

        .review-form input, .review-form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .review-form button {
            padding: 10px;
            background-color: #ede8d0; /* Button color */
            color: black; /* Button text color */
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .review-form button:hover {
            background-color: #a96d53; /* Darker color on hover */
        }

        /* Popup Box Styles */
        .popup {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgb(0, 0, 0); /* Fallback color */
            background-color: rgba(0, 0, 0, 0.4); /* Black w/ opacity */
            padding-top: 60px; /* Location of the box */
        }

        .popup-content {
            background-color: #fefefe;
            margin: 5% auto; /* 15% from the top and centered */
            padding: 20px;
            border: 1px solid #888;
            width: 80%; /* Could be more or less, depending on screen size */
            border-radius: 8px;
            text-align: center;
        }

        .popup-content img {
            width: 30px; /* Icon size */
            vertical-align: middle;
            margin-right: 10px; /* Spacing between icon and text */
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>

    <!-- Home Button -->
    <a href="welcome.html" class="home-icon"><i class="fas fa-home"></i></a>

    <div class="container">
        <h2>Reviews</h2>

        <!-- Review Form -->
        <div class="review-form">
            <h3>Your Review</h3>
            <form method="POST" action="reviews.php">
                <input type="text" name="owner_or_technician_name" placeholder="Owner or Technician Name" required value="<?php echo htmlspecialchars($owner_or_technician_name); ?>">
                <textarea name="review" rows="5" placeholder="Write your review here..." required><?php echo htmlspecialchars($review); ?></textarea>
                <button type="submit" name="submitReview">Submit Review</button>
            </form>
        </div>

        <h3>All Reviews</h3>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="review-card">
                    <h3><?php echo htmlspecialchars($row['owner_or_technician_name']); ?></h3>
                    <p><?php echo nl2br(htmlspecialchars($row['review'])); ?></p>
                    <p><em><?php echo date('F j, Y, g:i a', strtotime($row['created_at'])); ?></em></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No reviews yet.</p>
        <?php endif; ?>
    </div>

    <!-- The Modal for the Thank You Message -->
    <div id="thankYouModal" class="popup" style="<?php if (isset($thankYouMessage) && $thankYouMessage) echo 'display: block;'; ?>">
        <div class="popup-content">
            <span class="close" onclick="document.getElementById('thankYouModal').style.display='none'">&times;</span>
            <img src="https://img.icons8.com/ios-filled/50/28a745/checkmark.png" alt="Thank You Icon">
            <p>Thank you for your review!</p>
        </div>
    </div>

    <script>
        // Close the modal when the user clicks anywhere outside of it
        window.onclick = function(event) {
            var modal = document.getElementById('thankYouModal');
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>
</html>

<?php
$conn->close();
?>

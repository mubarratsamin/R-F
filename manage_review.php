<?php
// manage_review.php
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

// Delete review if delete request is made
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $stmt = $conn->prepare("DELETE FROM reviews WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    
    if ($stmt->execute()) {
        // Successfully deleted the review
        $deleteMessage = true;
    } else {
        echo "Error deleting review: " . $stmt->error;
    }

    $stmt->close();
}

// Fetch existing reviews from the database
$sql = "SELECT id, owner_or_technician_name, review, created_at FROM reviews ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Reviews</title>
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
            position: relative; /* Relative position for delete button */
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

        .delete-button {
            position: absolute; /* Position the delete button */
            top: 10px; 
            right: 10px; 
            background: transparent; /* Make background transparent */
            border: none; /* Remove border */
            color: #ff0000; /* Red color for delete */
            cursor: pointer; /* Change cursor to pointer */
        }

        .delete-button:hover {
            color: #cc0000; /* Darker red on hover */
        }

    </style>
</head>
<body>

    <!-- Home Button -->
    <a href="admin_index.php" class="home-icon"><i class="fas fa-home"></i></a>

    <div class="container">
        <h2>Manage Reviews</h2>

        <h3>All Reviews</h3>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="review-card">
                    <h3><?php echo htmlspecialchars($row['owner_or_technician_name']); ?></h3>
                    <p><?php echo nl2br(htmlspecialchars($row['review'])); ?></p>
                    <p><em><?php echo date('F j, Y, g:i a', strtotime($row['created_at'])); ?></em></p>
                    <form method="GET" action="manage_review.php">
                        <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" class="delete-button" onclick="return confirm('Are you sure you want to delete this review?');">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No reviews yet.</p>
        <?php endif; ?>
    </div>

    <?php if (isset($deleteMessage) && $deleteMessage): ?>
        <div id="thankYouModal" class="popup" style="display: block;">
            <div class="popup-content">
                <span class="close" onclick="document.getElementById('thankYouModal').style.display='none'">&times;</span>
                <img src="https://img.icons8.com/ios-filled/50/28a745/checkmark.png" alt="Delete Icon">
                <p>Review deleted successfully!</p>
            </div>
        </div>
    <?php endif; ?>

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

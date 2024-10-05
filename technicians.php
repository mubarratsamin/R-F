<?php
// technicians.php
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

// Initialize search variables
$searchLocation = '';
$searchWorkType = '';

// Check if the search form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $searchLocation = isset($_POST['location']) ? $_POST['location'] : '';
    $searchWorkType = isset($_POST['workType']) ? $_POST['workType'] : '';
}

// Fetch technicians from the database based on search criteria
$sql = "SELECT id, name, number, email, address, workType, photo FROM technicians WHERE 1=1";

if (!empty($searchLocation)) {
    $sql .= " AND address LIKE '%" . $conn->real_escape_string($searchLocation) . "%'";
}

if (!empty($searchWorkType)) {
    $sql .= " AND workType LIKE '%" . $conn->real_escape_string($searchWorkType) . "%'";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Technicians List</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Include Font Awesome -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #8901a5;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            background-color: #007694; /* Light blue background */
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            color: white; /* Text color for the container */
        }

        h2 {
            text-align: center;
            color: #ffff; /* Darker purple for the heading */
        }

        .search-form {
            margin-bottom: 20px;
        }

        .search-form input,
        .search-form select {
            padding: 10px;
            margin-right: 10px;
            border: none;
            border-radius: 5px;
        }

        .technician-card {
            background-color: rgba(255, 255, 255, 0.1); /* Slightly transparent background for technicians */
            border: 1px solid #8901a5; /* Border color */
            border-radius: 8px; /* Rounded corners */
            padding: 15px; /* Padding for technician cards */
            margin-bottom: 15px; /* Space between technician cards */
            position: relative;
        }

        .technician-card img {
            width: 100px; /* Set width */
            height: 100px; /* Set height */
            border-radius: 50%; /* Make the image round */
            object-fit: cover; /* Ensure the image covers the area without distortion */
            margin-right: 10px; /* Keep margin to the right */
        }

        .details {
            display: none; /* Initially hide details */
            margin-top: 10px;
            padding: 10px;
            background: rgba(255, 255, 255, 0.2); /* Slightly transparent background for details */
            border-radius: 5px;
        }

        .hire-button {
            background-color: #8901a5; /* Dark purple button */
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px;
            cursor: pointer;
        }

        .hire-button:hover {
            background-color: #a96d53; /* Darker purple on hover */
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
        <h2>Available Technicians</h2>

        <form class="search-form" method="POST" action="">
            <input type="text" name="location" placeholder="Location" value="<?php echo htmlspecialchars($searchLocation); ?>">
            <select name="workType">
                <option value="">Select Work Type</option>
                <option value="Electrician" <?php echo ($searchWorkType == "Electrician") ? 'selected' : ''; ?>>Electrician</option>
                <option value="Plumber" <?php echo ($searchWorkType == "Plumber") ? 'selected' : ''; ?>>Plumber</option>
                <option value="Carpenter" <?php echo ($searchWorkType == "Carpenter") ? 'selected' : ''; ?>>Carpenter</option>
                <!-- Add more work types as needed -->
            </select>
            <input type="submit" value="Search" style="background-color: #8901a5; color: white; border: none; border-radius: 5px; padding: 10px;">
        </form>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="technician-card">
                    <img src="<?php echo htmlspecialchars($row['photo']); ?>" alt="Technician Photo">
                    <strong><?php echo htmlspecialchars($row['name']); ?></strong> <br>
                    <em><?php echo htmlspecialchars($row['workType']); ?></em> <br>
                    <button class="hire-button" onclick="toggleDetails(<?php echo $row['id']; ?>)">Hire</button>
                    <div class="details" id="details-<?php echo $row['id']; ?>">
                        <p><strong>Number:</strong> <?php echo htmlspecialchars($row['number']); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($row['email']); ?></p>
                        <p><strong>Address:</strong> <?php echo nl2br(htmlspecialchars($row['address'])); ?></p>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No technicians available.</p>
        <?php endif; ?>
    </div>

    <script>
        function toggleDetails(id) {
            const detailsDiv = document.getElementById('details-' + id);
            if (detailsDiv.style.display === 'none' || detailsDiv.style.display === '') {
                detailsDiv.style.display = 'block'; // Show details
            } else {
                detailsDiv.style.display = 'none'; // Hide details
            }
        }
    </script>
</body>
</html>

<?php
$conn->close();
?>

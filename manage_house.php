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

// Initialize location search variable
$searchLocation = "";

// Check if the search form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $searchLocation = htmlspecialchars($_POST['location']);
}

// Handle house deletion
if (isset($_GET['delete'])) {
    $houseId = intval($_GET['delete']);
    $deleteSql = "DELETE FROM house_details WHERE id = $houseId";
    $conn->query($deleteSql);
}

// Fetch house details from the database
$sql = "SELECT * FROM house_details";
if (!empty($searchLocation)) {
    $sql .= " WHERE location LIKE '%$searchLocation%'";
}
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage House - Rent 'N Repair</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background-color: #8901a5; /* Background color */
            font-family: Arial, sans-serif;
        }
        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff; /* Container background color */
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .house-item {
            border: 1px solid #ccc;
            border-radius: 5px;
            margin: 10px 0;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .more-details {
            background-color: #007694; /* Button color */
            color: white;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 5px;
            margin-right: 10px;
            transition: background-color 0.3s ease; /* Smooth transition for hover effect */
        }
        .more-details:hover {
            background-color: #005c6e; /* Darker shade for hover effect */
        }
        .delete-button {
            background-color: #d9534f; /* Delete button color */
            color: white;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease; /* Smooth transition for hover effect */
        }
        .delete-button:hover {
            background-color: #c9302c; /* Darker shade for hover effect */
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }
        .modal-content {
            background-color: #fefefe;
            margin: 20% auto;
            padding: 10px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            text-align: center;
            border-radius: 5px;
        }
        .close {
            background-color: #007694; /* Button color */
            color: white;
            border: none;
            padding: 8px 12px;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 10px;
            transition: background-color 0.3s ease; /* Smooth transition for hover effect */
        }
        .close:hover {
            background-color: #005c6e; /* Darker shade for hover effect */
        }
        .house-image {
            max-width: 100%;
            height: auto;
            margin-bottom: 10px;
        }
        .search-form {
            margin-bottom: 20px;
        }
        .search-input {
            padding: 10px;
            width: calc(80% - 22px);
            margin-right: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .search-button {
            padding: 10px 15px;
            background-color: #007694; /* Button color */
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease; /* Smooth transition for hover effect */
        }
        .search-button:hover {
            background-color: #005c6e; /* Darker shade for hover effect */
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
        function showModal(ownerName, location, phoneNumber, imagePath) {
            document.getElementById('modal').style.display = 'block';
            document.getElementById('modalOwnerName').innerText = ownerName;
            document.getElementById('modalLocation').innerText = location;
            document.getElementById('modalPhoneNumber').innerText = phoneNumber;
            document.getElementById('modalImage').src = imagePath;
        }

        function closeModal() {
            document.getElementById('modal').style.display = 'none';
        }
    </script>
</head>
<body>

<a href="admin_index.php" class="home-icon"><i class="fas fa-home"></i></a>

<div class="container">
    <h2>Manage House</h2>

    <!-- Search Form -->
    <form class="search-form" method="POST" action="">
        <input type="text" class="search-input" name="location" placeholder="Search by location" value="<?php echo htmlspecialchars($searchLocation); ?>">
        <button type="submit" class="search-button">Search</button>
    </form>

    <?php
    if ($result->num_rows > 0) {
        // Output data of each row
        while ($row = $result->fetch_assoc()) {
            echo '<div class="house-item">';
            echo '<div>';
            echo '<h3>' . htmlspecialchars($row["name"]) . '</h3>';
            echo '<p>Location: ' . htmlspecialchars($row["location"]) . '</p>';
            echo '<p>Rent Per Month: ' . htmlspecialchars($row["rent_per_month"]) . '</p>';
            echo '</div>';
            echo '<div>';
            echo '<button class="more-details" onclick="showModal(\'' . htmlspecialchars($row["name"]) . '\', \'' . htmlspecialchars($row["location"]) . '\', \'' . htmlspecialchars($row["number"]) . '\', \'' . htmlspecialchars($row["house_photo"]) . '\')">More Details</button>';
            echo '<a href="?delete=' . htmlspecialchars($row["id"]) . '" onclick="return confirm(\'Are you sure you want to delete this house?\');" class="delete-button">Delete</a>';
            echo '</div>';
            echo '</div>';
        }
    } else {
        echo "<p>No houses available.</p>";
    }
    ?>

</div>

<!-- Modal -->
<div id="modal" class="modal">
    <div class="modal-content">
        <h3>House Details</h3>
        <img id="modalImage" class="house-image" src="" alt="House Photo">
        <p><strong>Owner:</strong> <span id="modalOwnerName"></span></p>
        <p><strong>Location:</strong> <span id="modalLocation"></span></p>
        <p><strong>Phone:</strong> <span id="modalPhoneNumber"></span></p>
        <button class="close" onclick="closeModal()">Close</button>
    </div>
</div>

</body>
</html>

<?php
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Rent 'N Repair</title>
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
            background-color: rgba(0, 0, 0, 0.8);
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            text-align: center;
        }
        h1 {
            color: #ede8d0;
        }
        .admin-button {
            display: inline-block;
            padding: 15px 30px;
            margin: 20px;
            background-color: #007694;
            color: white;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 18px;
            transition: background-color 0.3s ease;
        }
        .admin-button:hover {
            background-color: #005c6e; /* Darker shade on hover */
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Admin Dashboard</h1>
    <a href="manage_house.php" class="admin-button">Manage House</a>
    <a href="manage_technician.php" class="admin-button">Manage Technician</a>
    <a href="manage_review.php" class="admin-button">Manage Reviews</a>
</div>

<!-- Include Font Awesome -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>

</body>
</html>

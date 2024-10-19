<?php
session_start();
include 'db.php'; // Include the database connection

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

// Initialize variables for the form
$id = $name = $brand = $scent_notes = $price = "";
$update = false;

// Handle adding or updating perfume
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($mysqli, $_POST['name']);
    $brand = mysqli_real_escape_string($mysqli, $_POST['brand']);
    $scent_notes = mysqli_real_escape_string($mysqli, $_POST['scent_notes']);
    $price = mysqli_real_escape_string($mysqli, $_POST['price']);

    if (isset($_POST['add'])) {
        $mysqli->query("INSERT INTO perfumes (name, brand, scent_notes, price) VALUES ('$name', '$brand', '$scent_notes', '$price')");
    } elseif (isset($_POST['update'])) {
        $id = $_POST['id'];
        $mysqli->query("UPDATE perfumes SET name='$name', brand='$brand', scent_notes='$scent_notes', price='$price' WHERE id=$id");
        
        // Redirect back to the form after updating
        header("Location: index.php"); // Redirect to clear form fields
        exit();
    }
}

// Handle deleting perfume
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $mysqli->query("DELETE FROM perfumes WHERE id=$id");
}

// Fetch perfumes
$perfumes = $mysqli->query("SELECT * FROM perfumes");

// Check if editing a specific perfume
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $perfume_to_edit = $mysqli->query("SELECT * FROM perfumes WHERE id=$id")->fetch_assoc();
    
    if ($perfume_to_edit) {
        // Populate form fields with the existing perfume data
        $name = htmlspecialchars($perfume_to_edit['name']);
        $brand = htmlspecialchars($perfume_to_edit['brand']);
        $scent_notes = htmlspecialchars($perfume_to_edit['scent_notes']);
        $price = htmlspecialchars($perfume_to_edit['price']);
        $update = true; // Set update flag to true for editing
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Perfume Inventory Management</title>
</head>
<body>
    <h2>Welcome!</h2>
    
    <form method="post">
        <h3><?php echo $update ? 'Edit Perfume' : 'Add Perfume'; ?></h3>
        <?php if ($update): ?>
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
        <?php endif; ?>
        <input type="text" name="name" required placeholder="Perfume Name" value="<?php echo htmlspecialchars($name); ?>">
        <input type="text" name="brand" required placeholder="Brand" value="<?php echo htmlspecialchars($brand); ?>">
        <textarea name="scent_notes" required placeholder="Scent Notes"><?php echo htmlspecialchars($scent_notes); ?></textarea>
        <input type="number" step="0.01" name="price" required placeholder="Price" value="<?php echo htmlspecialchars($price); ?>">
        <button type="submit" name="<?php echo $update ? 'update' : 'add'; ?>"><?php echo $update ? 'Update' : 'Add'; ?></button>
    </form>

    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Brand</th>
            <th>Scent Notes</th>
            <th>Price</th>
            <th>Actions</th>
        </tr>
        <?php while ($perfume = $perfumes->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($perfume['id']); ?></td>
                <td><?php echo htmlspecialchars($perfume['name']); ?></td>
                <td><?php echo htmlspecialchars($perfume['brand']); ?></td>
                <td><?php echo htmlspecialchars($perfume['scent_notes']); ?></td>
                <td><?php echo htmlspecialchars($perfume['price']); ?></td>
                <td>
                    <a class="btn" href="?edit=<?php echo $perfume['id']; ?>">Edit</a>
                    <a class="btn" href="?delete=<?php echo $perfume['id']; ?>" onclick="return confirm('Are you sure you want to delete?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <a href="logout.php" class="btn">Logout</a>

</body>
</html>

<?php 
$mysqli->close(); 
?>
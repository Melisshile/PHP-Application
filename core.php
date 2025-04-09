<?php
include 'db_h.php';

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

// Create a PDO instance
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password, $options);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}

$action = $_POST['action'] ?? null;

switch ($action) {
    case 'create':
        createClient();
        break;
    case 'update':
        updateClient();
        break;
    case 'delete':
        deleteClient();
        break;
    default:
        echo "Invalid action";
        break;
}

function createClient() {
    global $pdo; // Use the global $pdo object for database interaction
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];
    $id_passport_number = $_POST['id_passport_number'];

    // Check for duplicates
    $stmt = $pdo->prepare("SELECT * FROM client WHERE id_passport_number = ?");
    $stmt->execute([$id_passport_number]);

    if ($stmt->rowCount() > 0) {
        echo "duplicate";
        return;
    }

    // Insert new client
    $stmt = $pdo->prepare("INSERT INTO client (name, surname, email, id_passport_number) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $surname, $email, $id_passport_number]);

    if ($stmt->rowCount() > 0) {
        echo "success";
    } else {
        echo "Failed to save client.";
    }
}

function updateClient() {
    global $pdo;
    $id_passport_number = $_POST['id_passport_number'];
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];

    // Update client info
    $stmt = $pdo->prepare("UPDATE client SET name = ?, surname = ?, email = ? WHERE id_passport_number = ?");
    $stmt->execute([$name, $surname, $email, $id_passport_number]);

    if ($stmt->rowCount() > 0) {
        echo "success";
    } else {
        echo "Update failed.";
    }
}

function deleteClient() {
    global $pdo;
    $id_passport_number = $_POST['id_passport_number'];

    // Delete client
    $stmt = $pdo->prepare("DELETE FROM client WHERE id_passport_number = ?");
    $stmt->execute([$id_passport_number]);

    if ($stmt->rowCount() > 0) {
        echo "deleted";
    } else {
        echo "Delete failed.";
    }
}
?>

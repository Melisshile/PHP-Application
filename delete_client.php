<?php
include 'db_h.php';

$id = $_POST['id'];

$sql = "DELETE FROM client WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "deleted";
} else {
    echo "error";
}
$stmt->close();
?>
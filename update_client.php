<?php
include 'db_h.php';

$id = $_POST['id'];
$name = $_POST['name'];
$surname = $_POST['surname'];
$email = $_POST['email'];
$id_pass = $_POST['id_passport_number'];

$sql = "UPDATE client SET name=?, surname=?, email=?, id_passport_number=? WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssi", $name, $surname, $email, $id_pass, $id);

if ($stmt->execute()) {
    echo "updated";
} else {
    echo "error";
}

$conn->close();
?>
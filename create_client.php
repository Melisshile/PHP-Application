<?php
include 'db_h.php';

$name = $_POST['name'];
$surname = $_POST['surname'];
$email = $_POST['email'];
$id_pass = $_POST['id_passport_number'];

$sql = "INSERT INTO client (name, surname, email, id_passport_number) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $name, $surname, $email, $id_pass);

if ($stmt->execute()) {
    echo "success";
} else {
    echo "error";
}

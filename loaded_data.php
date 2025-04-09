<?php
include 'db_h.php';

$output = '';
$sql = "SELECT * FROM client";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($row['id']) . "</td>";
    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
    echo "<td>" . htmlspecialchars($row['surname']) . "</td>";
    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
    echo "<td>" . htmlspecialchars($row['id_passport_number']) . "</td>";
    echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
    echo "<td>
            <button class='editBtn'>Edit</button>
            <button class='deleteBtn'>Delete</button>
          </td>";
    echo "</tr>";
}


echo $output;
?>

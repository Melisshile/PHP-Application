<?php
    include 'db_h.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Client Information</title>
    <link rel="stylesheet" href="style.css">
    <script src="functionality.js" defer></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>  

    <section class="form-display">
        <h1>Client Information Edit Board</h1>
        <form id="formClient">
            <!-- <input type="hidden" id="clientId" name="clientId"> -->
            <label>id: <input type="number" id="clientId" name="clientId" required></label><br>
            <label>Name: <input type="text" id="clientName" name="name" required></label><br>
            <label>Surname: <input type="text" id="clientSurname" name="surname" required></label><br>
            <label>Email: <input type="email" id="clientEmail" name="email" required></label><br>
            <label>ID/Passport Number: <input type="text" id="clientIDPass" name="id_passport_number" required></label><br>
            <button type="submit">Save Client</button>
        </form><br>
    </section>
    <hr>

    <section class="add-form">
        <form id="clientForm">
            <h2>Client Information</h2>
            <input type="text" name="name" placeholder="Name" required />
            <input type="text" name="surname" placeholder="Surname" required />
            <input type="email" name="email" placeholder="Email" required />
            <select name="id_passport_number" id="id_passport_number">
                <option value="">Select a specific identification</option>
                <option value="id_number">ID</option>
                <option value="passport_number">Passport</option>
            </select>
            <div class="passport_field">
                <input type="text" id="passport" name="passport_num" placeholder="Passport Number">
            </div>
            <div class="id_field">
                <input type="number" id="id_number" name="id_num" placeholder="ID Number">
            </div>
            <button type="submit">Add Client</button>
        </form>
    </section>
    <section class="client-information-display"></section>
    <hr>

    <section class="report">
    <button id="loadBtn">Load Clients</button><br>
        <table id="clientTable" border="1">
            <thead>
                <tr>
                    <th>id</th>
                    <th>Name</th>
                    <th>Surname</th>
                    <th>Email</th>
                    <th>ID/Passport</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include 'db_h.php';
                $sql = "SELECT * FROM client";
                $result = $conn->query($sql);

                while ($row = $result->fetch_assoc()) {
                    echo "<tr data-id='" . htmlspecialchars($row['id']) . "'>";
                    echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['surname']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['id_passport_number']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                    echo "<td>
                            <button class='editBtn'>Edit</button><br>
                            <button class='deleteBtn'>Delete</button>
                        </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>

    </section>

</body>
</html>

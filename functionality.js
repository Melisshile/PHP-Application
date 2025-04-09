// AJAX handler 
document.getElementById("loadBtn").addEventListener("click", function () {
    fetch("loaded_data.php")
        .then(response => response.text())
        .then(data => {
            const tbody = document.querySelector("#clientTable tbody");
            tbody.innerHTML = data;
        })
        .catch(error => console.error("Error loading clients:", error));
});

document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("formClient");
    const form_add = document.getElementById("clientForm");

    const idPassportSelect = document.getElementById("id_passport_number");
    const idField = document.querySelector(".id_field");
    const passportField = document.querySelector(".passport_field");
    const formDisplay = document.querySelector(".add-form");
    const clientDisplay = document.querySelector(".client-information-display");

    idField.style.display = "none";
    passportField.style.display = "none";

    const setFieldVisibility = () => {
        const value = idPassportSelect.value;
        idField.style.display = value === "id_number" ? "block" : "none";
        passportField.style.display = value === "passport_number" ? "block" : "none";
    };

    setFieldVisibility();
    idPassportSelect.addEventListener("change", setFieldVisibility);

    let currentID = null;

    form_add.addEventListener("submit", function (e) {
        e.preventDefault();

        const name = form_add.name.value.trim();
        const surname = form_add.surname.value.trim();
        const email = form_add.email.value.trim();
        const selection = idPassportSelect.value;
        const idNumber = document.getElementById("id_number").value.trim();
        const passport = document.getElementById("passport").value.trim();

        if (!name || !surname || !email || !selection) {
            alert("Please fill in all required fields.");
            return;
        }

        if (selection === "id_number" && !/^\d{13}$/.test(idNumber)) {
            alert("Please enter a valid 13-digit South African ID number.");
            return;
        }

        if (selection === "passport_number" && passport === "") {
            alert("Please enter a passport number.");
            return;
        }

        const id_passport = selection === "id_number" ? idNumber : passport;
        currentID = id_passport;

        const formData_add = new FormData();
        formData_add.append("name", name);
        formData_add.append("surname", surname);
        formData_add.append("email", email);
        formData_add.append("id_passport_number", id_passport);
        formData_add.append("action", "create");

        fetch("core.php", {
            method: "POST",
            body: formData_add
        })
        .then(res => res.text())
        .then(response => {
            if (response === "duplicate") {
                alert("This ID or Passport number is already registered.");
            } else if (response === "success") {
                showClientInfo({ name, surname, email, id_passport, selection });
            } else {
                alert("Failed to save data: " + response);
            }
        })
        .catch(error => alert("Error: " + error));
    });

    function showClientInfo({ name, surname, email, id_passport, selection }) {
        formDisplay.style.display = "none";
        clientDisplay.style.display = "block";

        clientDisplay.innerHTML = `
            <div class="client-card">
                <h2>Client Saved Successfully</h2>
                <table>
                    <tr><th>Name</th><td>${name}</td></tr>
                    <tr><th>Surname</th><td>${surname}</td></tr>
                    <tr><th>Email</th><td>${email}</td></tr>
                    <tr><th>${selection === "id_number" ? "ID Number" : "Passport Number"}</th><td>${id_passport}</td></tr>
                </table>
                <button id="updateBtn">Update</button>
                <button id="deleteBtn">Delete</button>
                <button id="resetBtn">New Entry</button>
            </div>
        `;

        document.getElementById("updateBtn").addEventListener("click", () => {
            form_add.name.value = name;
            form_add.surname.value = surname;
            form_add.email.value = email;
            idPassportSelect.value = selection;
            document.getElementById("id_number").value = selection === "id_number" ? id_passport : "";
            document.getElementById("passport").value = selection === "passport_number" ? id_passport : "";
            idField.style.display = selection === "id_number" ? "block" : "none";
            passportField.style.display = selection === "passport_number" ? "block" : "none";

            formDisplay.style.display = "block";
            clientDisplay.style.display = "none";

            form_add.onsubmit = function (e) {
                e.preventDefault();

                const updatedData = new FormData(form_add);
                updatedData.append("id_passport_number", currentID);
                updatedData.append("action", "update");

                fetch("core.php", {
                    method: "POST",
                    body: updatedData
                })
                .then(res => res.text())
                .then(response => {
                    if (response === "success") {
                        alert("Client updated successfully.");
                        window.location.reload();
                    } else {
                        alert("Update failed: " + response);
                    }
                });
            };
        });

        document.getElementById("deleteBtn").addEventListener("click", () => {
            const deleteData = new FormData();
            deleteData.append("id_passport_number", currentID);
            deleteData.append("action", "delete");

            fetch("core.php", {
                method: "POST",
                body: deleteData
            })
            .then(res => res.text())
            .then(response => {
                if (response === "deleted") {
                    alert("Client deleted successfully.");
                    window.location.reload();
                } else {
                    alert("Delete failed: " + response);
                }
            });
        });

        document.getElementById("resetBtn").addEventListener("click", () => {
            form_add.reset();
            setFieldVisibility();
            clientDisplay.style.display = "none";
            formDisplay.style.display = "block";
        });
    }

    form.addEventListener("submit", function (e) {
        e.preventDefault();

        const id = document.getElementById("clientId").value;
        const name = document.getElementById("clientName").value;
        const surname = document.getElementById("clientSurname").value;
        const email = document.getElementById("clientEmail").value;
        const id_pass = document.getElementById("clientIDPass").value;

        const formData = new FormData();
        formData.append("name", name);
        formData.append("surname", surname);
        formData.append("email", email);
        formData.append("id_passport_number", id_pass);
        if (id) formData.append("id", id);

        const url = id ? "update_client.php" : "create_client.php";

        fetch(url, {
            method: "POST",
            body: formData
        })
        .then(res => res.text())
        .then(data => {
            if (data === "success" || data === "updated") {
                alert("Saved!");
                location.reload();
            } else {
                alert("Error saving client.");
                console.log(data);
            }
        });
    });

    document.querySelector("table").addEventListener("click", function (e) {
        if (e.target.classList.contains("deleteBtn")) {
            const row = e.target.closest("tr");
            const id = row.getAttribute("data-id");

            const formData = new FormData();
            formData.append("id", id);

            fetch("delete_client.php", {
                method: "POST",
                body: formData
            })
            .then(res => res.text())
            .then(data => {
                if (data === "deleted") {
                    alert("Deleted!");
                    location.reload();
                } else {
                    alert("Delete failed.");
                    console.log(data);
                }
            });

        } else if (e.target.classList.contains("editBtn")) {
            const row = e.target.closest("tr");

            document.getElementById("clientId").value = row.getAttribute("data-id");
            document.getElementById("clientName").value = row.children[1].innerText;
            document.getElementById("clientSurname").value = row.children[2].innerText;
            document.getElementById("clientEmail").value = row.children[3].innerText;
            document.getElementById("clientIDPass").value = row.children[4].innerText;

            window.scrollTo(0, 0);
        }
    });
});

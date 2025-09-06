<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Inventory</title>
</head>
<body>

<div class="sidebar">
    <h2>ADMIN</h2>
    <a href="<?= base_url('Central_AD') ?>">DASHBOARD</a>
    <a href="<?= base_url('admin/inventory') ?>" class="active">INVENTORY</a>
    <a href="#">SUPPLIERS</a>
    <a href="#">ORDERS</a>
    <a href="#">FRANCHISING</a>
    <a href="#">REPORTS</a>
    <a href="#">SETTINGS</a>
    <a href="<?= base_url('logout') ?>" class="logout">Logout</a>
</div>

<div class="main">
    <div class="header">
        <span>Inventory Management</span>
        <a href="request_stock.html">+ Request Stock Item</a>
    </div>

    <div class="table-container">
        <h3>Branch Inventory Alert</h3>
        <table>
            <thead>
                <tr>
                    <th>Branch Name</th>
                    <th>Item Name</th>
                    <th>Type</th>
                    <th>Quantity</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Main Branch</td>
                    <td>Chicken</td>
                    <td>Thigh</td>
                    <td>120 kg</td>
                    <td class="status-cell"><span class="status low-stock">Low Stock</span></td>
                    <td><a href="#" class="btn refill">Refill</a></td>
                </tr>
                <tr>
                    <td>Branch A</td>
                    <td>Chicken</td>
                    <td>Wing</td>
                    <td>20 kg</td>
                    <td class="status-cell"><span class="status low-stock">Low Stock</span></td>
                    <td><a href="#" class="btn refill">Refill</a></td>
                </tr>
                <tr>
                    <td>Branch B</td>
                    <td>Chicken</td>
                    <td>Breast</td>
                    <td>0 kg</td>
                    <td class="status-cell"><span class="status out-of-stock">Out of Stock</span></td>
                    <td><a href="#" class="btn refill">Refill</a></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="table-container">
        <div class="table-header">
            <h3>Central Branch Available Stock</h3>
            <input type="text" id="searchBox" placeholder="Search item...">
        </div>
        <table id="stockTable">
            <thead>
                <tr>
                    <th>Item Name</th>
                    <th>Type</th>
                    <th>Total Quantity</th>
                    <th>Last Updated</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Chicken</td>
                    <td>Thigh</td>
                    <td>300 kg</td>
                    <td>2025-09-03</td>
                    <td>
                        <a href="#" class="btn update" onclick="updateItem(this)">Update</a>
                        <a href="#" class="btn delete" onclick="deleteItem(this)">Delete</a>
                    </td>
                </tr>
                <tr>
                    <td>Oil</td>
                    <td>Cooking Oil</td>
                    <td>100 kg</td>
                    <td>2025-09-03</td>
                    <td>
                        <a href="#" class="btn update" onclick="updateItem(this)">Update</a>
                        <a href="#" class="btn delete" onclick="deleteItem(this)">Delete</a>
                    </td>
                </tr>
                <tr>
                    <td>Chicken</td>
                    <td>Wing</td>
                    <td>150 kg</td>
                    <td>2025-09-03</td>
                    <td>
                        <a href="#" class="btn update" onclick="updateItem(this)">Update</a>
                        <a href="#" class="btn delete" onclick="deleteItem(this)">Delete</a>
                    </td>
                </tr>
                <tr>
                    <td>Chicken</td>
                    <td>Breast</td>
                    <td>100 kg</td>
                    <td>2025-09-03</td>
                    <td>
                        <a href="#" class="btn update" onclick="updateItem(this)">Update</a>
                        <a href="#" class="btn delete" onclick="deleteItem(this)">Delete</a>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="button-container">
            <button class="btn edit" onclick="openForm()">+ Add/Edit Item</button>
        </div>
    </div>
</div>

<div id="formPopup" class="form-popup">
    <div class="form-container">
        <h3>Add / Edit Item</h3>
        <label>Item Name:</label>
        <input type="text" id="itemName">
        <label>Type:</label>
        <input type="text" id="itemType">
        <label>Total Quantity (kg):</label>
        <input type="number" id="itemQty">
        <button id="confirmBtn">Confirm</button>
        <button onclick="closeForm()">Cancel</button>
    </div>
</div>

<script>
let editingRow = null;
const form = {
    popup: document.getElementById("formPopup"),
    name: document.getElementById("itemName"),
    type: document.getElementById("itemType"),
    qty: document.getElementById("itemQty"),
    confirm: document.getElementById("confirmBtn")
};

function openForm(row=null) {
    editingRow = row;
    if (row) {
        form.name.value = row.cells[0].innerText;
        form.type.value = row.cells[1].innerText;
        form.qty.value = row.cells[2].innerText.replace(" kg","");
    } else {
        form.name.value = "";
        form.type.value = "";
        form.qty.value = "";
    }
    form.popup.style.display = "flex";
}

function closeForm() {
    form.popup.style.display = "none";
    editingRow = null;
}

function addOrUpdateItem() {
    const name = form.name.value.trim(),
          type = form.type.value.trim(),
          qty = form.qty.value.trim(),
          date = new Date().toISOString().split("T")[0];
    if (!name || !type || !qty) return;

    if (editingRow) {
        editingRow.cells[0].innerText = name;
        editingRow.cells[1].innerText = type;
        editingRow.cells[2].innerText = qty + " kg";
        editingRow.cells[3].innerText = date;
    } else {
        const row = document.getElementById("stockTable").insertRow(-1);
        row.innerHTML = `
            <td>${name}</td>
            <td>${type}</td>
            <td>${qty} kg</td>
            <td>${date}</td>
            <td>
                <a href="#" class="btn update" onclick="openForm(this.closest('tr'))">Update</a>
                <a href="#" class="btn delete" onclick="this.closest('tr').remove()">Delete</a>
            </td>`;
    }
    closeForm();
}

form.confirm.addEventListener("click", addOrUpdateItem);

document.getElementById("searchBox").addEventListener("keyup", function() {
    const filter = this.value.toLowerCase();
    document.querySelectorAll("#stockTable tbody tr").forEach(row => {
        row.style.display = row.innerText.toLowerCase().includes(filter) ? "" : "none";
    });
});
</script>


</body>
</html>

<style>
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background-color: #f4f6f8;
}
.sidebar {
    position: fixed;
    top: 0;
    left: 0;
    width: 220px;
    height: 100%;
    background-color: #c9c9c9;
    padding-top: 20px;
    box-sizing: border-box;
    border-radius: 0 20px 20px 0;
}
.sidebar h2 {
    text-align: center;
    font-size: 18px;
    margin-bottom: 40px;
    color: black;
}
.sidebar a {
    display: block;
    padding: 14px 15px;
    text-decoration: none;
    color: white;
    margin: 15px 20px;
    background-color: #494949;
    text-align: center;
    border-radius: 20px;
    font-weight: bold;
    transition: 0.3s;
}
.sidebar a:hover { background-color: #0c0c0c; }
.sidebar a.active { background-color: #000000; }
.sidebar a.logout {
    background-color: #e74c3c;
    position: absolute;
    bottom: 20px;
    left: 15px;
    right: 15px;
    border-radius: 20px;
}
.main {
    margin-left: 240px;
    padding: 30px 40px;
    max-width: calc(100% - 240px);
}
.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #b6b6b6;
    padding: 15px 20px;
    font-weight: bold;
    border-radius: 20px;
    color: #000000;
}
.header a {
    background-color: #494949;
    color: white;
    padding: 7px 15px;
    text-decoration: none;
    border-radius: 20px;
    font-size: 14px;
    transition: 0.3s;
}
.header a:hover { background-color: #000000; }
.table-container {
    margin-top: 30px;
    background-color: #ffffff;
    border-radius: 20px;
    padding: 20px;
    box-shadow: 0 3px 8px rgba(0,0,0,0.1);
    position: relative;
}
.table-container h3 {
    margin: 0;
    font-size: 18px;
    color: #333;
}
.table-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}
.table-header input {
    padding: 6px 10px;
    border-radius: 10px;
    border: 1px solid #ccc;
}
table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 10px;
}
th, td {
    padding: 15px;
    text-align: center;
    background-color: #e0e0e0;
}
th {
    background-color: #494949;
    color: white;
    border-radius: 10px 10px 0 0;
}
tr:hover td { background-color: #b9b9b9; }
.status-cell {
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #e0e0e0;
}
.status {
    display: inline-block;
    padding: 5px 12px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: bold;
    text-align: center;
}
.status.low-stock { background-color: #f1c40f; color: #000; }
.status.out-of-stock { background-color: #e74c3c; color: #fff; }
.btn.refill {
    background: #e74c3c;
    color: #fff;
    padding: 5px 12px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: bold;
    text-decoration: none;
    transition: 0.3s;
}
.btn.refill:hover { background: red; }
.button-container {
    display: flex;
    justify-content: flex-end;
    margin-top: 15px;
}
.btn.edit {
    background: #494949;
    color: #fff;
    padding: 8px 15px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: bold;
    border: none;
    cursor: pointer;
}
.btn.edit:hover { background: #000; }
.form-popup {
    display: none;
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0,0,0,0.5);
    justify-content: center;
    align-items: center;
}
.form-container {
    background: #fff;
    padding: 20px;
    border-radius: 20px;
    width: 300px;
    display: flex;
    flex-direction: column;
    gap: 10px;
}
.form-container input, .form-container button {
    padding: 8px;
    border-radius: 10px;
    border: 1px solid #ccc;
}
.form-container button {
    background: #494949;
    color: #fff;
    border: none;
    cursor: pointer;
    font-weight: bold;
}
.form-container button:hover { background: #000; }
.btn.update {
    background: #3498db;
    color: #fff;
    padding: 5px 12px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: bold;
    text-decoration: none;
    transition: 0.3s;
    margin-right: 5px;
}
.btn.update:hover { background: #2980b9; }
.btn.delete {
    background: #e74c3c;
    color: #fff;
    padding: 5px 12px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: bold;
    text-decoration: none;
    transition: 0.3s;
}
.btn.delete:hover { background: red; }
</style>

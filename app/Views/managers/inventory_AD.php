<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Inventory</title>
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>
</head>
<body>
<div class="sidebar">
    <h2>Welcome, <?= session()->get('username') ?></h2>
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
         <a href="<?= site_url('Central_AD/request_stock') ?>">+ Request Stock Item</a>
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
                    <th>Barcode</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
        <div class="button-container">
            <button class="btn print" onclick="printReport()">Print Report</button>
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

function saveStockToLocal() {
    const stock = [];
    document.querySelectorAll("#stockTable tbody tr").forEach(row => {
        stock.push({
            name: row.cells[0].innerText,
            type: row.cells[1].innerText,
            qty: row.cells[2].innerText.replace(" kg", ""),
            date: row.cells[3].innerText
        });
    });
    localStorage.setItem("stockItems", JSON.stringify(stock));
}

function loadStockFromLocal() {
    const stock = JSON.parse(localStorage.getItem("stockItems") || "[]");
    const tbody = document.querySelector("#stockTable tbody");
    tbody.innerHTML = "";
    stock.forEach(item => {
        const row = tbody.insertRow();
        row.innerHTML = `
            <td>${item.name}</td>
            <td>${item.type}</td>
            <td>${item.qty} kg</td>
            <td>${item.date}</td>
            <td><svg class="barcode" data-code="${item.name}-${item.type}"></svg></td>
            <td>
                <a href="#" class="btn update" onclick="openForm(this.closest('tr'))">Update</a>
                <a href="#" class="btn delete" onclick="deleteRow(this.closest('tr'))">Delete</a>
            </td>`;
    });
    generateBarcodes();
}

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
        editingRow.cells[4].innerHTML = `<svg class="barcode" data-code="${name}-${type}"></svg>`;
    } else {
        const row = document.getElementById("stockTable").insertRow(-1);
        row.innerHTML = `
            <td>${name}</td>
            <td>${type}</td>
            <td>${qty} kg</td>
            <td>${date}</td>
            <td><svg class="barcode" data-code="${name}-${type}"></svg></td>
            <td>
                <a href="#" class="btn update" onclick="openForm(this.closest('tr'))">Update</a>
                <a href="#" class="btn delete" onclick="deleteRow(this.closest('tr'))">Delete</a>
            </td>`;
    }
    generateBarcodes();
    saveStockToLocal();
    closeForm();
}

function deleteRow(row) {
    row.remove();
    saveStockToLocal();
}

document.getElementById("searchBox").addEventListener("keyup", function() {
    const filter = this.value.toLowerCase();
    document.querySelectorAll("#stockTable tbody tr").forEach(row => {
        row.style.display = row.innerText.toLowerCase().includes(filter) ? "" : "none";
    });
});

function generateBarcodes() {
    document.querySelectorAll(".barcode").forEach(el => {
        JsBarcode(el, el.getAttribute("data-code"), {
            format: "CODE128",
            lineColor: "#000",
            width: 2,
            height: 40,
            displayValue: true,
            fontSize: 14
        });
    });
}

function printReport() {
    const content = document.getElementById("stockTable").outerHTML;
    const win = window.open("", "", "height=900,width=1200");
    win.document.write(`
        <html>
        <head>
            <title>Inventory Report</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                table { width: 100%; border-collapse: collapse; }
                th, td { border: 1px solid #000; padding: 8px; text-align: center; }
                th { background: #494949; color: white; }
                .barcode { width: 130px; height: 50px; }
                @page { size: A4; margin: 20mm; }
            </style>
        </head>
        <body>
            <h2 style="text-align:center;">Central Branch Stock Report</h2>
            ${content}
        </body>
        </html>
    `);
    win.document.close();
    win.print();
}

form.confirm.addEventListener("click", addOrUpdateItem);
window.addEventListener("load", loadStockFromLocal);
</script>

    <style>
        body {
    margin: 0;
    font-family: Arial, sans-serif;
    background-color: #f4f6f8;
}

.main {
    margin-left: 240px;
    max-width: calc(100% - 240px);
    padding: 30px 40px;
}

.sidebar {
    position: fixed;
    top: 0;
    left: 0;
    width: 220px;
    height: 100%;
    padding-top: 20px;
    box-sizing: border-box;
    background-color: #c9c9c9;
    border-radius: 0 20px 20px 0;
    border: 1px solid black;

}

.sidebar h2 {
    margin-bottom: 40px;
    text-align: center;
    font-size: 18px;
    color: black;
}

.sidebar a {
    display: block;
    margin: 15px 20px;
    padding: 14px 15px;
    text-align: center;
    text-decoration: none;
    font-weight: bold;
    color: white;
    background-color: #494949;
    border-radius: 20px;
    transition: 0.3s;
    border: 2px solid black;

}

.sidebar a:hover {
    background-color: #0c0c0c;
}

.sidebar a.active {
    background-color: #000;
}

.sidebar a.logout {
    position: absolute;
    bottom: 20px;
    left: 15px;
    right: 15px;
    background-color: #e74c3c;
    border-radius: 20px;
}

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 20px;
    font-weight: bold;
    color: #000;
    background-color: #b6b6b6;
    border-radius: 20px;
    border: 1px solid black;

}

.header a {
    padding: 7px 15px;
    font-size: 14px;
    color: #fff;
    background-color: #494949;
    border-radius: 20px;
    text-decoration: none;
    transition: 0.3s;
    border: 1px solid black;

}

.header a:hover {
    background-color: #000;
}

.table-container {
    margin-top: 30px;
    padding: 20px;
    background-color: #fff;
    border-radius: 20px;
    box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
    border: 1px solid black;

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
    border: 1px solid #ccc;
    border-radius: 10px;
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
    color: #fff;
    background-color: #494949;
    border-radius: 10px 10px 0 0;
}

tr:hover td {
    background-color: #b9b9b9;
}

.status-cell {
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #e0e0e0;
}

.status {
    display: inline-block;
    padding: 5px 12px;
    font-size: 12px;
    font-weight: bold;
    border-radius: 6px;
    text-align: center;
}

.status.low-stock {
    background-color: #f1c40f;
    color: #000;
}

.status.out-of-stock {
    background-color: #e74c3c;
    color: #fff;
}

.btn {
    padding: 5px 12px;
    font-size: 12px;
    font-weight: bold;
    border-radius: 6px;
    text-decoration: none;
    transition: 0.3s;
    cursor: pointer;
}

.btn.refill {
    background: #e74c3c;
    color: #fff;
}

.btn.refill:hover {
    background: red;
}

.btn.update {
    background: #3498db;
    color: #fff;
    margin-right: 5px;
}

.btn.update:hover {
    background: #2980b9;
}

.btn.delete {
    background: #e74c3c;
    color: #fff;
}

.btn.delete:hover {
    background: red;
}

.button-container {
    display: flex;
    justify-content: space-between;
    margin-top: 15px;
}

.btn.edit {
    padding: 8px 15px;
    font-size: 14px;
    font-weight: bold;
    background: #494949;
    color: #fff;
    border-radius: 20px;
    border: none;
}

.btn.edit:hover {
    background: #000;
}

.btn.print {
    padding: 8px 15px;
    font-size: 14px;
    font-weight: bold;
    background: #27ae60;
    color: #fff;
    border-radius: 20px;
    border: none;
}

.btn.print:hover {
    background: #1e8449;
}

.form-popup {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    justify-content: center;
    align-items: center;
}

.form-container {
    display: flex;
    flex-direction: column;
    gap: 10px;
    width: 300px;
    padding: 20px;
    background: #fff;
    border-radius: 20px;
}

.form-container h3 {
    margin: 0 0 10px 0;
    font-size: 18px;
    text-align: center;
}

.form-container input,
.form-container button {
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 10px;
}

.form-container button {
    font-weight: bold;
    border: none;
    cursor: pointer;
    transition: 0.3s;
}

.form-container #confirmBtn {
    background: #3498db;
    color: #fff;
}

.form-container #confirmBtn:hover {
    background: #2980b9;
}

.form-container button:nth-of-type(2) {
    background: #e74c3c;
    color: #fff;
}

.form-container button:nth-of-type(2):hover {
    background: red;
}

.barcode {
    display: block;
    width: 130px !important;
    height: 50px !important;
    margin: 5px auto;
}

@media print {
    #stockTable th:last-child,
    #stockTable td:last-child {
        display: none !important;
    }
    #stockTable {
        table-layout: auto !important;
        width: 100% !important;
    }
}

    </style>
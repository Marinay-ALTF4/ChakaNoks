<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="<?= base_url('css/request_stock.css') ?>">

<title>Request Stock Item</title>
</head>
<body>

<div class="sidebar">
    <h2>ADMIN</h2>
    <a href="Central_AD.html">DASHBOARD</a>
    <a href="inventory_AD.html">INVENTORY</a>
    <a href="#">SUPPLIERS</a>
    <a href="#">ORDERS</a>
    <a href="#">FRANCHISING</a>
    <a href="#">REPORTS</a>
    <a href="#">SETTINGS</a>
    <a href="Login.html" class="logout">Logout</a>
</div>

<div class="main">
    <div class="header">
        <span>Request Stock Item</span>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Branch Name</th>
                    <th>Item Name</th>
                    <th>Type</th>
                    <th>Quantity</th>
                    <th>Message</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <select>
                            <option value="" disabled selected>Select</option>
                            <option value="branch1">Branch 1</option>
                            <option value="branch2">Branch 2</option>
                            <option value="branch3">Branch 3</option>
                            <option value="branch3">Branch 4</option>
                            <option value="branch3">Branch 5</option>
                        </select>
                    </td>
                    <td><input type="text" placeholder="Enter Item"></td>
                    <td><input type="text" placeholder="Enter Type"></td>
                    <td><input type="number" placeholder="0"></td>
                    <td><input type="text" placeholder="Write message..."></td>
                    <td><a href="#" class="btn refill">Submit</a></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

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
    color: white;
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

.sidebar a:hover {
    background-color: #0c0c0c;
}

.sidebar a.active {
    background-color: #000000;
}

.sidebar a.logout {
    background-color: #e74c3c;
    position: absolute;
    bottom: 20px;
    left: 15px;
    right: 15px;
    border-radius: 20px;
}

.sidebar a.logout:hover { 
    background-color: red;
}

.main {
    margin-left: 240px;
    padding: 30px 40px;
    box-sizing: border-box;
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
    font-size: 14px;
    border-radius: 20px;
    transition: 0.3s;
}

.header a:hover {
    background-color: #000000;
}

.table-container {
    margin-top: 30px;
    background-color: #ffffff;
    border-radius: 20px;
    padding: 20px;
    box-shadow: 0 3px 8px rgba(0,0,0,0.1);
}

table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 12px;
}

th, td {
    padding: 16px;
    text-align: center;
    background-color: #f1f0f0;
    border-radius: 12px;
}

th {
    background-color: #494949;
    color: white;
    font-size: 14px;
    padding: 15px;
}
    

.btn {
    display: inline-block;
    padding: 6px 14px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 13px;
    font-weight: bold;
    border: 1px solid;
    margin: 0 3px;
    transition: 0.3s;
}

.btn.edit {
    color: #27ae60;
    border-color: #27ae60;
    background: #fff;
}

.btn.edit:hover {
    background: #27ae60;
    color: #fff;
}

.btn.delete {
    color: #e74c3c;
    border-color: #e74c3c;
    background: #fff;
}

.btn.delete:hover {
    background: #e74c3c;
    color: #fff;
}

table input,
table select {
    width: 100%;
    padding: 10px;
    border: 1px solid #000000;
    border-radius: 8px;
    font-size: 14px;
    box-sizing: border-box;
    background-color: #fff;
}
</style>

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

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="<?= base_url('css/inventory_AD.css') ?>">

<title>Inventory</title>
</head>
<body>

<div class="sidebar">
    <h2>ADMIN</h2>
    <a href="Central_AD.html">DASHBOARD</a>
    <a href="inventory_AD.html" class="active">INVENTORY</a>
    <a href="#">SUPPLIERS</a>
    <a href="#">ORDERS</a>
    <a href="#">FRANCHISING</a>
    <a href="#">REPORTS</a>
    <a href="#">SETTINGS</a>
    <a href="Login.html" class="logout">Logout</a>
</div>

<div class="main">
    <div class="header">
    <span>Inventory Management</span>
    <a href="request_stock.html">+Request Stock Item</a>
</div>

    <div class="table-container">
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
                    <td>In Stock</td>
                    <td>
                        <a href="#" class="btn refill">Urgent Refill</a>
                    </td>
                </tr>
                <tr>
                    <td>Branch A</td>
                    <td>Chicken</td>
                    <td>Wing</td>
                    <td>20 kg</td>
                    <td>Low Stock</td>
                    <td>
                        <a href="#" class="btn refill">Urgent Refill</a>
                    </td>
                </tr>
                <tr>
                    <td>Branch B</td>
                    <td>Chicken</td>
                    <td>Breast</td>
                    <td>0 kg</td>
                    <td>Out of Stock</td>
                    <td>
                        <a href="#" class="btn refill">Urgent Refill</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>

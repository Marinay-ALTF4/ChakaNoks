<h2>🛒 Create Purchase Request</h2>

<?php if (session()->getFlashdata('success')): ?>
  <p class="success-msg"><?= session()->getFlashdata('success') ?></p>
<?php endif; ?>

<div class="layout">

  <div class="form-container">
    <form method="post">
      <label>Item Name</label>
      <input type="text" name="item_name" placeholder="Enter item name" required>

      <label>Quantity</label>
      <input type="number" name="quantity" placeholder="Enter quantity" required>

      <label>Type of Goods</label>
      <input type="text" name="goods" placeholder="Type of Goods" required>


      <div class="button-group">
        <button type="submit" class="submit-btn">Submit Request</button>
      </div>
    </form>
  </div>

  <div class="info-box">
    <h3>ℹ️ Instructions</h3>
    <ul>
      <li>Fill in the <strong>Item Name</strong> and <strong>Quantity</strong>.</li>
      <li>Double-check your request before submitting.</li>
      <li>Submitted requests will be reviewed by Admin.</li>
    </ul>
    <p><strong>Tip:</strong> Check the inventory before making a request to avoid duplicates.</p>
  </div>
</div>

<div class="back-container">
  <a href="<?= site_url('branch/dashboard') ?>" class="back-btn">⬅ Back</a>
</div>

<style>
body {
  font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Arial, sans-serif;
  background:#f4f6f8;
  color:#333;
  display: flex;
  flex-direction: column;
  align-items: center;
  min-height: 100vh;
  margin: 0;
  padding: 20px;
}

h2 {
  margin-bottom: 20px;
  font-size: 22px;
  font-weight: bold;
  color:#222;
  text-align: center;
}

.success-msg {
  color: #28a745;
  background: #e9f9ee;
  border: 1px solid #c3e6cb;
  padding: 10px 12px;
  border-radius: 6px;
  margin-bottom: 15px;
  text-align: center;
}

.layout {
  display: flex;
  gap: 20px;
  max-width: 900px;
  width: 100%;
  justify-content: center;
  margin-top: 10px;
  flex-wrap: wrap; 
}

.form-container {
  flex: 2;
  background: #fff;
  padding: 20px;
  border-radius: 12px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.1);
  min-width: 300px;
  border: 1px solid black;
}

form {
  display: flex;
  flex-direction: column;
  gap: 12px;
  width: 100%;
}

label {
  font-weight: 600;
  font-size: 14px;
}

input[type="text"],
input[type="number"] {
  padding: 10px;
  border: 1px solid black;
  border-radius: 6px;
  font-size: 14px;
  width: 100%;
  box-sizing: border-box;
}

input:focus {
  border-color: #007bff;
  outline: none;
}

.button-group {
  display: flex;
  justify-content: center;
  margin-top: 15px;
}

.submit-btn {
  padding: 15px 16px;
  background: #007bff;
  color: #fff;
  border: none;
  border-radius: 8px;
  font-weight: bold;
  font-size: 14px;
  cursor: pointer;
  transition: background 0.2s ease;
}

.submit-btn:hover {
  background: #0056b3;
}

.back-container {
  width: 100%;
  display: flex;
  justify-content: center;
  margin-top: 25px;
}

.back-btn {
  padding: 10px 50px;
  background: #6c757d;
  color: #fff;
  border-radius: 6px;
  text-decoration: none;
  transition: background 0.3s ease;
  font-weight: bold;
  border: 1px solid black;
}

.back-btn:hover {
  background: #5a6268;
}

.info-box {
  flex: 1;
  background: #f9f9f9;
  padding: 20px;
  border-radius: 12px;
  border: 1px solid #ddd;
  font-size: 14px;
  line-height: 1.5;
  min-width: 250px;
  
}

.info-box h3 {
  margin-top: 0;
  margin-bottom: 12px;
  font-size: 18px;
  color: #007bff;
}

.info-box ul {
  margin: 0 0 10px 18px;
  padding: 0;
}

@media (max-width: 768px) {
  .layout {
    flex-direction: column;
    align-items: center;
  }

  .form-container,
  .info-box {
    width: 100%;
  }
}
</style>

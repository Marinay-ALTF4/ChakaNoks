<?= $this->extend('Layout') ?>

<?= $this->section('content') ?>

<h2>🍗 Approve Transfers</h2>
<!-- D -->
<div class="tips-box">
  <h3>📘 Tips</h3>
  <ul>
    <li>Review transfer details carefully before approving.</li>
    <li>Only approve transfers with <strong>Pending</strong> status.</li>
    <li>Ensure the source branch has enough stock.</li>
    <li>Approved transfers cannot be undone.</li>
  </ul>
</div>
<!-- Hawaa lang ang mga hardcoded nga sample kung mag himog nag code nga running -->
<table class="transfers-table">
  <thead>
    <tr>
      <th>Item</th>
      <th>Quantity</th>
      <th>From Branch</th>
      <th>Status</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>Fresh Chicken Breast</td>
      <td>25 kg</td>
      <td>Manila Main Branch</td>
      <td><span class="status pending">Pending</span></td>
      <td><a class="approve-btn" href="#">Approve</a></td>
    </tr>
    <tr>
      <td>Frozen Chicken Wings</td>
      <td>40 kg</td>
      <td>Cebu Branch</td>
      <td><span class="status approved">Approved</span></td>
      <td>—</td>
    </tr>
    <tr>
      <td>Chicken Drumsticks</td>
      <td>30 kg</td>
      <td>Davao Branch</td>
      <td><span class="status pending">Pending</span></td>
      <td><a class="approve-btn" href="#">Approve</a></td>
    </tr>
  </tbody>
</table>

<style>
/* Title */
h2 {
  text-align: center;
  margin: 25px 0 20px;
  font-size: 24px;
  font-weight: bold;
  color: #222;
}

/* Tips box */
.tips-box {
  width: 70%;
  max-width: 700px;
  background: #fff;
  padding: 20px 25px;
  border: 1px solid #ddd;
  border-radius: 10px;
  margin: 0 auto 30px;
  font-size: 15px;
  line-height: 1.6;
  box-shadow: 0 2px 5px rgba(0,0,0,0.05);
  text-align: left;
}

.tips-box h3 {
  margin-top: 0;
  margin-bottom: 10px;
  color: #007bff;
}

.tips-box ul {
  margin: 0;
  padding-left: 20px;
}

/* Table */
.transfers-table {
  width: 90%;
  max-width: 950px;
  border-collapse: collapse;
  margin: 0 auto 25px;
  border: 1px solid #ccc;
  font-size: 15px;
  background: #fff;
  border-radius: 8px;
  overflow: hidden;
}

.transfers-table th, 
.transfers-table td {
  border: 1px solid #ddd;
  padding: 12px;
  text-align: center;
}

.transfers-table th {
  background: #0456ad;
  color: #fff;
  font-weight: bold;
  text-transform: uppercase;
  font-size: 13px;
}

.transfers-table tr:nth-child(even) {
  background: #f9f9f9;
}

/* Status badges */
.status {
  font-weight: bold;
  padding: 4px 10px;
  border-radius: 12px;
}

.status.pending {
  background: #fff3cd;
  color: #856404;
  border: 1px solid #ffeeba;
}

.status.approved {
  background: #d4edda;
  color: #155724;
  border: 1px solid #c3e6cb;
}

/* Approve button */
.approve-btn {
  padding: 6px 14px;
  background: #28a745;
  color: #fff;
  border-radius: 6px;
  text-decoration: none;
  font-weight: bold;
  border: 1px solid #1e7e34;
  transition: background 0.2s ease;
}

.approve-btn:hover {
  background: #218838;
}

/* Responsive */
@media (max-width: 768px) {
  .tips-box {
    width: 90%;
  }
  .transfers-table {
    width: 100%;
    font-size: 13px;
  }
}
</style>

<?= $this->endSection() ?>

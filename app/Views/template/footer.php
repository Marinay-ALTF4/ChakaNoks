<!-- Bootstrap JS (needed for tabs) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>



//Branch Manager
<style>
body {
  margin:0;
  font-family: Arial, sans-serif;
  background:#D9C484;
}
.sidebar {
  position:fixed;
  left:0;
  top:0;
  width:220px;
  height:100%;
  background:#333;
  color:#fff;
  padding:20px;
  box-sizing:border-box;
  display:flex;
  flex-direction:column;
}
.sidebar h2 {
  text-align:center;
  margin:0 0 18px 0;
  font-size:14px;
  letter-spacing:0.6px;
}
.sidebar a {
  display:block;
  width:100%;
  box-sizing:border-box;
  padding:10px 14px;
  margin:6px 0;
  color:#fff;
  text-decoration:none;
  background:#444;
  border-radius:8px;
  transition: transform .12s ease, background .12s ease;
  text-align:left;
  border: 1px solid black;
}
.sidebar a:hover {
  background:#222;
  transform: translateY(-2px);
  text-decoration:none;
}
.sidebar a.logout {
  background:#e74c3c;
  font-weight:600;
  padding:12px 14px;
  margin:12px 0 0 0;
  margin-top:auto;
  text-align:center;
}
.sidebar a.logout:hover {
  background:#c0392b;
  transform: translateY(-3px);
}
.main {
  margin-left:240px;
  padding:30px;
}
.cards {
  display:flex;
  gap:20px;
}
.card {
  flex:1;
  background:white;
  padding:20px;
  border-radius:12px; 
  box-shadow:0 2px 6px rgba(0,0,0,0.1);
  display:flex;
  flex-direction:column;
  justify-content:space-between;
  transition: transform 0.25s ease, box-shadow 0.25s ease;
  border: 1px solid black;
}
.card:hover {
  transform: translateY(-8px);
  box-shadow:0 6px 16px rgba(0,0,0,0.15);
}
.card h3 {
  margin-top:0;
}
.card p {
  flex:1;
}
.card a {
  display:block;
  padding:10px;
  text-align:center;
  background:#007bff;
  color:white;
  border-radius:6px;
  font-weight:bold;
  transition: background 0.2s ease;
  border: 1px solid black;
}
.card a:hover {
  background:#0056b3;
  text-decoration:none;
}
</style>
</body>
</html>


//Staff Dashboard
 <style>
     body {
      margin: 0;
      font-family: Arial, sans-serif;
      background-color: #1e1e1e;
      color: #f5f5f5;
      display: flex;
      height: 100vh;
    }

    .sidebar {
      width: 240px;
      background-color: #111;
      padding: 20px 10px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    .sidebar h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #fff;
      font-size: 22px;
    }

    .nav {
      display: flex;
      flex-direction: column;
      gap: 12px;
    }

    .nav a {
      display: block;
      background-color: #2a2a2a;
      padding: 15px;
      border-radius: 8px;
      color: #ccc;
      text-decoration: none;
      font-size: 15px;
      transition: all 0.3s ease;
      box-shadow: 0 2px 5px rgba(0,0,0,0.4);
    }

    .nav a:hover {
      background-color: #444;
      color: #fff;
      transform: translateY(-3px);
    }

    .logout {
      display: block;
      text-align: center;
      background-color: #2a2a2a;
      padding: 12px;
      margin-top: 20px;
      color: #f44336;
      font-weight: bold;
      border-radius: 8px;
      text-decoration: none;
      transition: all 0.3s ease;
      box-shadow: 0 2px 5px rgba(0,0,0,0.4);
    }

    .logout:hover {
      background-color: #444;
      transform: translateY(-3px);
    }

    .main {
      flex: 1;
      display: flex;
      flex-direction: column;
    }

    .header {
      background-color: #111;
      padding: 15px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 1px solid #333;
    }

    .header h1 {
      margin: 0;
      font-size: 20px;
    }

    .content {
      flex: 1;
      padding: 20px;
      overflow-y: auto;
    }

    .card {
      background-color: #2a2a2a;
      padding: 15px;
      border-radius: 10px;
      margin-bottom: 20px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.4);
    }

    .card h3 {
      margin-top: 0;
      margin-bottom: 10px;
      color: #fff;
    }

    .card p {
      color: #ccc;
    }

    .action-card {
      border-left: 5px solid #4caf50;
      flex: 1;
      margin: 8px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    .action-card .btn {
      display: inline-block;
      margin-top: 10px;
      padding: 10px 15px;
      background-color: #4caf50;
      color: white;
      border-radius: 6px;
      text-decoration: none;
      transition: background 0.3s;
      text-align: center;
    }

    .action-card .btn:hover {
      background-color: #45a049;
    }

    .action-row {
      display: flex;
      gap: 10px;
      margin-bottom: 10px;
    }

    .small-card {
      font-size: 14px;
      opacity: 0.8;
      padding: 15px;
    }

    .small-card h3 {
      font-size: 16px;
    }
  </style>
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


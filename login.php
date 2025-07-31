<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login</title>
<style>
body {
font-family: Arial, sans-serif;
background: #ecf0f1;
display: flex;
justify-content: center;
align-items: center;
height: 100vh;
}
.form-container {
background: white;
padding: 30px;
border-radius: 8px;
box-shadow: 0 0 10px rgba(0,0,0,0.1);
width: 350px;
}
.form-container h2 {
text-align: center;
margin-bottom: 20px;
}
.form-container input, .form-container select {
width: 100%;
padding: 10px;
margin-bottom: 15px;
border: 1px solid #ccc;
border-radius: 4px;
}
.form-container button {
width: 100%;
padding: 10px;
background: #27ae60;
border: none;
color: white;
font-weight: bold;
border-radius: 4px;
}
.form-container button:hover {
background: #2ecc71;
}
</style>
</head>
<body>

<div class="form-container">
<h2>Login</h2>
<form action="process_login.php" method="post">
<input type="email" name="email" placeholder="Email Address" required>
<input type="password" name="password" placeholder="Password" required>
<select name="role" required>
  <option value="">-- Select Role --</option>
  <option value="student">Student</option>
  <option value="teacher">Teacher</option>
  <option value="admin">Admin</option>
</select>

</select>
<button type="submit">Login</button>
</form>
</div>

</body>
</html>
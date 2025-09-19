<?php
include "service/database.php";
session_start();


if (isset($_POST['login'])) {
    
    $username = $_POST['username']; 
    $password = $_POST['password'];
    

    $sql = "SELECT * FROM user WHERE username = '$username'" ;
    $login = mysqli_query($db, $sql);
  
    if ($login && mysqli_num_rows($login) > 0) {
        $data = mysqli_fetch_assoc($login);
        
        if (password_verify($password, $data['password'])) {
            $_SESSION["username"] = $data["username"];
            $_SESSION["is_login"] = true;
            $_SESSION["email"] = $data["email"];

            if ($data ['role'] == "admin") {
                $_SESSION['admin'] = $username;
                header("location: admin_dashboard/dashboard.php");
            } elseif ($data['role'] == "pelanggan") {
                $_SESSION['user'] = $data ['username'];
                $_SESSION['nama'] = $data ['nama'];
                $_SESSION['id_user'] = $data ['id'];
                header("location: index.php");
            }

            exit;
        } else {
            echo "<script>alert('Password salah!');</script>";
        }
    } else {
        echo "<script>alert('Akun tidak ditemukan!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login Anindia</title>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to bottom right, #ffeef7ff, #ffe6f2);
    }

    .header {
      background-color: #60a4edff;
      color: white;
      text-align: center;
      padding: 20px 0;
      font-size: 28px;
      font-weight: bold;
      letter-spacing: 1px;
    }

    .login-container {
      background-color: white;
      max-width: 400px;
      margin: 50px auto;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 0 15px rgba(255, 105, 180, 0.3);
      text-align: center;
    }

    .login-container h2 {
      margin-bottom: 20px;
      color: #3d98f9;
    }

    .login-container input {
      width: 94%;
      padding: 10px;
      margin: 10px 0;
      border: 2px solid #3d98f9;
      border-radius: 8px;
      font-size: 16px;
    }

    .login-container button {
      width: 100%;
      padding: 12px;
      background-color: #3d98f9;
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .login-container button:hover {
      background-color: #3d98f9;
    }
    .back-btn {
    position: fixed;
    bottom: 20px;
    left: 20px;
    background-color: #3d98f9;
    color: #721c24;
    padding: 10px 16px;
    border-radius: 8px;
    font-weight: bold;
    text-decoration: none;
    box-shadow: 0 0 10px rgba(0,0,0,0.2);
    transition: background-color 0.3s ease;
}

.back-btn:hover {
    background-color: #f1b0b7;
}


    .footer {
      margin-top: 40px;
      text-align: center;
      color: #999;
      font-size: 14px;
    }
  </style>
</head>
<body>

  <!-- Header terpisah -->
  <div class="header">
    Selamat Datang di Anindia Kitchen
  </div>

  <!-- Kontainer Login -->
  <div class="login-container">
    <h2>Login Akun Anda</h2>
    <form action="login.php" method="POST">
     
      <input type="text" name="username" placeholder="Masukkan username" required>
      <input type="password" name="password" placeholder="Masukkan password" required>
      <button type="submit" name="login">Masuk Sekarang</button>
    </form>
    belum punya akun?<a href="register.php"> register sekarang</a>
  </div>


  <!-- Footer opsional -->

  <div class="footer">
    &copy; 2025 Anindia Kitchen. All rights reserved.
  </div>

</body>
</html>

<?php
$db = mysqli_connect ("localhost", "root", "", "irsyad");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password']; 
    $hp = $_POST['hp'];
    $alamat = $_POST['alamat'];
    $role = $_POST['user']; 
    
    

    // 🔑 Hash password sebelum disimpan
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Cek apakah username sudah terdaftar
    $cek = $db->query("SELECT * FROM user WHERE username='$username'");
    if ($cek->num_rows > 0) {
        echo "<script>alert('Username sudah terdaftar!');</script>";
    } else {
        $simpan = $db->query("INSERT INTO user (nama, email, username, password, hp, alamat, role) 
                              VALUES ('$nama', '$email', '$username', '$hashedPassword', '$hp', '$alamat', '$role')");
        if ($simpan) {
            echo "<script>alert('Registrasi berhasil! Silakan login.'); window.location='login.php';</script>";
        } else {
            echo "<script>alert('Registrasi gagal!');</script>";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Registrasi</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #3d98f9, #75bdfc);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            width: 350px;
            animation: fadeIn 0.8s ease-in-out;
        }
        @keyframes fadeIn {
            from {opacity: 0; transform: translateY(-20px);}
            to {opacity: 1; transform: translateY(0);}
        }
        h2 {
            text-align: center;
            color: #3d98f9;
            margin-bottom: 20px;
        }
        input, select {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 10px;
            outline: none;
            transition: 0.3s;
            font-size: 14px;
        }
        input:focus, select:focus {
            border-color: #3d98f9;
            box-shadow: 0 0 8px rgba(61, 152, 249, 0.4);
        }
        button {
            width: 100%;
            padding: 12px;
            background: #3d98f9;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: 0.3s;
        }
        button:hover {
            background: #2e7cd4;
            transform: scale(1.02);
        }
        .link {
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
        }
        .link a {
            color: #3d98f9;
            text-decoration: none;
        }
        .link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Registrasi</h2>
    <form method="post" action="">
        <input type="text" name="nama" placeholder="Nama Lengkap" required />
        <input type="email" name="email" placeholder="Email" required />
        <input type="text" name="username" placeholder="Username" required />
        <input type="password" name="password" placeholder="Password" required />
        <input type="number" name="hp" placeholder="No HP" required />
        <input type="text" name="alamat" placeholder="Alamat" required />
        <button type="submit">Daftar</button>
    </form>
    <div class="link">
        Sudah punya akun? <a href="login.php">Login di sini</a>
    </div>
</div>

</body>
</html>

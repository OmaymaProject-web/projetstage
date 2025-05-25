<?php
session_start();
require 'db.php'; // ملف الاتصال بقاعدة البيانات PDO

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usernameOrEmail = trim($_POST['username_email']);
    $password = $_POST['password'];

    // جلب المستخدم حسب اسم المستخدم أو البريد الإلكتروني
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$usernameOrEmail, $usernameOrEmail]);
    $user = $stmt->fetch();

    if ($user) {
        if (password_verify($password, $user['password'])) {
            // نجاح الدخول
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // إعادة التوجيه بناءً على الدور
            if ($user['role'] === 'admin') {
                header('Location: pp.html');
            } else {
                header('Location: pp.html'); // التوجيه حسب طلبك
            }
            exit;
        } else {
            $message = "كلمة المرور غير صحيحة.";
        }
    } else {
        $message = "المستخدم غير موجود.";
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>تسجيل الدخول</title>
  <link href="https://fonts.googleapis.com/css2?family=Amiri&display=swap" rel="stylesheet">

  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet" />
  <style>
    * {
      margin: 0; padding: 0; box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }
    body, html { height: 100%; }
    body {
      background: url('WhatsApp Image 2025-05-17 à 20.22.49_c7703f4a.jpg') no-repeat center center/cover;
      display: flex; justify-content: center; align-items: center;
      direction: rtl;
    }
    .login-box {
      background-color: rgba(255,255,255,0.1);
      backdrop-filter: blur(10px);
      padding: 40px;
      border-radius: 20px;
      text-align: center;
      width: 320px;
      color: #fff;
      box-shadow: 0 8px 20px rgba(0,0,0,0.4);
    }
    .avatar {
      width: 80px; height: 80px; border-radius: 50%; margin-bottom: 15px;
      border: 2px solid #fff;
    }
    .login-box h2 { margin-bottom: 20px; }
    .input-box { margin-bottom: 15px; }
    .input-box input {
      width: 100%; padding: 12px; border: none;
      border-radius: 30px; outline: none;
      font-size: 14px; text-indent: 10px;
    }
    .login-btn {
      width: 100%; padding: 12px;
      background-color: #333; color: white;
      border: none; border-radius: 30px;
      cursor: pointer; font-weight: bold;
      margin-top: 10px;
    }
    .login-btn:hover { background-color: #444; }
    .links {
      margin-top: 15px; font-size: 14px;
    }
    .links a {
      display: block;
      color: rgb(30, 107, 124);
      text-decoration: none;
      margin-top: 5px;
      font-weight: bold;
    }
    .links a:hover { text-decoration: underline; }
    .message {
      margin-top: 15px;
      color: #ff6b6b;
      font-weight: bold;
    }
    .caption {
  margin-top: 2px;
  margin-bottom: 18px;
  font-weight: bold;
  font-size: 22px;
  color: black;
  font-family: 'Amiri', 'Scheherazade', 'Lateef', 'Traditional Arabic', 'Arial', sans-serif;
  text-align: center;
  direction: rtl;
}


  </style>
</head>
<body>
  <div class="login-box">
    <img src="chi.jpg" class="avatar" alt="User Image" />
    <div class="caption">المديرية الإقليمية للتعليم</div>

    <h2>مرحبًا بك مرة أخرى!</h2>
    <form method="POST" action="">
      <div class="input-box">
        <input type="text" name="username_email" placeholder="اسم المستخدم أو البريد الإلكتروني" required />
      </div>
      <div class="input-box">
        <input type="password" name="password" placeholder="كلمة المرور" required />
      </div>
      <button type="submit" class="login-btn">تسجيل الدخول</button>
      <div class="links">
        <a href="index.php">إنشاء حساب جديد</a>
      </div>
      <?php if (!empty($message)): ?>
        <div class="message"><?= htmlspecialchars($message) ?></div>
      <?php endif; ?>
    </form>
  </div>
</body>
</html>

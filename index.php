<?php
session_start();
require 'db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    if (strlen($username) < 3) {
        $message = "اسم المستخدم يجب أن يكون على الأقل 3 أحرف.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "البريد الإلكتروني غير صالح.";
    } elseif (strlen($password) < 6) {
        $message = "كلمة المرور يجب أن تكون على الأقل 6 أحرف.";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        if ($stmt->fetch()) {
            $message = "اسم المستخدم أو البريد الإلكتروني مستخدم مسبقًا.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            if ($stmt->execute([$username, $email, $hashed_password])) {
                $message = "تم إنشاء الحساب بنجاح! يمكنك الآن <a href='login.php'>تسجيل الدخول</a>.";
            } else {
                $message = "فشل في إنشاء الحساب، حاول مرة أخرى.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>تسجيل حساب جديد</title>
  <!-- خط عربي جميل -->
  <link href="https://fonts.googleapis.com/css2?family=Amiri&display=swap" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Amiri', serif;
    }

    body {
      background: url('WhatsApp Image 2025-05-17 à 20.22.49_c7703f4a.jpg') no-repeat center center/cover;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .login-box {
      background-color: rgba(255, 255, 255, 0.12);
      backdrop-filter: blur(10px);
      padding: 30px 25px;
      border-radius: 20px;
      text-align: center;
      width: 320px;
      color: #000;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4);
    }

    .avatar {
      width: 70px;
      height: 70px;
      border-radius: 50%;
      margin-bottom: 10px;
      border: 2px solid #fff;
    }

    .caption {
      margin-top: 5px;
      margin-bottom: 25px;
      font-weight: bold;
      font-size: 26px;
      color: #111;
      font-family: 'Amiri', 'Scheherazade', 'Lateef', 'Traditional Arabic', serif;
      padding-bottom: 4px;
    }

    h2 {
      margin-bottom: 20px;
      font-size: 22px;
    }

    .input-box {
      margin-bottom: 12px;
    }

    .input-box input {
      width: 100%;
      padding: 10px;
      border: none;
      border-radius: 20px;
      outline: none;
      font-size: 14px;
      text-align: right;
      background-color: #f9f9f9;
    }

    .login-btn {
      width: 100%;
      padding: 10px;
      background-color: #222;
      color: white;
      border: none;
      border-radius: 20px;
      cursor: pointer;
      font-weight: bold;
      margin-top: 10px;
      font-size: 15px;
    }

    .login-btn:hover {
      background-color: #333;
    }

    .links {
      margin-top: 15px;
      font-size: 14px;
    }

    .links a {
      color: rgb(0, 75, 95);
      text-decoration: none;
      font-weight: bold;
    }

    .links a:hover {
      text-decoration: underline;
    }

    .message {
      margin-top: 15px;
      color: #f5c518;
      font-weight: bold;
      font-size: 14px;
    }

  </style>
</head>
<body>
  <div class="login-box">
    <img src="chi.jpg" class="avatar" alt="User Image">
    <div class="caption">المديرية الإقليمية للتعليم</div>
    <h2>تسجيل حساب جديد</h2>
    <form method="POST">
      <div class="input-box">
        <input type="text" name="username" placeholder="اسم المستخدم" required />
      </div>
      <div class="input-box">
        <input type="email" name="email" placeholder="البريد الإلكتروني" required />
      </div>
      <div class="input-box">
        <input type="password" name="password" placeholder="كلمة المرور" required />
      </div>
      <button type="submit" class="login-btn">تسجيل</button>
      <div class="links">
        <a href="logine.php">هل لديك حساب؟ تسجيل الدخول</a>
      </div>
      <?php if (!empty($message)): ?>
        <div class="message"><?= htmlspecialchars($message) ?></div>
      <?php endif; ?>
    </form>
  </div>
</body>
</html>

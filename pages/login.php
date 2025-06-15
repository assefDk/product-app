<?php
session_start();

$error = '';
$lockoutTime = 3 * 60; // 30 دقيقة بالثواني

// تحقق هل المستخدم محظور مؤقتًا
if (isset($_SESSION['lockout']) && (time() - $_SESSION['lockout']) < $lockoutTime) {
    $remaining = $lockoutTime - (time() - $_SESSION['lockout']);
    $minutes = floor($remaining / 60);
    $seconds = $remaining % 60;
    $error = "لقد تجاوزت عدد المحاولات المسموحة. الرجاء الانتظار {$minutes} دقيقة و {$seconds} ثانية قبل المحاولة مجددًا.";
} else {
    // إذا انتهى وقت الحظر نعيد تعيين العداد
    if (isset($_SESSION['lockout'])) {
        unset($_SESSION['lockout']);
        unset($_SESSION['attempts']);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $password = $_POST['password'] ?? '';

        $correctPassword = '1234';

        // تهيئة عداد المحاولات لو غير موجود
        if (!isset($_SESSION['attempts'])) {
            $_SESSION['attempts'] = 0;
        }

        if ($password === $correctPassword) {
            // تسجيل الدخول ناجح، إعادة تعيين المحاولات
            unset($_SESSION['attempts']);
            unset($_SESSION['lockout']);
            $_SESSION['logged_in'] = true;
            header('Location: index.php');
            exit;
        } else {
            $_SESSION['attempts']++;

            if ($_SESSION['attempts'] >= 3) {
                // قفل الحساب لمدة 30 دقيقة
                $_SESSION['lockout'] = time();
                $error = "لقد تجاوزت عدد المحاولات المسموحة. الرجاء الانتظار 3 دقيقة قبل المحاولة مجددًا.";
            } else {
                $remainingAttempts = 3 - $_SESSION['attempts'];
                $error = "كلمة السر غير صحيحة. لديك {$remainingAttempts} محاولات متبقية.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>تسجيل الدخول</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet" />
</head>
<body class="bg-light d-flex align-items-center" style="height: 100vh;">

<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-4">
      <div class="card shadow-sm p-4">
        <h3 class="mb-4 text-center">تسجيل الدخول</h3>
        <?php if ($error): ?>
          <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <!-- اذا محظور، نعطل الفورم -->
        <form method="POST" novalidate <?= (isset($_SESSION['lockout']) ? 'style="pointer-events:none; opacity:0.6;"' : '') ?>>
          <div class="mb-3">
            <label for="password" class="form-label">كلمة السر</label>
            <input type="password" name="password" id="password" class="form-control" required autofocus <?= (isset($_SESSION['lockout']) ? 'disabled' : '') ?>>
          </div>
          <button type="submit" class="btn btn-primary w-100" <?= (isset($_SESSION['lockout']) ? 'disabled' : '') ?>>دخول</button>
        </form>
      </div>
    </div>
  </div>
</div>

</body>
</html>

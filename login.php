<?php
$SECRET_HASH = '59773ff0e873802620bbd976d1db44c48ae186494a4ffc3bf682714ec300c123';
$error = '';

function safe_redirect_path($path) {
    if (!$path || $path[0] !== '/' || substr($path, 0, 2) === '//') return '/index.html';
    return $path;
}

$redirect = safe_redirect_path($_GET['redirect'] ?? '/index.html');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pass = $_POST['password'] ?? '';
    if (hash('sha256', $pass) === $SECRET_HASH) {
        setcookie('slides_auth', $SECRET_HASH, [
            'expires' => time() + 60 * 60 * 24 * 30,
            'path' => '/',
            'secure' => true,
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
        $redirect = safe_redirect_path($_POST['redirect'] ?? $redirect);
        header('Location: ' . $redirect);
        exit;
    }
    $error = 'Senha incorreta.';
    $redirect = safe_redirect_path($_POST['redirect'] ?? $redirect);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Live Semanal · Acesso</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&display=swap" rel="stylesheet">
<style>
*{margin:0;padding:0;box-sizing:border-box;}
body{background:#0d0d0d;color:#fff;font-family:'Inter',sans-serif;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:24px;}
.card{width:100%;max-width:380px;border:1px solid rgba(255,255,255,.14);background:rgba(255,255,255,.03);border-radius:16px;padding:36px 32px;}
.lg{font-size:.7rem;font-weight:700;letter-spacing:.22em;text-transform:uppercase;color:rgba(255,255,255,.4);margin-bottom:10px;}
h1{font-size:1.4rem;font-weight:800;letter-spacing:-.02em;margin-bottom:24px;}
label{display:block;font-size:.74rem;font-weight:600;color:rgba(255,255,255,.55);margin-bottom:8px;}
input[type=password]{width:100%;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.16);border-radius:10px;color:#fff;padding:12px 14px;font-size:.95rem;font-family:inherit;margin-bottom:16px;}
input[type=password]:focus{outline:none;border-color:#f97316;}
button{width:100%;border:none;border-radius:10px;padding:13px;font-size:.9rem;font-weight:700;font-family:inherit;color:#fff;cursor:pointer;background:linear-gradient(120deg,#f97316,#e11d48 45%,#7c3aed);}
button:hover{opacity:.92;}
.err{background:rgba(225,29,72,.12);border:1px solid rgba(225,29,72,.35);color:#fca5b0;font-size:.82rem;padding:10px 12px;border-radius:8px;margin-bottom:16px;}
</style>
</head>
<body>
<form class="card" method="POST" action="/login.php">
  <div class="lg">Arrive In Digital · Live Semanal</div>
  <h1>Acesso restrito</h1>
  <?php if ($error): ?><div class="err">⚠️ <?php echo htmlspecialchars($error); ?></div><?php endif; ?>
  <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($redirect); ?>">
  <label for="password">Senha</label>
  <input type="password" id="password" name="password" autofocus required>
  <button type="submit">Entrar</button>
</form>
</body>
</html>

<?php
session_start();
include '../includes/db.php';

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: index.php");
    exit;
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hardcoded credentials
    $admin_user = "admin";
    $admin_pass = "password123";

    if ($username === $admin_user && $password === $admin_pass) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: index.php");
        exit;
    } else {
        $error = "Invalid credentials";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Access - Hay.Luxury</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500&family=Playfair+Display:ital,wght@0,600;1,600&display=swap');

        :root {
            --gold: #D4AF37;
            --black: #0a0a0a;
            --white: #ffffff;
        }

        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(135deg, #111 0%, #222 100%);
            color: var(--white);
            overflow: hidden;
        }
        
        /* Subtle animated background pattern */
        body::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background-image: 
                radial-gradient(circle at 20% 30%, rgba(212, 175, 55, 0.05) 0%, transparent 20%),
                radial-gradient(circle at 80% 70%, rgba(212, 175, 55, 0.05) 0%, transparent 20%);
            z-index: 0;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 60px 40px;
            width: 100%;
            max-width: 400px;
            text-align: center;
            border-radius: 4px; /* Slight rounding, keeps it sharp/luxury */
            box-shadow: 0 20px 50px rgba(0,0,0,0.3);
            position: relative;
            z-index: 1;
            transform: translateY(0);
            transition: transform 0.3s ease;
        }
        
        .login-card:hover {
            transform: translateY(-5px);
            border-color: rgba(212, 175, 55, 0.3);
        }

        .brand {
            font-family: 'Playfair Display', serif;
            font-size: 32px;
            letter-spacing: 4px;
            text-transform: uppercase;
            margin-bottom: 5px;
            color: var(--white);
        }

        .brand span {
            color: var(--gold);
            font-style: italic;
        }
        
        .subtitle {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 3px;
            color: rgba(255,255,255,0.5);
            margin-bottom: 40px;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-control {
            width: 100%;
            background: transparent;
            border: none;
            border-bottom: 1px solid rgba(255,255,255,0.2);
            padding: 15px 0;
            color: var(--white);
            font-family: 'Montserrat', sans-serif;
            font-size: 14px;
            outline: none;
            transition: border-color 0.3s;
            box-sizing: border-box; /* Fix width overflow */
        }

        .form-control:focus {
            border-bottom-color: var(--gold);
        }
        
        .form-control::placeholder {
            color: rgba(255,255,255,0.3);
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: 1px;
        }

        .btn {
            background: linear-gradient(45deg, var(--gold), #f3c858);
            color: var(--black);
            width: 100%;
            padding: 15px;
            border: none;
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 2px;
            cursor: pointer;
            margin-top: 20px;
            transition: all 0.3s;
            border-radius: 2px;
        }

        .btn:hover {
            transform: scale(1.02);
            box-shadow: 0 5px 20px rgba(212, 175, 55, 0.3);
        }

        .error-msg {
            color: #ff6b6b;
            font-size: 11px;
            margin-bottom: 20px;
            height: 15px; /* Prevent jump */
        }
        
        .footer-link {
            display: block;
            margin-top: 30px;
            font-size: 11px;
            color: rgba(255,255,255,0.3);
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .footer-link:hover {
            color: var(--gold);
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="brand">HAY.<span>LUXURY</span></div>
    <div class="subtitle">Administration</div>

    <div class="error-msg"><?php echo $error; ?></div>

    <form method="POST">
        <div class="form-group">
            <input type="text" name="username" class="form-control" placeholder="Identity" required autocomplete="off">
        </div>
        <div class="form-group">
            <input type="password" name="password" class="form-control" placeholder="Passkey" required>
        </div>
        <button type="submit" class="btn">Authenticate</button>
    </form>
    
    <a href="../index.php" class="footer-link">Return to Website</a>
</div>

</body>
</html>

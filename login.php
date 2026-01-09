<?php
session_start();
include "config/db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute([":email" => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if (password_verify($password, $user["password"])) {
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["user_name"] = $user["name"];
            header("Location: dashboard.php");
            exit;
        } else {
            $message = "❌ Incorrect password.";
        }
    } else {
        $message = "❌ No account found with this email.";
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>AI Task Manager — Login (3D)</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-1: #0f172a;
            --accent: #6ee7b7;
            --card-w: 420px;
        }

        * {
            box-sizing: border-box;
            font-family: Inter, system-ui, -apple-system, 'Segoe UI', Roboto, Arial
        }

        html,
        body {
            height: 100%;
            margin: 0
        }

        body {
            background: radial-gradient(1200px 600px at 10% 10%, rgba(64, 138, 255, 0.14), transparent 10%),
                radial-gradient(800px 500px at 90% 90%, rgba(110, 231, 183, 0.06), transparent 10%),
                var(--bg-1);
            color: #e6eef8;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            perspective: 1200px;
            overflow: hidden;
        }

        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(60px);
            opacity: 0.8;
            mix-blend-mode: screen;
            animation: blob 10s infinite alternate;
        }

        .blob.a {
            width: 520px;
            height: 520px;
            left: -10%;
            top: -15%;
            background: linear-gradient(135deg, #3b82f6, #a78bfa)
        }

        .blob.b {
            width: 420px;
            height: 420px;
            right: -8%;
            bottom: -20%;
            background: linear-gradient(135deg, #06b6d4, #22c55e);
            animation-duration: 12s
        }

        @keyframes blob {
            to {
                transform: translateY(40px) scale(1.05)
            }
        }

        .scene {
            width: 100%;
            max-width: 1200px;
            display: grid;
            grid-template-columns: 1fr 420px;
            gap: 48px;
            align-items: center;
            transform-style: preserve-3d
        }

        .hero {
            padding: 32px 24px
        }

        .eyebadge {
            display: inline-block;
            background: linear-gradient(90deg, #0ea5e9, #a78bfa);
            padding: 6px 12px;
            border-radius: 999px;
            font-weight: 700;
            color: #001122;
            font-size: 13px
        }

        h1 {
            font-size: 36px;
            line-height: 1.02;
            margin: 18px 0 8px
        }

        p.lead {
            color: rgba(230, 238, 248, 0.8);
            max-width: 680px
        }

        .card-wrap {
            width: var(--card-w);
            height: 420px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card {
            width: 100%;
            border-radius: 18px;
            padding: 22px;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.04), rgba(255, 255, 255, 0.02));
            backdrop-filter: blur(10px);
            box-shadow: 0 10px 30px rgba(2, 6, 23, 0.6);
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.04)
        }

        .logo {
            display: flex;
            gap: 12px;
            align-items: center
        }

        .logo .mark {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            background: linear-gradient(135deg, #06b6d4, #3b82f6);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            color: #001
        }

        .logo h3 {
            margin: 0;
            font-size: 16px
        }

        label {
            display: block;
            font-size: 13px;
            color: rgba(230, 238, 248, 0.9);
            margin-bottom: 8px
        }

        input[type=email],
        input[type=password] {
            width: 100%;
            padding: 12px 14px;
            border-radius: 10px;
            border: none;
            background: rgba(255, 255, 255, 0.03);
            color: inherit;
            outline: none;
            margin-bottom: 14px
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            border-radius: 10px;
            border: none;
            cursor: pointer;
            font-weight: 700;
            background: linear-gradient(90deg, #06b6d4, #3b82f6);
            color: #001;
            text-transform: uppercase;
            letter-spacing: 0.6px;
        }

        .btn:active {
            transform: translateY(2px)
        }

        .hint {
            font-size: 13px;
            color: rgba(230, 238, 248, 0.6);
            margin-top: 12px
        }

        .status {
            margin-top: 14px;
            padding: 10px;
            border-radius: 8px;
            font-weight: 600
        }

        .status.error {
            background: linear-gradient(90deg, rgba(239, 68, 68, 0.12), rgba(239, 68, 68, 0.06));
            color: #ffd7d7
        }

        @media(max-width:920px) {
            .scene {
                grid-template-columns: 1fr;
                gap: 28px
            }

            .card-wrap {
                justify-self: center
            }
        }
    </style>
</head>

<body>
    <div class="blob a" aria-hidden="true"></div>
    <div class="blob b" aria-hidden="true"></div>

    <div class="scene">
        <div class="hero">
            <span class="eyebadge">AI · Productivity</span>
            <h1>Welcome back! 👋<br><small style="font-weight:400;color:rgba(230,238,248,0.7);font-size:18px">Log in to continue managing your smart tasks with ease.</small></h1>
            <p class="lead">AI Task Manager helps you organize, prioritize, and complete your goals efficiently — powered by intelligent automation and a sleek design.</p>
        </div>

        <div class="card-wrap">
            <div class="card">
                <div class="logo">
                    <div class="mark">AI</div>
                    <div>
                        <h3>AI Task Manager</h3>
                        <div class="meta-line">Sign in to your account</div>
                    </div>
                </div>

                <form action="#" method="post">
                    <label>Email</label>
                    <input type="email" name="email" required>

                    <label>Password</label>
                    <input type="password" name="password" required>

                    <div style="display:flex;justify-content:space-between;align-items:center;margin-top:6px">
                        <div class="hint">Don’t have an account? <a href="register.php" style="color:var(--accent);text-decoration:none">Sign up</a></div>
                        <button type="submit" class="btn">Login</button>
                    </div>

                    <?php if ($message): ?>
                        <div class="status error"><?php echo $message; ?></div>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

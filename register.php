<?php
// register_3d.php
// This is the frontend file. Save it as register_3d.php in project root.
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>AI Task Manager — Register (3D)</title>
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
            height: 520px;
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

        .card::before,
        .card::after {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: inherit;
            pointer-events: none;
            mix-blend-mode: overlay;
        }

        .card::before {
            background: linear-gradient(120deg, rgba(34, 197, 94, 0.03), rgba(59, 130, 246, 0.03));
        }

        .card::after {
            background: linear-gradient(120deg, rgba(6, 182, 212, 0.02), rgba(167, 139, 250, 0.02));
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

        form {
            margin-top: 18px
        }

        label {
            display: block;
            font-size: 13px;
            color: rgba(230, 238, 248, 0.9);
            margin-bottom: 8px
        }

        input[type=text],
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

        .row {
            display: flex;
            gap: 10px
        }

        .small {
            flex: 1
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

        .preview {
            position: absolute;
            right: 18px;
            top: 18px;
            width: 120px;
            height: 120px;
            border-radius: 12px;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.02), rgba(255, 255, 255, 0.01));
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transform: translateZ(60px);
            border: 1px solid rgba(255, 255, 255, 0.03)
        }

        .meta-line {
            margin-top: 12px;
            font-size: 13px;
            color: rgba(230, 238, 248, 0.8)
        }

        .status {
            margin-top: 14px;
            padding: 10px;
            border-radius: 8px;
            font-weight: 600
        }

        .status.success {
            background: linear-gradient(90deg, rgba(34, 197, 94, 0.12), rgba(34, 197, 94, 0.06));
            color: #bff7d7
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

    <div class="scene" id="scene">
        <div class="hero" id="hero">
            <span class="eyebadge">AI · Productivity</span>
            <h1>Build focus. Finish tasks.<br><small style="font-weight:400;color:rgba(230,238,248,0.7);font-size:18px">With an intelligent assistant that helps write descriptions and plan your day.</small></h1>
            <p class="lead">Turn chaos into clarity. Our AI-powered task manager helps you stay focused, organized, and in control of your goals.</p>
            <div style="margin-top:18px;display:flex;gap:12px;align-items:center">
            </div>
        </div>

        <div class="card-wrap" id="cardWrap">
            <div class="card" id="card">
                <div class="logo">
                    <div class="mark">AI</div>
                    <div>
                        <h3>AI Task Manager</h3>
                        <div class="meta-line">Create account — free</div>
                    </div>
                </div>


                <form id="registerForm" action="#" method="post" autocomplete="on">
                    <label for="name">Full name</label>
                    <input id="name" type="text" name="name" placeholder="Your full name" required>

                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" placeholder="you@domain.com" required>

                    <div class="row">
                        <div class="small">
                            <label for="password">Password</label>
                            <input id="password" type="password" name="password" placeholder="Choose a secure password" required>
                        </div>
                    </div>

                    <div style="display:flex;justify-content:space-between;align-items:center;margin-top:6px">
                        <div class="hint">By signing up you agree to our <a href="#" style="color:var(--accent);text-decoration:none">Terms</a></div>
                        <button type="submit" class="btn">Sign up</button>
                    </div>

                    <div style="margin-top:12px;text-align:center;color:rgba(230,238,248,0.6)">
                        Already have an account? <a href="login.php" style="color:var(--accent);text-decoration:underline">Login</a>
                    </div>
                    <div id="exist"></div>
                </form>

            </div>
        </div>
    </div>
    <script>
        let form = document.querySelector("form")
        let name = document.getElementById("name")
        let email = document.getElementById("email")
        let password = document.getElementById("password")

        function setBorder(elem, isValid) {
            elem.style.border = isValid ? "2px solid #22c55e" : "2px solid #ef4444";
        }

        form.addEventListener("submit", function(e) {
            e.preventDefault();

            let valid = true

            let nameV = name.value.trim();
            let nameValid = /^[A-Za-zÀ-ÿ]+(\s+[A-Za-zÀ-ÿ]+)+$/.test(nameV);
            setBorder(name, nameValid);
            if (!nameValid) valid = false;



            let emailV = email.value.trim();
            let emailValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailV);
            setBorder(email, emailValid);
            if (!emailV) valid = false;


            let passwordV = password.value.trim();
            let passwordValid = passwordV.length >= 6;
            setBorder(password, passwordValid);
            if (!passwordValid) valid = false;

            if (valid) {
                form.submit();
            }
        })
    </script>

</body>

</html>


<?php
include "config/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {


    $name = htmlspecialchars($_POST["name"]);
    $email = htmlspecialchars($_POST["email"]);
    $password = htmlspecialchars($_POST["password"]);


    $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
    $stmt->execute([":email" => $email]);

    if ($stmt->fetchColumn() == 0) {

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);


        $insert = $conn->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
        $insert->execute([
            ":name" => $name,
            ":email" => $email,
            ":password" => $hashedPassword
        ]);
    } else {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                const status = document.createElement('div');
                status.className = 'status error';
                status.textContent = 'Cet email existe déjà.';
                document.getElementById('exist').appendChild(status);
            });
        </script>";
        exit;
    }
}
?>
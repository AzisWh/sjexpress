<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Master</title>

    <link rel="shortcut icon" type="image/png" href="{{ asset('public/adminview/assets/images/logos/favicon.png') }}">

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        :root {
            --primary: #1e4db7;
            --secondary: #3f6fd9;
            --white: #ffffff;
            --gray: #f5f5f5;
            --text: #555;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            min-height: 100vh;
            overflow: hidden;
            background: #f1f5f9;
        }

        .container-login {
            width: 100%;
            min-height: 100vh;
            display: flex;
        }

        /* LEFT SIDE */
        .left-side {
            width: 50%;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .left-side::before {
            content: "";
            position: absolute;
            width: 700px;
            height: 700px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
            top: -250px;
            left: -250px;
        }

        .left-content {
            position: relative;
            z-index: 2;
            max-width: 450px;
        }

        .left-content h1 {
            font-size: 48px;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .left-content p {
            font-size: 16px;
            line-height: 1.8;
            opacity: 0.9;
        }

        /* RIGHT SIDE */
        .right-side {
            width: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px;
            background: white;
        }

        .login-box {
            width: 100%;
            max-width: 420px;
        }

        .login-box h2 {
            font-size: 34px;
            margin-bottom: 10px;
            color: #222;
        }

        .login-box .subtitle {
            color: #777;
            margin-bottom: 35px;
        }

        .input-group {
            position: relative;
            margin-bottom: 20px;
        }

        .input-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #888;
            font-size: 20px;
        }

        .input-group input {
            width: 100%;
            padding: 14px 45px;
            border: 1px solid #ddd;
            border-radius: 12px;
            background: var(--gray);
            outline: none;
            font-size: 15px;
            transition: 0.3s;
        }

        .input-group input:focus {
            border-color: var(--primary);
            background: white;
        }

        #toggle-password {
            position: absolute;
            right: 55px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #777;
            font-size: 18px;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 25px;
            color: var(--text);
            font-size: 14px;
        }

        .btn-login {
            width: 100%;
            border: none;
            padding: 14px;
            border-radius: 12px;
            background: var(--primary);
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
        }


        .btn-login:hover {
            background: #163b8f;
        }

        @media (max-width: 768px) {
            .left-side {
                display: none;
            }

            .right-side {
                width: 100%;
            }

            body {
                background: white;
            }
        }
    </style>
</head>

<body>

    @include('sweetalert::alert')

    <div class="container-login">

        <!-- LEFT -->
        <div class="left-side">
            <div class="left-content">
                <h1>SAMA JAYA EXPRESS</h1>

                <p>
                    Welcome back! Please login to continue accessing the system.
                </p>
            </div>
        </div>

        <!-- RIGHT -->
        <div class="right-side">

            <div class="login-box">

                <h2>Sign In</h2>
                <p class="subtitle">
                    Login using your account
                </p>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- EMAIL -->
                    <div class="input-group">
                        <i class='bx bx-envelope'></i>

                        <input type="email" name="email" placeholder="Enter your email" required>
                    </div>

                    <!-- PASSWORD -->
                    <div class="input-group">
                        <i class='bx bx-lock-alt'></i>

                        <input type="password" name="password" id="password" placeholder="Enter your password"
                            required>

                        <span id="toggle-password">
                            <i class="fa-solid fa-eye"></i>
                        </span>
                    </div>

                    <!-- REMEMBER -->
                    <div class="remember">
                        <input type="checkbox" name="remember" id="remember">
                        <label for="remember">Remember Me</label>
                    </div>

                    <!-- BUTTON -->
                    <button type="submit" class="btn-login">
                        Sign In
                    </button>

                </form>

            </div>

        </div>

    </div>

    <script>
        document.getElementById('toggle-password').addEventListener('click', function() {

            let password = document.getElementById('password');
            let icon = this.querySelector('i');

            if (password.type === 'password') {
                password.type = 'text';

                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');

            } else {
                password.type = 'password';

                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }

        });
    </script>

</body>

</html>

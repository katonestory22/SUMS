<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/swahililogo.png') }} ">
    <title>SUMS</title>
    <style>
        /* Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body,
        html {
            height: 100%;
            width: 100%;
        }

        /* Hero Section */
        .hero {
            background: url('/images/scott.jpg') center/cover no-repeat;
            height: 100vh;
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
        }

        /* Overlay */
        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.55);
            z-index: 1;
        }

        /* Hero Content */
        .hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
            max-width: 800px;
            padding: 0 20px;
        }

        .hero h1 {
            font-size: 48px;
            margin-bottom: 20px;
            font-weight: 700;
            line-height: 1.2;
        }

        .hero p {
            font-size: 20px;
            margin-bottom: 40px;
            color: #f3f4f6;
        }

        /* Buttons */
        .btn {
            display: inline-block;
            padding: 14px 32px;
            margin: 0 10px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-login {
            background-color: #f59e0b;
            color: white;
        }

        .btn-login:hover {
            background-color: #374151;
        }



        .btn-register:hover {
            background-color: #d97706;
        }

        /* Responsive */
        @media(max-width:768px) {
            .hero h1 {
                font-size: 36px;
            }

            .hero p {
                font-size: 18px;
            }

            .btn {
                padding: 12px 24px;
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
    <div class="hero">
        <div class="overlay"></div>
        <div class="hero-content">
            <h1>Swahili Units Management System (SUMS)</h1>
            <p>Reliable construction solutions tailored for your vision.</p>

            <div>
                <a href="{{ route('login') }}" class="btn btn-login">Login</a>

            </div>
        </div>
    </div>
</body>

</html>

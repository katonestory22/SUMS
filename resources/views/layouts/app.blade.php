<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="{{ asset('images/swahililogo.png') }} ">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>@yield('title')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            font-family: 'Inter', sans-serif;

        }

        /* NAVBAR */
        .navbar {
            background-color: #111827;
            padding: 20px 60px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            margin-right: 20px;
            font-weight: 500;
        }

        .navbar a:hover {
            opacity: 0.7;
        }

        /* SECONDARY NAV */
        .sub-nav-wrapper {
            display: flex;
            justify-content: center;
            margin-top: 30px;
        }

        .sub-nav {
            background: white;
            padding: 15px 30px;
            border-radius: 50px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .sub-nav a {
            margin: 0 20px;
            text-decoration: none;
            color: #374151;
            font-weight: 500;
        }

        .sub-nav a:hover {
            color: #111827;
        }

        /* MAIN CONTENT AREA */
        .page-wrapper {
            padding: 50px 20px 80px;
            display: flex;
            justify-content: center;
        }

        /* BIG CARD */
        .main-card {
            background-color: #ffffff;
            width: 100%;
            max-width: 1100px;
            padding: 50px;
            border-radius: 14px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.06);
        }

        /* INNER CARDS */
        .card {
            background-color: #f9fafb;
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
        }

        /* FEEDBACK */
        .feedback {
            margin-bottom: 30px;
        }

        .success {
            padding: 15px;
            background-color: #ecfdf5;
            border-left: 4px solid #10b981;
            border-radius: 6px;
        }

        .error {
            padding: 15px;
            background-color: #fef2f2;
            border-left: 4px solid #ef4444;
            border-radius: 6px;
        }

        h1 {
            margin-top: 0;
            margin-bottom: 40px;
        }

        .brand-link {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            font-weight: 600;
            font-size: 18px;
            color: #000;
            /* adjust to your theme */
        }

        .brand-logo {
            height: 40px;
            width: auto;
            border-radius: 6px;
            /* optional - softer look */
        }

        .brand-link:hover {
            opacity: 0.85;
        }
    </style>
</head>

<body>

    <!-- TOP NAVBAR -->
    <div class="navbar">
        <div class="brand">
            <a href="{{ route('dashboard') }}" class="brand-link">
                <img src="{{ asset('images/swahililogo.png') }}" alt="Swahili Units Logo" class="brand-logo">
                <span>SUMS (Swahili Units Management System)</span>
            </a>
        </div>

        <div>
            <a href="{{ route('profile.edit') }}">Profile</a>

            <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                @csrf
                <button style="background:none;border:none;color:white;cursor:pointer;">
                    Logout
                </button>
            </form>
        </div>
    </div>

    <!-- ROLE NAVIGATION (Centered under navbar) -->
    <div class="sub-nav-wrapper">
        <div class="sub-nav">
            @yield('sub-nav')
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="page-wrapper">
        <div class="main-card">

            <h1>@yield('page-title')</h1>

            <div class="feedback">
                @if (session('success'))
                    <div class="success">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="error">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="error">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            @yield('content')

        </div>
    </div>

</body>

</html>

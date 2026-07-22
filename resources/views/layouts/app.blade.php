<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="{{ asset('images/swahililogo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>@yield('title')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background-color: #f4f6f9;
            padding-top: 130px;
            /* navbar + subnav height */
        }

        /* NAVBAR */
        .navbar {
            background-color: #111827;
            padding: 14px 60px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
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

        /* ===== BEAUTIFIED NAV LINKS ===== */
        .nav-links {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav-link-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 18px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            color: #e5e7eb;
            text-decoration: none;
            transition: all 0.2s ease;
            border: 1px solid transparent;
        }

        .nav-link-btn:hover {
            background-color: rgba(255, 255, 255, 0.08);
            color: #ffffff;
            opacity: 1;
        }

        .nav-link-btn svg {
            width: 16px;
            height: 16px;
            opacity: 0.7;
        }

        .nav-link-btn:hover svg {
            opacity: 1;
        }

        .nav-logout-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 18px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            color: #e5e7eb;
            background: none;
            border: 1px solid transparent;
            cursor: pointer;
            transition: all 0.2s ease;
            font-family: 'Inter', sans-serif;
        }

        .nav-logout-btn:hover {
            background-color: rgba(239, 68, 68, 0.15);
            color: #fca5a5;
            border-color: rgba(239, 68, 68, 0.3);
        }

        .nav-logout-btn svg {
            width: 16px;
            height: 16px;
            opacity: 0.7;
        }

        .nav-logout-btn:hover svg {
            opacity: 1;
        }

        .nav-divider {
            width: 1px;
            height: 24px;
            background-color: rgba(255, 255, 255, 0.12);
            margin: 0 4px;
        }

        /* ===== END BEAUTIFIED NAV LINKS ===== */

        /* SECONDARY NAV */
        .sub-nav-wrapper {
            display: flex;
            justify-content: center;
            position: fixed;
            top: 68px;
            /* sits just below navbar */
            left: 0;
            right: 0;
            z-index: 999;
            padding: 10px 20px;
            background: #f4f6f9;
        }

        .sub-nav {
            background: white;
            padding: 12px 30px;
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
            padding: 30px 20px 80px;
            display: flex;
            justify-content: center;
        }

        /* BIG CARD */
        .main-card {
            background-color: #ffffff;
            width: 100%;
            max-width: 1300px;
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
            color: white;
        }

        .brand-logo {
            height: 40px;
            width: auto;
            border-radius: 6px;
        }

        .brand-link:hover {
            opacity: 0.85;
        }

        .main-card.projects-wide {
            max-width: 1600px;
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

        <div class="nav-links">
            <a href="{{ route('profile.edit') }}" class="nav-link-btn">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                </svg>
                Profile
            </a>

            <span class="nav-divider"></span>

            <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                @csrf
                <button type="submit" class="nav-logout-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                    </svg>
                    Logout
                </button>
            </form>
        </div>
    </div>

    <!-- ROLE NAVIGATION -->
    <div class="sub-nav-wrapper">
        <div class="sub-nav">
            @yield('sub-nav')
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="page-wrapper">
        <div class="main-card @yield('card-class')">

            <h1>@yield('page-title')</h1>

            <div class="feedback">
                @if (session('success'))
                    <div class="success">{{ session('success') }}</div>
                @endif

                @if (session('error'))
                    <div class="error">{{ session('error') }}</div>
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

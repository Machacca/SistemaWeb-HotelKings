<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inka Kings - Dashboard</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        /* 1. VARIABLES Y BASE */
        :root { 
            --sidebar-bg: #121212; 
            --luxury-gold: #d4af37; 
            --text-muted: #888;
            --bg-main: #f4f7f6;
        }

        body { 
            font-family: 'Inter', sans-serif; 
            background-color: var(--bg-main); 
            margin: 0; 
        }

        /* 2. ESTRUCTURA PRINCIPAL */
        .main-wrapper { display: flex; min-height: 100vh; width: 100%; }

        .sidebar { 
            width: 260px; 
            background-color: var(--sidebar-bg); 
            flex-shrink: 0;
            z-index: 100;
        }

        .content-area { 
            flex-grow: 1; 
            display: flex; 
            flex-direction: column; 
            overflow-x: hidden; 
        }

        /* 3. MENÚ LATERAL (PREMIUM) */
        .logo-container {
            background: linear-gradient(135deg, #222, #111);
            padding: 25px;
            border-radius: 0 0 20px 20px;
            margin-bottom: 30px;
            border-bottom: 1px solid rgba(212, 175, 55, 0.1);
        }

        .side-menu { padding: 0 15px; }

        .menu-item {
            color: var(--text-muted) !important;
            padding: 14px 18px !important;
            border-radius: 12px;
            margin-bottom: 5px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            text-decoration: none !important;
            position: relative;
            font-size: 0.88rem;
            font-weight: 500;
        }

        .icon-box {
            width: 24px;
            margin-right: 15px;
            text-align: center;
            font-size: 1.1rem;
        }

        /* Estados del Menú */
        .menu-item:hover {
            color: #fff !important;
            background: rgba(255, 255, 255, 0.03);
        }

        .active-menu {
            color: var(--luxury-gold) !important;
            background: rgba(212, 175, 55, 0.08) !important;
        }

        /* Línea indicadora dorada */
        .active-menu::before {
            content: "";
            position: absolute;
            left: -15px;
            top: 20%;
            height: 60%;
            width: 4px;
            background: var(--luxury-gold);
            border-radius: 0 4px 4px 0;
            box-shadow: 2px 0 10px rgba(212, 175, 55, 0.4);
        }

        /* 4. COMPONENTES (TARJETAS Y BOTONES) */
        .card { 
            border: none !important; 
            border-radius: 15px !important; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.05); 
        }

        .btn-luxury { 
            background: linear-gradient(135deg, #8a6d1a 0%, var(--luxury-gold) 100%); 
            color: white; 
            border: none; 
            border-radius: 8px; 
            padding: 8px 20px;
            font-weight: 600;
        }
    </style>
</head>
<body>

    <div class="main-wrapper">
        <aside class="sidebar">
            @include('layouts.sidebar')
        </aside>

        <div class="content-area">
            @include('layouts.header')
            
            <main class="p-4">
                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
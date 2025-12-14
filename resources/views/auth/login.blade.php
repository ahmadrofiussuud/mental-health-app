<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - FlexSport</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;700;900&family=Sora:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #FF4500;
            --secondary: #FF8C00;
            --dark: #0f172a;
            --card-bg: rgba(255, 255, 255, 0.05);
            --text-main: #ffffff;
            --text-muted: #94a3b8;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Sora', sans-serif;
            background-color: var(--dark);
            background-image: 
                radial-gradient(at 0% 0%, rgba(255, 69, 0, 0.15) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(255, 140, 0, 0.15) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(255, 69, 0, 0.15) 0px, transparent 50%),
                radial-gradient(at 0% 100%, rgba(255, 140, 0, 0.15) 0px, transparent 50%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-main);
            overflow-x: hidden;
        }

        /* Ambient Background shapes */
        .shape {
            position: absolute;
            background: linear-gradient(45deg, var(--secondary), var(--primary));
            border-radius: 50%;
            filter: blur(80px);
            z-index: -1;
            opacity: 0.4;
            animation: float 20s infinite ease-in-out;
        }
        .shape-1 { top: -10%; left: -10%; width: 500px; height: 500px; }
        .shape-2 { bottom: -10%; right: -10%; width: 400px; height: 400px; animation-delay: -5s; }

        @keyframes float {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(30px, -30px); }
        }
        
        .login-container {
            width: 100%;
            max-width: 450px;
            padding: 2rem;
            position: relative;
            z-index: 10;
        }
        
        .login-card {
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            padding: 2.5rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .brand {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .brand h1 {
            font-family: 'Orbitron', sans-serif;
            font-size: 2.5rem;
            font-weight: 900;
            background: linear-gradient(to right, #fff, var(--primary));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            letter-spacing: -1px;
            margin-bottom: 0.5rem;
        }

        .brand p {
            color: var(--text-muted);
            font-size: 0.95rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        input {
            width: 100%;
            padding: 1rem 1.25rem;
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            font-family: 'Sora', sans-serif;
            font-size: 1rem;
            color: white;
            transition: all 0.3s ease;
        }

        input::placeholder {
            color: rgba(255, 255, 255, 0.3);
        }
        
        input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(255, 69, 0, 0.1);
            background: rgba(15, 23, 42, 0.8);
        }
        
        .btn {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, var(--secondary) 0%, var(--primary) 100%);
            border: none;
            border-radius: 12px;
            font-family: 'Sora', sans-serif;
            font-size: 1rem;
            font-weight: 700;
            color: var(--dark);
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-top: 1rem;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -10px var(--primary);
        }

        .footer-links {
            text-align: center;
            margin-top: 2rem;
            font-size: 0.9rem;
            color: var(--text-muted);
        }

        .footer-links a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
        }

        .footer-links a:hover {
            color: white;
            text-decoration: underline;
        }

        .error-msg {
            color: #ff4757;
            font-size: 0.85rem;
            margin-top: 0.5rem;
            display: block;
        }

        /* Session Status */
        .status-msg {
            background: rgba(255, 69, 0, 0.1);
            border: 1px solid var(--primary);
            color: var(--primary);
            padding: 0.75rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="shape shape-1"></div>
    <div class="shape shape-2"></div>

    <div class="login-container">
        <div class="login-card">
            <div class="brand">
                <h1>FLEXSPORT</h1>
                <p>Welcome back, Champion.</p>
            </div>

            <!-- Session Status -->
            @if(session('status'))
                <div class="status-msg">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="john@example.com">
                    @error('email')
                        <span class="error-msg">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required placeholder="••••••••">
                    @error('password')
                        <span class="error-msg">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group" style="display:flex; justify-content:space-between; align-items:center;">
                    <label class="flex items-center" style="margin:0; text-transform:none; cursor:pointer;">
                        <input type="checkbox" name="remember" style="width:auto; margin-right:0.5rem;">
                        <span style="font-size:0.85rem;">Remember me</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" style="color:var(--text-muted); font-size:0.85rem; text-decoration:none;">Forgot Password?</a>
                    @endif
                </div>

                <button type="submit" class="btn">
                    Sign In
                </button>

                <div class="footer-links">
                    Don't have an account? <a href="{{ route('register') }}">Create Account</a>
                    <br><br>
                    <a href="{{ route('home') }}" style="color: var(--text-muted); font-size: 0.8em;">Back to Store</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
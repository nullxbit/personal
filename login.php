<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tripenure - Login</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: #ffffff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .container {
            display: flex;
            width: 90%;
            max-width: 1200px;
            height: 600px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(0, 0, 0, 0.1);
        }

        .left-section {
            flex: 1;
            position: relative;
            background: linear-gradient(135deg, #f5f5f5 0%, #e8e8e8 50%, #d4d4d4 100%);
            border-radius: 20px 0 0 20px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .desert-scene {
            position: relative;
            width: 100%;
            height: 100%;
            background: url('asstes/side.png') center center;
            background-size: cover;
            background-repeat: no-repeat;
        }

        .desert-scene::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.2);
            z-index: 1;
        }


        .right-section {
            flex: 1;
            padding: 60px 40px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 0 20px 20px 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .logo {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 40px;
            text-align: center;
        }

        .logo-icon {
            width: 180px;
            height: 180px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo-icon img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }


        .login-title {
            color: #333333;
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .login-subtitle {
            color: #666666;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .input-container {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
            font-size: 18px;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .input-icon svg {
            width: 100%;
            height: 100%;
            fill: currentColor;
        }

        .form-input {
            width: 100%;
            padding: 15px 15px 15px 45px;
            background: rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            color: #333;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .form-input::placeholder {
            color: #999;
        }

        .form-input:focus {
            outline: none;
            border-color: #ffb800;
            background: rgba(255, 255, 255, 0.9);
            box-shadow: 0 0 10px rgba(255, 184, 0, 0.2);
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            font-size: 14px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            color: #666;
        }

        .remember-me input {
            margin-right: 8px;
            accent-color: #ffb800;
        }

        .forgot-password {
            color: #ffb800;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .forgot-password:hover {
            color: #ff8c00;
        }

        .login-button {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #ffb800 0%, #ff8c00 100%);
            border: none;
            border-radius: 10px;
            color: #000;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .login-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(255, 184, 0, 0.4);
        }

        .login-button:active {
            transform: translateY(0);
        }

        /* Remove all animation keyframes and particle styles */

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
                height: auto;
                margin: 20px;
            }
            
            .left-section {
                height: 300px;
                border-radius: 20px 20px 0 0;
            }
            
            .right-section {
                border-radius: 0 0 20px 20px;
                padding: 40px 30px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="left-section">
            <div class="desert-scene">
                <div class="sun"></div>
                <div class="pyramid-1"></div>
                <div class="pyramid-2"></div>
                <div class="palm-tree">
                    <div class="palm-trunk"></div>
                    <div class="palm-leaves">
                        <div class="palm-leaf"></div>
                        <div class="palm-leaf"></div>
                        <div class="palm-leaf"></div>
                        <div class="palm-leaf"></div>
                    </div>
                </div>
                <div class="caravan">
                    <div class="camel"></div>
                    <div class="camel"></div>
                    <div class="camel"></div>
                </div>
            </div>
        </div>
        
        <div class="right-section">
            <div class="logo">
                <div class="logo-icon">
                    <img src="asstes/image.png" alt="Logo">
                </div>
                <div class="login-title">LOGIN</div>
                <div class="login-subtitle">welcome to the website</div>
            </div>
            
            <form>
                <div class="form-group">
                    <div class="input-container">
                        <span class="input-icon">
                            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                            </svg>
                        </span>
                        <input type="text" class="form-input" placeholder="Username" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="input-container">
                        <span class="input-icon">
                            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/>
                            </svg>
                        </span>
                        <input type="password" class="form-input" placeholder="Password" required>
                    </div>
                </div>
                
                <div class="form-options">
                    <label class="remember-me">
                        <input type="checkbox">
                        Remember
                    </label>
                    <a href="#" class="forgot-password">Forget Password ?</a>
                </div>
                
                <button type="submit" class="login-button">LOGIN</button>
            </form>
        </div>
    </div>

</body>
</html>
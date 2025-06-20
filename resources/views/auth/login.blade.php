<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Login | Library Portal</title>
<!-- Include Cairo font from Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@700&display=swap" rel="stylesheet">

<meta name="viewport" content="width=device-width, initial-scale=1" />
<style>
    body {
        background: linear-gradient(rgba(153, 166, 194, 0.7), rgba(26, 58, 110, 0.7)),
                    url('https://i.pinimg.com/originals/eb/b5/0a/ebb50a0d74954cfaa0c3e27f928eb500.jpg') no-repeat center center;
        background-size: cover;
        background-attachment: fixed;
        min-height: 100vh;
        margin: 0;
        padding: 0;
        font-family: Arial, sans-serif;
    }

    .main-container {
        display: flex;
        width: 100%;
        max-width: 1200px;
        margin: 40px auto; /* Increased top margin */
        padding: 40px 20px;
        align-items: flex-start; /* Changed from center to flex-start */
        justify-content: space-between;
    }

    /* Logo and Title Section - Left Side */
    .hero-container {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 20px;
        color: white;
        font-family: 'Cairo', sans-serif;
        padding: 40px 20px;
        max-width: 450px;
        margin-top: 40px; /* Added margin to push down */
    }

    .hero-logo {
        width: 160px;
        filter: brightness(0) invert(1);
    }

    .title-row {
        display: flex;
        align-items: center;
        gap: 12px;
        position: relative;
        width: 100%;
    }

    .hero-title {
        font-size: 3.2rem;
        line-height: 1.2;
        font-weight: 700;
        padding-left: 10px;
        flex-grow: 1;
        position: relative;
        margin: 0;
    }

    /* Line behind the title */
    .hero-title::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        transform: translateY(-50%);
        width: 100%;
        height: 7px;
        background-color: rgba(232, 185, 35, 0.5);
        z-index: -1;
        border-radius: 3px;
    }

    .decorative-icon {
        width: 40px;
        height: 40px;
        fill: #e8b923;
        filter: drop-shadow(0 0 3px rgba(232, 185, 35, 0.7));
    }

    /* Login Section - Right Side */
    
    .login-section {
    flex: 1;
    max-width: 500px;
    margin-top: 10%;
    margin-right: 15%;
}
    

    /* Rest of your CSS remains exactly the same */
    .role-buttons {
        margin-bottom: 1.5rem;
        display: flex;
        justify-content: center;
        gap: 10px;
        background: rgba(72, 91, 122, 0.7);
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    }

    .role-buttons.hidden {
        display: none;
    }

    .role-buttons button {
        background: transparent;
        color: white;
        border: 2px solid white;
        padding: 0.8rem 1.5rem;
        border-radius: 6px;
        font-size: 1.1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        flex: 1;
    }

    .role-buttons button:hover {
        background: rgba(255, 255, 255, 0.2);
    }

    .back-btn {
        background: #e8b923;
        color: #1a3a6e;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-size: 0.9rem;
        cursor: pointer;
        margin-bottom: 1rem;
        font-weight: bold;
        display: none;
    }

    .role-section {
        display: none;
        background: white;
        border-radius: 10px;
        padding: 2rem;
        margin-top: 1rem;
        text-align: left;
        box-shadow: 0 4px 20px rgba(0,0,0,0.2);
    }

    .role-section.active {
        display: block;
    }

    .form-title {
        font-weight: bold;
        margin-bottom: 1.5rem;
        color: #1a3a6e;
        font-size: 1.5rem;
        text-align: center;
    }

    .btn-submit {
        width: 100%;
        background: #1a3a6e;
        color: white;
        padding: 1rem;
        border: none;
border-radius: 6px;
        font-size: 1.1rem;
        cursor: pointer;
        margin-top: 1.5rem;
        transition: background 0.3s ease;
    }

    .btn-submit:hover {
        background: #0f2452;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.6rem;
        font-weight: bold;
        color: #1a3a6e;
    }

    .form-group input {
        width: 100%;
        padding: 0.8rem;
        border: 1px solid #ccc;
        border-radius: 6px;
        box-sizing: border-box;
        font-size: 1rem;
    }

    @media (max-width: 992px) {
        .main-container {
            flex-direction: column;
            padding: 20px;
            margin-top: 20px;
        }
        
        .hero-container {
            text-align: center;
            align-items: center;
            padding: 20px 0;
            margin-bottom: 20px;
            max-width: 100%;
            margin-top: 20px;
        }
        
        .title-row {
            justify-content: center;
        }
        
        .hero-title {
            font-size: 2.8rem;
        }
        
        .login-section {
            width: 100%;
            margin-top: 40px;
        }
    }

    @media (max-width: 768px) {
        .hero-title {
            font-size: 2.5rem;
        }
        
        .role-buttons {
            flex-direction: column;
        }
        
        .role-buttons button {
            width: 100%;
        }
    }

    @media (max-width: 576px) {
        .hero-title {
            font-size: 2rem;
        }
    }
</style><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Login | Library Portal</title>
<!-- Include Cairo font from Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@700&display=swap" rel="stylesheet">

<meta name="viewport" content="width=device-width, initial-scale=1" />
<style>
    body {
        background: linear-gradient(rgba(153, 166, 194, 0.7), rgba(26, 58, 110, 0.7)),
                    url('https://i.pinimg.com/originals/eb/b5/0a/ebb50a0d74954cfaa0c3e27f928eb500.jpg') no-repeat center center;
        background-size: cover;
        background-attachment: fixed;
        min-height: 100vh;
        margin: 0;
        padding: 0;
        font-family: Arial, sans-serif;
    }

    .main-container {
        display: flex;
        width: 100%;
        max-width: 1200px;
        margin: 40px auto; /* Increased top margin */
        padding: 40px 20px;
        align-items: flex-start; /* Changed from center to flex-start */
        justify-content: space-between;
    }

    /* Logo and Title Section - Left Side */
    .hero-container {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 20px;
        color: white;
        font-family: 'Cairo', sans-serif;
        padding: 40px 20px;
        max-width: 450px;
        margin-top: 40px; /* Added margin to push down */
    }

    .hero-logo {
        width: 160px;
        filter: brightness(0) invert(1);
    }

    .title-row {
        display: flex;
        align-items: center;
        gap: 12px;
        position: relative;
        width: 100%;
    }

    .hero-title {
        font-size: 3.2rem;
        line-height: 1.2;
        font-weight: 700;
        padding-left: 10px;
        flex-grow: 1;
        position: relative;
        margin: 0;
    }

    /* Line behind the title */
    .hero-title::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        transform: translateY(-50%);
        width: 100%;
        height: 7px;
        background-color: rgba(232, 185, 35, 0.5);
        z-index: -1;
        border-radius: 3px;
    }

    .decorative-icon {
        width: 40px;
        height: 40px;
        fill: #e8b923;
        filter: drop-shadow(0 0 3px rgba(232, 185, 35, 0.7));
    }

    /* Login Section - Right Side */
    
    .login-section {
    flex: 1;
    max-width: 500px;
    margin-top: 10%;
    margin-right: 15%;
}
    

    /* Rest of your CSS remains exactly the same */
    .role-buttons {
        margin-bottom: 1.5rem;
        display: flex;
        justify-content: center;
        gap: 10px;
        background: rgba(72, 91, 122, 0.7);
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    }

    .role-buttons.hidden {
        display: none;
    }

    .role-buttons button {
        background: transparent;
        color: white;
        border: 2px solid white;
        padding: 0.8rem 1.5rem;
        border-radius: 6px;
        font-size: 1.1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        flex: 1;
    }

    .role-buttons button:hover {
        background: rgba(255, 255, 255, 0.2);
    }

    .back-btn {
        background: #e8b923;
        color: #1a3a6e;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-size: 0.9rem;
        cursor: pointer;
        margin-bottom: 1rem;
        font-weight: bold;
        display: none;
    }

    .role-section {
        display: none;
        background: white;
        border-radius: 10px;
        padding: 2rem;
        margin-top: 1rem;
        text-align: left;
        box-shadow: 0 4px 20px rgba(0,0,0,0.2);
    }

    .role-section.active {
        display: block;
    }

    .form-title {
        font-weight: bold;
        margin-bottom: 1.5rem;
        color: #1a3a6e;
        font-size: 1.5rem;
        text-align: center;
    }

    .btn-submit {
        width: 100%;
        background: #1a3a6e;
        color: white;
        padding: 1rem;
        border: none;
border-radius: 6px;
        font-size: 1.1rem;
        cursor: pointer;
        margin-top: 1.5rem;
        transition: background 0.3s ease;
    }

    .btn-submit:hover {
        background: #0f2452;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.6rem;
        font-weight: bold;
        color: #1a3a6e;
    }

    .form-group input {
        width: 100%;
        padding: 0.8rem;
        border: 1px solid #ccc;
        border-radius: 6px;
        box-sizing: border-box;
        font-size: 1rem;
    }

    @media (max-width: 992px) {
        .main-container {
            flex-direction: column;
            padding: 20px;
            margin-top: 20px;
        }
        
        .hero-container {
            text-align: center;
            align-items: center;
            padding: 20px 0;
            margin-bottom: 20px;
            max-width: 100%;
            margin-top: 20px;
        }
        
        .title-row {
            justify-content: center;
        }
        
        .hero-title {
            font-size: 2.8rem;
        }
        
        .login-section {
            width: 100%;
            margin-top: 40px;
        }
    }

    @media (max-width: 768px) {
        .hero-title {
            font-size: 2.5rem;
        }
        
        .role-buttons {
            flex-direction: column;
        }
        
        .role-buttons button {
            width: 100%;
        }
    }

    @media (max-width: 576px) {
        .hero-title {
            font-size: 2rem;
        }
    }
</style>
</head>
<body>
    <div class="main-container">
        <div class="hero-container">
            <img 
                src="https://upload.wikimedia.org/wikipedia/commons/8/89/National_Library_of_Algeria%2C_Logo.png" 
                alt="Logo BNA" 
                class="hero-logo" 
            />
            <div class="title-row">
                <h1 class="hero-title">المكتبة الوطنية الجزائرية</h1>
                <!-- Decorative star icon -->
                <svg class="decorative-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                    <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                </svg>
            </div>
        </div>

        <div class="login-section">
            <button class="back-btn" id="backButton">← Retour</button>
            
            <div id="all-roles-buttons" class="role-buttons">
                <button class="role-btn" data-role="admin">Admin</button>
                <button class="role-btn" data-role="employer">Employer</button>
                <button class="role-btn" data-role="lecteur">Lecteur</button>
            </div>

            <!-- Admin Form -->
            <form method="POST" action="{{ route('login.admin') }}" id="admin-form" class="role-section">
                @csrf
                <div class="form-title">Admin Login</div>

                <div class="form-group">
                    <label for="admin_full_name">Nom et Prénom</label>
                    <input type="text" name="full_name" id="admin_full_name" required />
                </div>

                <div class="form-group">
                    <label for="admin_password">Mot de passe</label>
                    <input type="password" name="admin_password" id="admin_password" required />
                </div>

                <button type="submit" class="btn-submit">Login as Admin</button>
            </form>

            <!-- Employer Form -->
            <form method="POST" action="{{ route('login.employer') }}" id="employer-form" class="role-section">
                @csrf
                <div class="form-title">Employer Login</div>

                <div class="form-group">
                    <label for="employer_full_name">Nom et Prénom</label>
                    <input type="text" name="full_name" id="employer_full_name" required value="{{ old('full_name') }}" />
                </div>

                <div class="form-group">
                    <label for="employer_password">Mot de passe</label>
                    <input type="password" name="password" id="employer_password" required />
                </div>

                <button type="submit" class="btn-submit">Login as Employer</button>
            </form>

            <!-- Lecteur Form -->
            <form method="POST" action="{{ route('login.lecteur') }}" id="lecteur-form" class="role-section">
                @csrf
                <div class="form-title">Lecteur Login</div>

                <div class="form-group">
                    <label for="num_inscription">Numéro d'inscription</label>
                    <input type="text" name="num_inscription" id="num_inscription" required />
                </div>

                <div class="form-group">
                    <label for="lecteur_full_name">Nom et Prénom</label>
                    <input type="text" name="full_name" id="lecteur_full_name" required />
                </div>

                <button type="submit" class="btn-submit">Login as Lecteur</button>
            </form>
        </div>
    </div>

   <script>
    const roleButtons = document.querySelectorAll('.role-btn');
    const sections = document.querySelectorAll('.role-section');
    const backButton = document.getElementById('backButton');
    const roleButtonsContainer = document.getElementById('all-roles-buttons');

    roleButtons.forEach(button => {
        button.addEventListener('click', () => {
            // Show the selected form
            const role = button.dataset.role;
            const activeForm = document.getElementById(`${role}-form`);
            if (activeForm) activeForm.classList.add('active');
            
            // Hide role selection buttons
            roleButtonsContainer.classList.add('hidden');
            
            // Show back button
            backButton.style.display = 'block';
        });
    });

    // Back button functionality
    backButton.addEventListener('click', () => {
        // Show role buttons
        roleButtonsContainer.classList.remove('hidden');
        
        // Hide back button
        backButton.style.display = 'none';
        
        // Hide all forms
        sections.forEach(section => section.classList.remove('active'));
    });
</script>
</body>
</html>

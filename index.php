<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Clinic Management System</title>

    <link href="./assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="./assets/css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        body {
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            max-width: 400px;
            width: 100%;
        }

        .card {
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        .form-group {
            text-align: left;
        }

        .form-control-user {
            border-radius: 10px;
            padding: 14px;
            font-size: 1rem;
        }

        .btn-user {
            font-size: 1.2rem;
            padding: 12px;
            border-radius: 10px;
            background: #000000;
            color: #ffffff;
            border: none;
            transition: background 0.3s;
        }

        .btn-user:hover {
            background: #333333;
        }

        .alert {
            display: none;
        }

        .spinner-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .logo {
            width: 120px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="spinner-overlay">
        <div class="spinner-border text-light" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>

    <div class="login-container">
        <div class="card">
            <div class="text-center">
                <img src="./assets/img/logo.png" alt="Clinic Logo" class="logo">
                <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
            </div>
            <div id="alertMessage" class="alert alert-danger"></div>
            <form id="loginForm">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" class="form-control form-control-user" id="email" name="email" placeholder="Enter Email..." required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control form-control-user" id="password" name="password" placeholder="Password" required>
                </div>
                <button type="submit" class="btn btn-user btn-block">Login</button>
            </form>
        </div>
    </div>

    <script>
        document.getElementById("loginForm").addEventListener("submit", async function(event) {
            event.preventDefault();

            let email = document.getElementById("email").value;
            let password = document.getElementById("password").value;
            let alertMessage = document.getElementById("alertMessage");
            let loginButton = document.querySelector(".btn-user");
            let spinnerOverlay = document.querySelector(".spinner-overlay");

            spinnerOverlay.style.display = "flex";
            loginButton.disabled = true;

            try {
                let response = await fetch("controllers/LoginController.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded",
                    },
                    body: new URLSearchParams({
                        email: email,
                        password: password
                    })
                });

                let result = await response.json();
                
                spinnerOverlay.style.display = "none";
                loginButton.disabled = false;

                if (result.status === "success") {
                    window.location.href = result.redirect;
                } else {
                    alertMessage.style.display = "block";
                    alertMessage.innerHTML = result.message;
                }
            } catch (error) {
                spinnerOverlay.style.display = "none";
                loginButton.disabled = false;
                alertMessage.style.display = "block";
                alertMessage.innerHTML = "An error occurred. Please try again.";
            }
        });
    </script>
</body>
</html>

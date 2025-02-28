<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Clinic Management System</title>

    <link href="./assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="./assets/css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        .card {
            border-radius: 15px;
        }
        .btn-user {
            font-size: 1.1rem;
            padding: 12px;
        }
        .form-control-user {
            border-radius: 10px;
        }
        .login-container {
            max-width: 450px;
            margin: auto;
        }
        .alert {
            display: none;
        }
        /* Spinner overlay */
        .spinner-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
    </style>
</head>
<body class="bg-gradient-dark">
    <div class="spinner-overlay">
        <div class="spinner-border text-light" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 login-container">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-4">
                        <div class="text-center">
                            <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                        </div>
                        <div id="alertMessage" class="alert alert-danger"></div>
                        <form id="loginForm">
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="email" class="form-control form-control-user" id="email" name="email" placeholder="Enter Email Address..." required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control form-control-user" id="password" name="password" placeholder="Password" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-user btn-block">Login</button>
                        </form>
                    </div>
                </div>
            </div>
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

            // Show loading spinner
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
                
                // Hide spinner after response is received
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

<?php include('partials/foot.php'); ?>
</body>
</html>

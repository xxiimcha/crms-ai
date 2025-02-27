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
        .loading-spinner {
            display: none;
            text-align: center;
            margin-top: 15px;
        }
        .alert {
            display: none;
        }
    </style>
</head>
<body class="bg-gradient-dark">
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
                            <div class="loading-spinner">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById("loginForm").addEventListener("submit", function(event) {
            event.preventDefault();

            let email = document.getElementById("email").value;
            let password = document.getElementById("password").value;
            let alertMessage = document.getElementById("alertMessage");

            // Show loading spinner
            document.querySelector(".loading-spinner").style.display = "block";

            // Disable login button
            document.querySelector(".btn-user").disabled = true;

            // AJAX request
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "controllers/LoginController.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    document.querySelector(".loading-spinner").style.display = "none";
                    document.querySelector(".btn-user").disabled = false;

                    let response = JSON.parse(xhr.responseText);

                    if (response.status === "success") {
                        window.location.href = response.redirect;
                    } else {
                        alertMessage.style.display = "block";
                        alertMessage.innerHTML = response.message;
                    }
                }
            };

            xhr.send("email=" + encodeURIComponent(email) + "&password=" + encodeURIComponent(password));
        });
    </script>

<?php include('partials/foot.php');?>
</body>
</html>

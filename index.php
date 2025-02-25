
<?php include('partials/head.php');?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 login-container">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-4">
                        <div class="text-center">
                            <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                        </div>
                        <form class="user">
                            <div class="form-group">
                                <label for="exampleInputEmail">Email Address</label>
                                <input type="email" class="form-control form-control-user" id="exampleInputEmail" placeholder="Enter Email Address...">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword">Password</label>
                                <input type="password" class="form-control form-control-user" id="exampleInputPassword" placeholder="Password">
                            </div>
                            <div class="form-group d-flex justify-content-between align-items-center">
                                <div class="custom-control custom-checkbox small">
                                    <input type="checkbox" class="custom-control-input" id="customCheck">
                                    <label class="custom-control-label" for="customCheck">Remember Me</label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-user btn-block">Login</button>
                        </form
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php include('partials/foot.php');?>
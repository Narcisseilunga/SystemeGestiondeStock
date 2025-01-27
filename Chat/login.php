<?php
$page_title = "Login Chat";
include 'header.php';
?>


<div class="  login ">
    <div class="   container div-v  d-flex justify-content-center align-items-center">
        <div class="row col-md-7 ">
            <form class="row g-3  needs-validation isLogin" novalidate>


                <div class="col-12">
                    <label for="validationCustom03" class="form-label">Email</label>
                    <input type="text" class="form-control email-login" id="validationCustom03" required>
                    <div class="invalid-feedback">
                        Please provide You Email
                    </div>
                </div>

                <div class="col-12 position-relative">
                    <label for="validationCustom05" class="form-label  ">Password
                    </label>
                    <input type="text" class="form-control password-login" id="validationCustom05" required>
                    <div class="show-pass position-absolute">
                        <i class="fa-regular fa-eye"></i>
                    </div>
                    <div class="invalid-feedback">
                        Please Enter Password
                    </div>
                </div>


                <div class="col-12 msg-fetch d-none">
                    <div class="alert alert-info p-2 m-2">
                        msg
                    </div>
                </div>

                <div class="col-12">
                    <button class="btn btn-primary w-100" type="submit">Connect</button>
                </div>
                <div class="col-12 text-white">
                    <p>You D'ont Have Account
                        <a href="regester.php">Regester Here</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>





<script src="./jscript.js"></script>

<?php
include 'footer.php';
?>
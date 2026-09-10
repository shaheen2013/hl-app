<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<?php include LANG . $_SESSION['userLang'].'/register.php' ?>
<div class="container mt2">
    <div class="col-lg-12">
        <div class="col-lg-6 col-lg-offset-3">
            <img src="<?php echo DIR_IMG; ?>login-logo.png" alt="logo" width="208" height="39" class="loginLogo">
            <div class="col-lg-12 mb white-module">
                <h1 class="text-center"><?php echo $registerLang['Create an account'] ?></h1>
                <p class="text-center mt2"><strong><?php echo $registerLang['Bienvenido a Hotelinking'] ?></strong><br/>
                    <?php echo $registerLang['Insert password to be linked to your mail account'] ?>
                </p>
                <div class="mt2">
                    <div class="col-lg-8 col-lg-offset-2">
                        <form method="POST" action="create-account/?token=<?php echo $token ?>&verif=1">
                            <div class="form-group">
                                <div class="form-group">
                                    <label><?php echo $registerLang['Insert password'] ?></label>
                                    <div class="input-group">
                                        <input type="password" name="userPassword1" class="form-control input-lg" placeholder="Insert password..." required/>
                                        <span class="input-group-addon">6-12 char</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label><?php echo $registerLang['Insert password again'] ?></label>
                                    <input type="password" name="userPassword2" class="form-control input-lg" placeholder="Insert password again..." required/>
                                </div>
                                <input class="btn btn-lg btn-primary mb2" type="submit" name="passwordConfirmButton" value="Create account"/>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

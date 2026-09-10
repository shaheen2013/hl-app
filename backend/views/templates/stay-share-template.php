<?php include_once LANG . $_SESSION['userLang'] . '/stay-share-callback.php'; ?>
<div class="stay-overlayer"></div>
<style type="text/css">
    body {
        background: url(<?php echo imageSize('large', $hotel_data['fotoBg']) ?>);
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }

    .facebook-login-content {
        display: flex;
        flex-wrap: wrap;
        height: 100vh;
        align-content: space-between;
    }
</style>

<div class="facebook-login-content text-center">
    <div class="col-xs-12"></div>
    <div class="col-xs-12 text-center">
        <?php if (array_get($_SESSION, 'facebook_user') && !array_get($_SESSION, 'facebook_user.email') && !in_array(array_get($_SESSION, 'facebook_user.gender'), $validGenders)) { ?>
        <i class="fa fa-meh-o fa-3x" aria-hidden="true"></i>
            <h3 style="color:white"><?php echo $stayShareCallbackLang['Upps'] ?></h3>
            <p style="color:white"><?php echo $stayShareCallbackLang['no data'] ?>
                <br><?php echo $stayShareCallbackLang['insert data'] ?></p>
            <div class="col-sm-6 col-sm-offset-3 text-left">
                <form id="re-email-form" method="post">
                    <?php if (!array_get($_SESSION, 'facebook_user.first_name')) { ?>
                        <div class="form-group">
                            <input type="text" class="form-control" name="firstName"
                                    placeholder="<?php echo $stayShareCallbackLang['First name'] ?>..." required/>
                        </div>                    
                    <?php } ?>
                    <?php if (!array_get($_SESSION, 'facebook_user.last_name')) { ?>
                        <div class="form-group">
                            <input type="text" class="form-control" name="lastName"
                                    placeholder="<?php echo $stayShareCallbackLang['Last name'] ?>..." required/>
                        </div>     
                    <?php } ?>
                    <div class="form-group">
                        <input type="email" class="form-control" name="re-email"
                                placeholder="<?php echo $stayShareCallbackLang['Email'] ?>..." required/>
                    </div>
                    <div class="form-group">
                    <select class="form-control" id="formGender" name="gender" required>
                            <option value="" disabled selected><?php echo $stayShareCallbackLang['gender'] ?>...</option>
                            <option value="male"><?php echo $stayShareCallbackLang['male'] ?></option>
                            <option value="female"><?php echo $stayShareCallbackLang['female'] ?></option>
                        </select>
                    </div>
                    <?php if ($isUnderConsentAge) { ?>
                        <div style="color:white;display:block" class="checkbox gdprConditions">
                            <label>
                                <input type="checkbox" class="checkbox_check "name="gdpr_year" required> 
                                <?php echo $stayShareCallbackLang['gdpr_year'] ?>
                            </label>
                        </div>
                    <?php } ?>
                    <button type="submit"
                            class="btn btn-primary"><?php echo $stayShareCallbackLang['Conectar al wifi'] ?></button>
                </form>
            </div>
        <?php } else if (!$permissions_fb) { ?>
            <i class="fa fa-meh-o fa-3x" aria-hidden="true"></i>
            <h3 style="color:white"><?php echo $stayShareCallbackLang['Upps'] ?></h3>
            <p style="color:white"><?php echo $stayShareCallbackLang['Para poder acceder al wifi necesitamos que aceptes todos los permisos'] ?></p>

            <button class="btn btn-facebook" aria-label="Log in with Facebook">
                <div class="flex-container">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 216 216" width="216" height="216" class="_5h0m" color="#ffffff"><path fill="#ffffff" d=" M204.1 0H11.9C5.3 0 0 5.3 0 11.9v192.2c0 6.6 5.3 11.9 11.9 11.9h103.5v-83.6H87.2V99.8h28.1v-24c0-27.9 17-43.1 41.9-43.1 11.9 0 22.2.9 25.2 1.3v29.2h-17.3c-13.5 0-16.2 6.4-16.2 15.9v20.8h32.3l-4.2 32.6h-28V216h55c6.6 0 11.9-5.3 11.9-11.9V11.9C216 5.3 210.7 0 204.1 0z"></path></svg>
                    <br>
                    <strong><?php echo $stayShareCallbackLang['Conectar con facebook'] ?></strong>
                </div>
            </button>
            <br>
            <br>
            <a href="<?php echo SECURE_BASE_PATH . 'stay-share/' . $_SESSION['guidHotel'] ?>">Go back</a>
        <?php } else if (!array_get($_SESSION, 'facebook_user.email') || !$validEmail) { ?>
            <i class="fa fa-meh-o fa-3x" aria-hidden="true"></i>
            <h3 style="color:white"><?php echo $stayShareCallbackLang['Upps'] ?></h3>
            <p style="color:white"><?php echo $stayShareCallbackLang['no data'] ?>
                <br><?php echo $stayShareCallbackLang['insert data'] ?></p>
            <div class="col-sm-6 col-sm-offset-3 text-left">
                <form id="re-email-form" method="post">
                    <?php if (!array_get($_SESSION, 'facebook_user.first_name')) { ?>
                        <div class="form-group">
                            <input type="text" class="form-control" name="firstName"
                                    placeholder="<?php echo $stayShareCallbackLang['First name'] ?>..." required/>
                        </div>                    
                    <?php } ?>
                    <?php if (!array_get($_SESSION, 'facebook_user.last_name')) { ?>
                        <div class="form-group">
                            <input type="text" class="form-control" name="lastName"
                                    placeholder="<?php echo $stayShareCallbackLang['Last name'] ?>..." required/>
                        </div>     
                    <?php } ?>
                    <div class="form-group">
                        <label><?php echo $stayShareCallbackLang['Email'] ?></label>
                        <input type="email" class="form-control" name="re-email"
                                placeholder="<?php echo $stayShareCallbackLang['Email'] ?>..." required/>
                    </div>
                    <?php if ($isUnderConsentAge) { ?>
                        <div style="color:white;display:block" class="checkbox gdprConditions">
                            <label>
                                <input type="checkbox" class="checkbox_check "name="gdpr_year" required> 
                                <?php echo $stayShareCallbackLang['gdpr_year'] ?>
                            </label>
                        </div>
                    <?php } ?>
                    <button type="submit"
                            class="btn btn-primary"><?php echo $stayShareCallbackLang['Conectar al wifi'] ?></button>
                </form>
            </div>
        <?php }
        else if (!in_array(array_get($_SESSION, 'facebook_user.gender'), $validGenders)){ ?>
                <h3 style="color:white"><?php echo $stayShareCallbackLang['Upps'] ?></h3>
                <p style="color:white"><?php echo $stayShareCallbackLang['no data'] ?>
                    <br><?php echo $stayShareCallbackLang['insert data'] ?></p>
            <div class="col-sm-6 col-sm-offset-3 text-left">
                <form id="gender-facebook" method="post" action="<?php echo SECURE_BASE_PATH . 'facebook-login-callback/'?>" method="post">
                    <?php if (!array_get($_SESSION, 'facebook_user.first_name')) { ?>
                        <div class="form-group">
                            <input 
                                type="text"
                                minlength='<?php echo $firstNameLength?>'
                                pattern='<?php echo $firstNamePattern?>'
                                class="form-control"
                                name="firstName"
                                placeholder="<?php echo $stayShareCallbackLang['First name'] ?>..."
                                required
                            />
                        </div>                    
                    <?php } ?>
                    <?php if (!array_get($_SESSION, 'facebook_user.last_name')) { ?>
                        <div class="form-group">
                            <input
                                type="text"
                                minlength='<?php echo $lastNameLength?>'
                                pattern='<?php echo $lastNamePattern?>'
                                class="form-control"
                                name="lastName"
                                placeholder="<?php echo $stayShareCallbackLang['Last name'] ?>..." required
                            />
                        </div>     
                    <?php } ?>
                    <div class="form-group">
                        <select class="form-control" id="formGender" name="gender" required>
                            <option value="" disabled selected><?php echo $stayShareCallbackLang['gender'] ?>...</option>
                            <option value="male"><?php echo $stayShareCallbackLang['male'] ?></option>
                            <option value="female"><?php echo $stayShareCallbackLang['female'] ?></option>
                        </select>
                    </div>
                    <?php if ($isUnderConsentAge) { ?>
                        <div style="color:white;display:block" class="checkbox gdprConditions">
                            <label>
                                <input type="checkbox" class="checkbox_check "name="gdpr_year" required> 
                                <?php echo $stayShareCallbackLang['gdpr_year'] ?>
                            </label>
                        </div>
                    <?php } ?>
                    <button type="submit" class="btn btn-primary"><?php echo $stayShareCallbackLang['Conectar al wifi'] ?></button>
                </form>
            </div>
        <?php } else if (!array_get($_SESSION, 'facebook_user.first_name') || !array_get($_SESSION, 'facebook_user.last_name')) { ?>
            <i class="fa fa-meh-o fa-3x" aria-hidden="true"></i>
            <h3 style="color:white"><?php echo $stayShareCallbackLang['Upps'] ?></h3>
            <p style="color:white"><?php echo $stayShareCallbackLang['no data'] ?>
                <br><?php echo $stayShareCallbackLang['insert data'] ?>
            </p>
            <div class="col-sm-6 col-sm-offset-3 text-left">
                <form id="re-email-form" method="post">
                    <?php if (!array_get($_SESSION, 'facebook_user.first_name')) { ?>
                        <div class="form-group">
                            <input
                                type="text"
                                minlength='<?php echo $firstNameLength?>'
                                pattern='<?php echo $firstNamePattern?>'
                                class="form-control"
                                name="firstName"
                                placeholder="<?php echo $stayShareCallbackLang['First name'] ?>..." 
                                required
                            />
                        </div>                    
                    <?php } ?>
                    <?php if (!array_get($_SESSION, 'facebook_user.last_name')) { ?>
                        <div class="form-group">
                            <input
                                type="text"
                                minlength='<?php echo $lastNameLength?>'
                                pattern='<?php echo $lastNamePattern?>'
                                class="form-control"
                                name="lastName"
                                placeholder="<?php echo $stayShareCallbackLang['Last name'] ?>..." 
                                required
                            />
                        </div>     
                    <?php } ?>
                    <?php if ($isUnderConsentAge) { ?>
                        <div style="color:white;display:block" class="checkbox gdprConditions">
                            <label>
                                <input type="checkbox" class="checkbox_check "name="gdpr_year" required> 
                                <?php echo $stayShareCallbackLang['gdpr_year'] ?>
                            </label>
                        </div>
                    <?php } ?>
                    <button type="submit"
                            class="btn btn-primary"><?php echo $stayShareCallbackLang['Conectar al wifi'] ?></button>
                </form>
            </div>
        <?php } else if (!array_get($_SESSION, 'facebook_user')){ ?>
            <i class="fa fa-meh-o fa-3x" aria-hidden="true"></i>
            <h3><?php echo $stayShareCallbackLang['Upps'] ?></h3>
            <p><?php echo $stayShareCallbackLang['No hemos podido conseguir tus datos de Facebook.'] ?>
                <br><?php echo $stayShareCallbackLang['insert data'] ?></p>
            <div class="col-sm-6 col-sm-offset-3 text-left">
                <form id="re-email-form"
                        action="<?php echo SECURE_BASE_PATH . 'stay-share/' . $_SESSION['guidHotel'] ?>" method="post">
                    <div class="form-group">
                        <label><?php echo $stayShareCallbackLang['Email'] ?></label>
                        <input type="email" class="form-control" name="refShareStep2email"
                                placeholder="<?php echo $stayShareCallbackLang['Email'] ?>..." required/>
                    </div>
                    <div class="form-group">
                        <label><?php echo $stayShareCallbackLang['Nombre'] ?></label>
                        <input type="text" class="form-control" name="refShareStep2name"
                                placeholder="<?php echo $stayShareCallbackLang['Nombre'] ?>..." required/>
                    </div>
                    <?php if ($isUnderConsentAge) { ?>
                        <div style="color:white;display:block" class="checkbox gdprConditions">
                            <label>
                                <input type="checkbox" class="checkbox_check "name="gdpr_year" required> 
                                <?php echo $stayShareCallbackLang['gdpr_year'] ?>
                            </label>
                        </div>
                    <?php } ?>
                    <button type="submit"
                            class="btn btn-primary"><?php echo $stayShareCallbackLang['Conectar al wifi'] ?></button>
                </form>
            </div>
        <?php } else if ($isUnderConsentAge) { ?>
            <i class="fa fa-meh-o fa-3x" aria-hidden="true"></i>
            <h3 style="color:white"><?php echo $stayShareCallbackLang['Upps'] ?></h3>
            <p style="color:white"><?php echo $stayShareCallbackLang['No hemos podido conseguir tus datos de Facebook.'] ?>
                <br><?php echo $stayShareCallbackLang['insert data'] ?></p>
            <div class="col-sm-6 col-sm-offset-3 text-left">
                <form id="re-email-form" method="post">
                    <div style="color:white;display:block" class="checkbox gdprConditions">
                        <label>
                            <input type="checkbox" class="checkbox_check "name="gdpr_year" required> 
                            <?php echo $stayShareCallbackLang['gdpr_year'] ?>
                        </label>
                    </div>
                    <button type="submit"
                            class="btn btn-primary"><?php echo $stayShareCallbackLang['Conectar al wifi'] ?></button>
                </form>
            </div>
        <?php } ?>
    </div>
    <?php include 'views/common/stepper.php'; ?>
</div>
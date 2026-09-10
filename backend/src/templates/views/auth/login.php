<?php $this->layout('_layout::index') ?>
<?php include LANG . $_SESSION['userLang'] . '/hotel-login.php' ?>
<div class="login-page">
    <div class="login-right-background">
        <div class="login-form-holder">
            <div class="login-form">
                <div id="animated_logo">
                    <img style="width:100%" src="https://images.hotelinking.com/login/hotelinking-transparent.png">
                </div>
                <h1 class="welcome"><?php echo $HotelLoginLang['hotelier Login'] ?></h1>
                <h3><?php echo $HotelLoginLang['hotelier intro text'] ?></h3>
                <p></p>
                <form class="ui form big" method="POST">
                    <div class="field">
                        <label><?php echo $HotelLoginLang['email'] ?></label>
                        <input type="email" name="email" placeholder="<?php echo $HotelLoginLang['email text'] ?>" autocomplete="off" class="inputBgOpaque" required>
                    </div>
                    <div class="field">
                        <label><?php echo $HotelLoginLang['password'] ?></label>
                        <input type="password" name="password" placeholder="<?php echo $HotelLoginLang['Your password..'] ?>" autocomplete="off" class="inputBgOpaque" maxlength="18" required>
                    </div>
                    <div class="field">
                        <button style="background-color:black !important" class="ui button primary-color bg large" type="submit"><?php echo $HotelLoginLang['Login button'] ?></button>
                    </div>
                    <div class="field">
                        <label class="small text-center"><?php echo $HotelLoginLang['remember password'] ?> <a style="color:rgb(163 230 53)" href="#" id="recoverPasswordButton" title="password recovery"><?php echo $HotelLoginLang['remember my password'] ?></a></label>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="recoverPassModal" class="ui modal">
    <i class="close icon"></i>
    <div class="header">
        <?php echo $HotelLoginLang['we need your email'] ?>
    </div>
    <div class="image content">
        <form method="POST" id="recoverPassForm" name="recoverPassForm" class="ui form big" style="width:370px">
            <div class="field">
                <label><?php echo $HotelLoginLang['email'] ?></label>
                <input type="email" name="email" placeholder="<?php echo $HotelLoginLang['email text'] ?>" autocomplete="off" class="inputBgOpaque" required >
            </div>
            <input type="hidden" name="password" value="none">
            <input type="hidden" name="emailToRecover" value="true">
            <div class="clearfix"></div>
        </form>
    </div>
    <div class="actions">
        <div id="recoverPass" class="ui button">
            <?php echo $HotelLoginLang['Get new password'] ?></div>
    </div>
</div>

<?php if(isset($challenge)): ?>
    <div id="challengeModal" class="ui modal">
        <i class="close icon"></i>
        <div class="header">
            <?php echo $HotelLoginLang[$challenge . ' modal title'] ?>
        </div>
        <div style="padding-bottom:0" class="image content">
            <?php echo $HotelLoginLang[$challenge . ' modal description'] ?>
        </div>
        <div class="image content">
            <form method="POST" id="challengeForm" name="challengeForm" class="ui form big">
                <div class="field">
                    <?php if ($challenge === "SMS_MFA" || $challenge === "SOFTWARE_TOKEN_MFA" || $challenge === "MFA_SETUP_VERIFY_SMS"): ?>
                        <label><?php echo $HotelLoginLang['Code'] ?></label>
                        <input type="text" name="code" placeholder="<?php echo $HotelLoginLang['Your code..'] ?>" autocomplete="off" class="inputBgOpaque" maxlength="18" required>
                        <input type="hidden" name="password" value="none">
                    <?php elseif ($challenge === "NEW_PASSWORD_REQUIRED"): ?>
                        <label><?php echo $HotelLoginLang['password'] ?></label>
                        <input 
                            pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$"
                            type="password" 
                            name="password" 
                            placeholder="<?php echo $HotelLoginLang['password'] ?>" 
                            autocomplete="off" 
                            class="inputBgOpaque" 
                            maxlength="18"
                            required
                        >
                    <?php elseif ($challenge === "RECOVER_ACCOUNT"): ?>
                        <div class="field">
                            <label><?php echo $HotelLoginLang['Code'] ?></label>
                            <input type="text" name="code" placeholder="<?php echo $HotelLoginLang['Your code..'] ?>" autocomplete="off" class="inputBgOpaque" maxlength="18" required>
                        </div>
                        <div class="field">
                            <label><?php echo $HotelLoginLang['password'] ?></label>
                            <input 
                                pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$"
                                type="password" 
                                name="password" 
                                placeholder="<?php echo $HotelLoginLang['password'] ?>" 
                                autocomplete="off" 
                                class="inputBgOpaque" 
                                maxlength="18"
                                required
                            >
                        </div>
                    <?php elseif ($challenge === "MFA_SETUP"): ?>
                        <div class="field">
                            <div class="ui radio checkbox">
                                <input type="radio" id="authenticator" name="mfa_type" value="authenticator" checked />
                                <label><?php echo $HotelLoginLang['authenticator app'] ?></label>
                            </div>
                        </div>
                        <div class="field">
                            <div class="ui radio checkbox">
                                <input type="radio" id="sms" name="mfa_type" value="sms" />
                                <label><?php echo $HotelLoginLang['SMS'] ?></label>
                            </div>
                        </div>
                        <div id="authenticatorForm" class="ui">
                            <p style="font-size:1rem; line-height: 1.4;">
                                <?php echo $HotelLoginLang['AuthenticationExplanation'] ?>
                            </p>
                            <center style="margin:2em;">
                                <div style="margin-bottom:1em" id="qrcode"></div>
                                <a style="font-size:1rem; line-height: 1.4;" id="toggle-secret" href="#"> <?php echo $HotelLoginLang['Show secret key'] ?></a>
                                <p id="secretKey" class="dnone" style="font-size:1rem; line-height: 1.4;word-wrap: break-word;"><?php echo $associateSoftwareCode ?></p>
                            </center>
                            <p style="font-size:1rem; line-height: 1.4;">
                                <?php echo $HotelLoginLang['AuthenticationExplanation2'] ?>
                            </p>
                            <label><?php echo $HotelLoginLang['Code'] ?></label>
                            <input type="text" name="code" placeholder="<?php echo $HotelLoginLang['Your code..'] ?>" autocomplete="off" class="inputBgOpaque" maxlength="18">
                        </div>
                        <div id="smsForm" class="ui dnone">
                            <p style="font-size:1rem; line-height: 1.4;"><?php echo $HotelLoginLang['SMS explanation'] ?></p>
                            <div class="field">
                                <label><?php echo $HotelLoginLang['telephone'] ?></label>
                                <?php 
                                    include_once RUTA_DIR . LIB . 'countryCodes.php';

                                    // Delete duplicated codes
                                    $uniqueCountryCodes = array_unique(array_column($countryCodes, 'code'));
                
                                    // Convert back to original format array
                                    $uniqueCountryCodes = array_map(function($code) {
                                        return ['code' => $code];
                                    }, $uniqueCountryCodes);
                
                                    // Sort country codes
                                    usort($uniqueCountryCodes, function ($a, $b) {
                                        return intval($a['code']) - intval($b['code']);
                                    });
                                ?>
                                <div style="max-width: 20em" class="ui grid">
                                    <div class="six wide column">
                                        <select class="form-control" id="phone_code" name="phone_code">
                                            <?php foreach ($uniqueCountryCodes as $country) { ?>
                                                <option value="<?php echo $country['code'] ?>" <?php echo $country['code'] === '+34' ? 'selected' : ''; ?> . '><?php echo $country['code'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="ten wide column">
                                        <input
                                            class="inputBgOpaque" 
                                            name="phone_number"
                                            type="tel"
                                            maxlength="20" 
                                            pattern="[\+]?[0-9]{1,19}"
                                            placeholder="<?php echo $HotelLoginLang['telephone placeholder'] ?>"
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <input type="hidden" name="password" value="none">
                    <?php endif; ?>
                    <input type="hidden" name="email" value="<?php echo $email ?>">
                    <input type="hidden" name="challenge" value="<?php echo $challenge ?>">
                    <input type="hidden" name="session" value="<?php echo $session ?? null ?>">
                </div>
                <div class="clearfix"></div>
            </form>
        </div>
        <div class="actions">
            <div id="challengeModalButton" class="ui button">
                <?php echo $HotelLoginLang['Confirm'] ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js" type="text/javascript"></script>
<script src="../../../../semantic/dist/semantic.min.js" type="text/javascript"></script>
<script src="<?php echo $this->asset('/public/javascript/login.js') ?>"></script>

<?php if(isset($challenge)): ?>
    <script>
        $('#challengeModal').modal('show');

        <?php if ($challenge === "MFA_SETUP") : ?>
            new QRCode(document.getElementById("qrcode"), {
                text: "otpauth://totp/<?php echo $email ?>?secret=<?php echo $associateSoftwareCode ?>&issuer=Hotelinking",
                width: 128,
                height: 128
            });
        <?php endif; ?>

    </script>
<?php endif; ?>
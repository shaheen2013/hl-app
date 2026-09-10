<div id="wrapper">
    <?php include TEMPLATES . 'hotel-sidebar.php'; ?>
    <div id="page-content-wrapper">
        <div class="top-bar">
            <?php include TEMPLATES . 'hotel-profile-menu.php'; ?>
            <img class="topbarLogo visible-xs" src="<?php echo DIR_IMG . 'topbar-logo-xs.png' ?>" width="77" height="70"
                 alt="top bar logo">
        </div>
        <div class="utility-bar">
            <div class="col-lg-12">
                <h1 class="pull-left"><i class="fa fa-user-secret"></i> Condiciones de privacidad del hotel</h1>
                <div class="breadcrumbs pull-right">
                    <ul>
                        <?php include(TEMPLATES . 'breadcrumbs.php'); ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="mainContent" id="fullContainer">
            <div class="col-lg-12">
                <div class="col-lg-8 mt2">
                    <form class="mb2" method="POST" id="privacyform">
                        <div class="form-group">
                            <label for="companyName">Nombre de la empresa</label>
                            <input type="text" class="form-control" name="company_name" id="companyName" value="<?php echo !empty($privacyData['company_name']) ? $privacyData['company_name'] : null ?>">
                        </div>
                        <div class="form-group">
                            <label for="companyAddress">Dirección de la empresa</label>
                            <input type="text" class="form-control" name="company_address" id="companyAddress" value="<?php echo !empty($privacyData['company_address']) ? $privacyData['company_address'] : null ?>">
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="companyNIF">NIF</label>
                                    <input type="text" class="form-control" name="company_nif" id="companyNIF" value="<?php echo !empty($privacyData['company_nif']) ? $privacyData['company_nif'] : null ?>">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="companyEmail">Email de contacto para asuntos de privacidad</label>
                                    <input type="text" class="form-control" name="company_email" id="companyEmail" value="<?php echo !empty($privacyData['company_email']) ? $privacyData['company_email'] : null ?>">
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label for="privacyPolicy">Condiciones de privacidad</label>
                            <textarea id="privacyPolicy" name="privacy_conditions"><?php echo !empty($privacyData['privacy_conditions']) ? $privacyData['privacy_conditions'] : null ?></textarea>
                        </div>
                        <?php if(!empty($_SESSION['c_logueado'])) : ?>
                            <div class="checkbox mb2">
                                <label>
                                    <input name="use_as_chain" type="checkbox" <?php echo !empty($privacyData['use_as_chain']) && $privacyData['use_as_chain'] == '1' ? 'checked' : '' ?>> Quiero usar la política de privacidad de la cadena.
                                </label>
                            </div>
                        <?php endif; ?>
                        <hr>
                        <div class="radio">
                            <label>
                                <input name="option" type="radio" value="onlyData" <?php echo $privacyData['option'] == 'onlyData' ? 'checked' : '' ?>> Soy el Responsable de los datos, Hotelinking los procesa.
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input name="option" type="radio" value="hotel" <?php echo $privacyData['option'] == 'hotel' ? 'checked' : '' ?>> Quiero usar mi propia política de privacidad.
                            </label>
                        </div>
                        <div class="radio mb2">
                            <label>
                                <input name="option" type="radio" value="hotelinking" <?php echo $privacyData['option'] == 'hotelinking' ? 'checked' : '' ?>> Hotelinking es el responsable de los datos, yo los proceso.
                            </label>
                        </div>
                        <hr>
                        <button type="submit" class="btn btn-primary privacyButton" >Actualiza mi política de privacidad</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo DIR_JS ?>feedback-errors.js"></script>
<script>
    var privacyPolicyEditor = new Jodit('#privacyPolicy', {
        height: 250,
        "buttons": "bold,strikethrough,underline,italic,|,,ul,ol,,outdent,indent,,|,link,,align,undo,redo,\n"
    });
</script>

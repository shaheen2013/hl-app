<?php
    include LANG . $_SESSION['userLang'] . '/hotel-profile-menu.php';
?>

<ul class="hidden-md hidden-sm hidden-xs top-bar-main-menu">
	<li><a href="<?php echo $urlTree['hotel-profile'] ?>" title="<?php echo $HotelProfileMenuLang['Basic info'] ?>" class="<?php echo $currentSubPage == 'hotel-profile' ? 'top-bar-active' : ''?>"><?php echo $HotelProfileMenuLang['Basic info'] ?></a></li>
	<li><a href="<?php echo $urlTree['hotel-profile-datos-de-reserva'] ?>" title="<?php echo $HotelProfileMenuLang['Booking info'] ?>" class="<?php echo $currentSubPage == 'hotel-profile-2' ? 'top-bar-active' : ''?>"><?php echo $HotelProfileMenuLang['Booking info'] ?></a></li>
    <li>
		<div class="dropdown">
			<a class="dropdown-toggle <?php echo $currentSubPage == 'hotel-profile-datos-landing' || $currentSubPage == 'login-configuration' || $currentSubPage == 'hotel-protocol-management' || $currentSubPage == 'eprivacy-management' ? 'top-bar-active' : ''?>" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                <?php echo $HotelProfileMenuLang['Manage captive portal'] ?>
				<span class="caret"></span>
			</a>
			<ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
                <li><a href="<?php echo $urlTree['hotel-profile-datos-landing'] ?>" title="<?php echo $HotelProfileMenuLang['Landing Page'] ?>" class="<?php echo $currentSubPage == 'hotel-profile-datos-landing' ? 'active' : ''?>"><?php echo $HotelProfileMenuLang['Landing Page'] ?></a></li>
                <li><a href="<?php echo $urlTree['login-configuration'] ?>" title="<?php echo $HotelProfileMenuLang['Login configuration'] ?>" class="<?php echo $currentSubPage == 'login-configuration' ? 'active' : ''?>"><?php echo $HotelProfileMenuLang['Login configuration'] ?></a></li>
                <?php if ((!empty($_SESSION['c_logueado']) && !empty($_SESSION['chain']['brand_id'])) || $_SESSION['isIndependent']) { ?>
                    <li><a href="<?php echo $urlTree['hotel-protocol-management']; ?>" title="<?php echo $HotelProfileMenuLang['protocol management']; ?>" class="<?php echo $currentSubPage == 'hotel-protocol-management' ? 'active' : ''; ?>"><?php echo $HotelProfileMenuLang['protocol management']; ?></a></li>
                <?php } ?>
                <li><a href="<?php echo $urlTree['eprivacy-management'] ?>" title="Hotel Privacy" class="<?php echo $currentSubPage == 'eprivacy-management' ? 'active' : ''?>" ><?php echo $HotelProfileMenuLang['privacidad'] ?></a></li>
            </ul>
		</div>
	</li>

    <li><a href="<?php echo $urlTree['hotel-profile-langs'] ?>" title="<?php echo $HotelProfileMenuLang['Activate languages'] ?>" class="<?php echo $currentSubPage == 'hotel-profile-langs' ? 'top-bar-active' : ''?>" ><?php echo $HotelProfileMenuLang['Activate languages'] ?></a></li>
	
    <?php if(!hotelDeCadena($_SESSION['h_logueado']) && $_SESSION['permisos']['LY'] == '1'){//Si el hotel no pertenece a cadena?>
	<li><a href="<?php echo $urlTree['loyalty-management'] ?>" title="<?php echo $HotelProfileMenuLang['Loyalty management'] ?>"><?php echo $HotelProfileMenuLang['Loyalty management'] ?></a></li>
    <li><a href="<?php echo $urlTree['loyalty-management'] ?>" title="<?php echo $HotelProfileMenuLang['Loyalty management'] ?>"><?php echo $HotelProfileMenuLang['Loyalty management'] ?></a></li>
	<?php }?>

    <li>
		<div class="dropdown">
			<a class="dropdown-toggle <?php echo $currentSubPage == 'review-urls' || $currentSubPage == 'birthday-email' || $currentSubPage == 'satisfaction-filter' || $currentSubPage == 'pushtech-config' || $currentSubPage == 'clients-reports-management' || $currentSubPage == 'pixel-config' ? 'top-bar-active' : ''?>" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            <?php echo $HotelProfileMenuLang['Marketing Tools'] ?>
				<span class="caret"></span>
			</a>
			<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                <?php if ($_SESSION['permisos']['review'] == 1 && $_SESSION['permisos']['satisfaction'] == 0) { ?>
                    <li><a href="<?php echo $urlTree['review-urls'] ?>" title="Reviews" class="<?php echo $currentSubPage == 'review-urls' ? 'active' : ''?>"><?php echo $HotelProfileMenuLang['Reviews automation'] ?></a></li>
                <?php } ?>
                <?php if ($_SESSION['permisos']['satisfaction'] == 1) { ?>
                    <li><a href="<?php echo $urlTree['review-urls'] ?>" title="Reviews" class="<?php echo $currentSubPage == 'review-urls' ? 'active' : ''?>"><?php echo $HotelProfileMenuLang['Reviews automation'] ?></a></li>
					<li><a href="<?php echo $urlTree['hotel-satisfaction'] ?>" title="Satisfaction" class="<?php echo $currentSubPage == 'satisfaction-filter' ? 'active' : ''?>"><?php echo $HotelProfileMenuLang['hotel-satisfaction-surveys'] ?></a></li>
				<?php } ?>
				<li><a href="<?php echo $urlTree['clients-reports-management'] ?>" title="clients reports management" class="<?php echo $currentSubPage == 'clients-reports-management' ? 'active' : ''?>"><?php echo $HotelProfileMenuLang['clients-reports-management'] ?></a></li>
					<li><a href="<?php echo $urlTree['birthday-email-offer-config'] ?>" title="Birthday email config" class="<?php echo $currentSubPage == 'birthday-email' ? 'active' : ''?>"><?php echo $HotelProfileMenuLang['Birthday email configuration'] ?></a></li>

                <?php if ($_SESSION['permisos']['loyalty'] == 1) { ?>
                    <li><a href="<?php echo $urlTree['loyalty-config'] ?>" title="Loyalty config" class="<?php echo $currentSubPage == 'loyalty-config' ? 'active' : ''?>"><?php echo $HotelProfileMenuLang['Loyalty configuration'] ?></a></li>
                <?php } ?>
                <?php if ($_SESSION['permisos']['pushtech'] == 1) :?>
                    <li><a href="<?php echo $urlTree['pushtech-config'] ?>" title="<?php echo $HotelProfileMenuLang['pushtech integration'] ?>" class="<?php echo $currentSubPage == 'pushtech-config' ? 'active' : ''?>"><?php echo $HotelProfileMenuLang['pushtech integration'] ?></a></li>
				<?php endif; ?>
                <li><a href="<?php echo $urlTree['pixel-config'] ?>" title="Pixel config" class="<?php echo $currentSubPage == 'pixel-config' ? 'active' : ''?>"><?php echo $HotelProfileMenuLang['pixel-config'] ?></a></li>
			</ul>
		</div>
	</li>

    <?php if($_SESSION['permisos']['autocheckin'] == 1) {?>
    <li>
        <div class="dropdown">
            <a class="dropdown-toggle <?php echo $currentSubPage == 'documents-management' || $currentSubPage == 'autocheckin-configuration' || $currentSubPage == 'autocheckin-customized-texts' || $currentSubPage == 'autocheckin-confirmation' ? 'top-bar-active' : ''?>" id="dropdownMenu-autocheckin" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                AutoCheckin
                <span class="caret"></span>
            </a>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenu-autocheckin">
                <li><a href="<?php echo $urlTree['documents-management'] ?>" title="Document management" class="<?php echo $currentSubPage == 'documents-management' ? 'active' : ''?>"><?php echo $HotelProfileMenuLang['documents-management'] ?></a></li>
                <li><a href="<?php echo $urlTree['autocheckin-configuration'] ?>" title="Autocheckin Configuration" class="<?php echo $currentSubPage == 'autocheckin-configuration' ? 'active' : ''?>"><?php echo $HotelProfileMenuLang['autocheckin-configuration'] ?></a></li>
                <li><a href="<?php echo $urlTree['autocheckin-customized-texts'] ?>" title="Customized Texts configuration" class="<?php echo $currentSubPage == 'autocheckin-customized-texts' ? 'active' : ''?>"><?php echo $HotelProfileMenuLang['autocheckin-customized-texts'] ?></a></li>
                <li><a href="<?php echo $urlTree['autocheckin-confirmation'] ?>" title="Autocheckin confirmation" class="<?php echo $currentSubPage == 'autocheckin-confirmation' ? 'active' : ''?>"><?php echo $HotelProfileMenuLang['autocheckin-confirmation'] ?></a></li>
            </ul>
        </div>
    </li>
    <?php }?>
</ul>

<div class="dropdown visible-sm visible-xs visible-md hamburguer-btn pull-left">
	<a href="<?php echo $urlTree['hotel-profile'] ?>" class="hasTooltip pull-left access-profile-mobile" data-toggle="tooltip" data-placement="bottom" title="Access to your profile"><i class="fa fa-cog fa-2x pr"></i></a>
	<i class="fa fa-bars dropdown-toggle fa-2x" id="dropdownMenu1" data-toggle="dropdown"></i>
	<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
		<li><a href="<?php echo $urlTree['hotel-profile'] ?>" title="<?php echo $HotelProfileMenuLang['Basic info'] ?>"><?php echo $HotelProfileMenuLang['Basic info'] ?></a></li>
		<li><a href="<?php echo $urlTree['hotel-profile-datos-de-reserva'] ?>" title="<?php echo $HotelProfileMenuLang['Booking info'] ?>"><?php echo $HotelProfileMenuLang['Booking info'] ?></a></li>
        
        <?php if(!hotelDeCadena($_SESSION['h_logueado']) && $_SESSION['permisos']['LY'] == '1'){//Si el hotel no pertenece a cadena?>
		    <li><a href="<?php echo $urlTree['loyalty-management'] ?>" title="<?php echo $HotelProfileMenuLang['Loyalty management'] ?>"><?php echo $HotelProfileMenuLang['Loyalty management'] ?></a></li>
		<?php }?>

        <li role="separator" class="divider"></li>
		<li class="dropdown-header"><?php echo $HotelProfileMenuLang['Manage captive portal'] ?></li>

		<li><a href="<?php echo $urlTree['hotel-profile-datos-landing'] ?>" title="<?php echo $HotelProfileMenuLang['Landing Page'] ?>"><?php echo $HotelProfileMenuLang['Landing Page'] ?></a></li>
        <li><a href="<?php echo $urlTree['login-configuration'] ?>" title="<?php echo $HotelProfileMenuLang['Login configuration'] ?>"><?php echo $HotelProfileMenuLang['Login configuration'] ?></a></li>
        <li><a href="<?php echo $urlTree['eprivacy-management'] ?>" title="Hotel Privacy" ><?php echo $HotelProfileMenuLang['privacidad'] ?></a></li>
        
        <?php if ((!empty($_SESSION['c_logueado']) && !empty($_SESSION['chain']['brand_id'])) || $_SESSION['isIndependent']) { ?>
            <li> <a href="<?php echo $urlTree['hotel-protocol-management']; ?>" title="<?php echo $HotelProfileMenuLang['protocol management']; ?>"><?php echo $HotelProfileMenuLang['protocol management']; ?></a></li>
        <?php } ?>

        <li role="separator" class="divider"></li>
		<li class="dropdown-header"><?php echo $HotelProfileMenuLang['Dashboard configuration'] ?></li>

		<li><a href="<?php echo $urlTree['hotel-profile-langs'] ?>" title="<?php echo $HotelProfileMenuLang['Activate languages'] ?>"><?php echo $HotelProfileMenuLang['Activate languages'] ?></a></li>

		<li role="separator" class="divider"></li>
		<li class="dropdown-header">Marketing tools</li>
        
        <?php if ($_SESSION['permisos']['review'] == 1 && $_SESSION['permisos']['satisfaction'] == 0) { ?>
            <li><a href="<?php echo $urlTree['review-urls'] ?>" title="Reviews"><?php echo $HotelProfileMenuLang['Reviews automation'] ?></a></li>
        <?php } ?>

        <?php if ($_SESSION['permisos']['satisfaction'] == 1) { ?>
            <li><a href="<?php echo $urlTree['review-urls'] ?>" title="Reviews"><?php echo $HotelProfileMenuLang['Reviews automation'] ?></a></li>
			<li><a href="<?php echo $urlTree['hotel-satisfaction'] ?>" title="Satisfaction"><?php echo $HotelProfileMenuLang['hotel-satisfaction-surveys'] ?></a></li>
		<?php } ?>

        <li><a href="<?php echo $urlTree['clients-reports-management'] ?>" title="clients reports management"><?php echo $HotelProfileMenuLang['clients-reports-management'] ?></a></li>
        <li><a href="<?php echo $urlTree['birthday-email-offer-config'] ?>" title="Birthday email config"><?php echo $HotelProfileMenuLang['Birthday email configuration'] ?></a></li>
		
        <?php if ($_SESSION['permisos']['pushtech'] == 1) :?>
			<li><a href="<?php echo $urlTree['pushtech-config'] ?>" title="<?php echo $HotelProfileMenuLang['pushtech integration'] ?>"><?php echo $HotelProfileMenuLang['pushtech integration'] ?></a></li>
		<?php endif; ?>

        <?php if ($_SESSION['permisos']['autocheckin'] == 1) { ?>
            <li role="separator" class="divider"></li>
            <li class="dropdown-header">AutoCheckin</li>
            <li><a href="<?php echo $urlTree['documents-management'] ?>" title="Documents management"><?php echo $HotelProfileMenuLang['documents-management'] ?></a></li>
            <li><a href="<?php echo $urlTree['autocheckin-configuration'] ?>" title="Autocheckin Configuration" ><?php echo $HotelProfileMenuLang['autocheckin-configuration'] ?></a></li>
            <li><a href="<?php echo $urlTree['autocheckin-customized-texts'] ?>" title="Customized Texts configuration" ><?php echo $HotelProfileMenuLang['autocheckin-customized-texts'] ?></a></li>
            <li><a href="<?php echo $urlTree['autocheckin-confirmation'] ?>" title="Autocheckin confirmation" ><?php echo $HotelProfileMenuLang['autocheckin-confirmation'] ?></a></li>
        <?php } ?>
	</ul>
</div>

<?php
include TEMPLATES . 'hotel-suggest.php'; ?>
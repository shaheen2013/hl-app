<?php //Miramos si esta definida la variable de control de index.php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
} ?>

<?php if ($integrationEnabled ?? false) { ?>
    <!-- JsonForm imports -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.9.1/underscore-min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/jsonform@2.1.3/lib/jsonform.min.js"></script>
    <!-- Import integration scripts -->
    <script type="text/javascript" src="<?php echo DIR_JS ?>hotelinking_integrations.js"></script>
    <script type="text/javascript" src="<?php echo DIR_JS ?>hotelinking_payments.js"></script>
<?php } ?>

<div class="mt2">
    <div class="col-lg-12 text-center">
        <h2>Acciones</h2>
        <div class="btn-group" role="group" aria-label="...">
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#myModal">Añadir Hotel</button>
            <a href="<?php echo SECURE_BASE_PATH . 'private/' . $urlTree['private-marketing-multipliers'] ?>" class="btn btn-default">Multiplicadores de marketing</a>
            <a href="<?php echo SECURE_BASE_PATH . 'private/' . $urlTree['private-search-users'] ?>" class="btn btn-default">Búsqueda de usuarios</a>
            <a href="<?php echo SECURE_BASE_PATH . 'private/' . $urlTree['private-edit-booking-engines'] ?>" class="btn btn-default">Actualización de los Motores de Reserva</a>
            <a class="btn btn-warning" href="app/logout" title="logout">logout</a>
        </div>
    </div>

    <?php if ($show_error ?? false) { ?>
        <div class="col-lg-12 row">
            <div class="col-md-6 col-md-offset-3 text-center" style="margin-top: 10px;">
                <strong>
                    <p class="text-danger">Error, it is possible that hotels are not loaded correctly.</p>
                </strong>
            </div>
        </div>
    <?php }; ?>

    <div class="col-lg-12">
        <?php
        if ($searchInput ?? false) { ?>
            <h2 class="col-sm-4">Listado de hoteles</h2>
            <div class="col-sm-offset-4 col-sm-3 mt2">
                <input class="form-control" type="text" placeholder="Search by id, name or email" aria-label="Search" id="searchInput">
            </div>
            <button class="col-sm-1 mt2 btn btn-success" type="button" onClick="onSearchHotel()">Search</button>
        <?php } else { ?>
            <div class="row mt4">
                <div class="col-sm-offset-1 col-sm-8 text-center">
                    <input class="form-control input-lg text-center" type="text" placeholder="Search by id, name or email" aria-label="Search" id="searchInput">
                </div>
                <button class="btn btn-success col-sm-1 input-lg" type="button" onClick="onSearchHotel()">Search</button>
            </div>
        <?php } ?>

        <div class="table-responsive">
            <?php if ($searchInput ?? false) { ?>
                <table class="table table-hover table-bordered table-striped">
                    <tr>
                        <td><input type="checkbox" id="selectAllBrandsCheckbox" onchange="selectAllCheckboxes()"></td>
                        <td>Brand ID</td>
                        <td>Account ID</td>
                        <td>Guid</td>
                        <td>Hotel Name</td>
                        <td>Hotel Email</td>
                        <td>Affilired name / ID</td>
                        <td>Features</td>
                    </tr>
                    <?php foreach ($hotels as $hotel) { ?>
                        <tr>
                            <?php if (data_get($hotel, '0.activated')) { ?>
                                <td><input type="checkbox" name="selectedBrands[]" class="brandCheckbox" data-brand-id="<?php echo $hotel[0]['brand_id']; ?>" data-hotel-id="<?php echo $hotel[0]['id_hotel']; ?>"></td>
                            <?php } else { ?>
                                <td><input type="checkbox" disabled></td>
                            <?php } ?>
                            <td><?php echo data_get($hotel, '0.brand_id') ?></td>
                            <td><?php echo data_get($hotel, '0.account_id') ?></td>
                            <td>
                                <a href="/stay-share/<?php echo data_get($hotel, '0.guid') ?>" target="_blank"><?php echo data_get($hotel, '0.guid') ?></a>
                                <br>
                                <a onclick="changeHotel(<?php echo data_get($hotel, '0.id_hotel') ?>, window.location.origin, true, 'super_admin')" class="btn btn-primary">Super Admin Login</a>
                                <a onclick="changeHotel(<?php echo data_get($hotel, '0.id_hotel') ?>, window.location.origin, true, 'admin')" class="btn btn-primary">Admin Login</a>
                            </td>
                            <td><?php echo data_get($hotel, '0.hotelName') ?></td>
                            <td><?php echo data_get($hotel, '0.email') ?></td>
                            <td>
                                <form method="POST" class="form-inline">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="afName" placeholder="Affilired name" value="<?php echo (!empty($hotel[0]['affilired_hotel']) ? $hotel[0]['affilired_hotel'] : '') ?>" data-container="body" data-toggle="popover" data-placement="left" data-content="Recuerda. Todo en minúsculas">
                                    </div>
                                    <div class="form-group">
                                        <input type="text" style="width:80px" class="form-control" name="afId" placeholder="Affilired ID" value="<?php echo (!empty($hotel[0]['affilired_id']) ? $hotel[0]['affilired_id'] : '') ?>">
                                    </div>
                                    <input type="hidden" value="<?php echo data_get($hotel, '0.id_hotel') ?>" name="id_hotel">
                                    <input type="hidden" name="guid" value="<?php echo data_get($hotel, '0.guid') ?>">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </form>
                            </td>
                            <td>
                                <?php if (data_get($hotel, '0.activated')) {
                                    $hasPortalPro = hasAssignedProduct($hotel, $portalProProductId); ?>
                                    <form>
                                        <div <?php echo ((is_null($hotel['integration']['pushtech']['key']) && is_null($hotel['integration']['pushtech']['secret'])) ? 'data-has-keys="0" data-container="body" data-toggle="popover" data-placement="left" data-content="Hay que tener credentiales antes de activar"' : 'data-has-keys="1"') ?>>
                                            <input type="radio" name="hotel_<?php echo $hotel[0]['brand_id'] ?>_<?php echo $pushtechProductId ?>" value="<?php echo (hasAssignedProduct($hotel, $pushtechProductId) ? 1 : 0) ?>" <?php echo (hasAssignedProduct($hotel, $pushtechProductId) ? 'checked' : '') ?> <?php echo ((is_null($hotel['integration']['pushtech']['key']) && is_null($hotel['integration']['pushtech']['secret'])) ? 'readonly' : '') ?> onclick="onClickPushtech(this.value, $(this), <?php echo $hotel[0]['id_hotel'] ?>, <?php echo $hotel[0]['brand_id'] ?>, <?php echo (hasAssignedProduct($hotel, $pushtechProductId) == 1 ? 0 : 1) ?>, <?php echo $pushtechProductId ?>)"> Pushtech <a class="pushtech_edit_button" style="cursor:pointer;" data-toggle="modal" data-target="#pushtechModal" data-idHotel="<?php echo $hotel[0]['id_hotel'] ?>" data-key="<?php echo (!empty($hotel['integration']['pushtech']['key']) ? $hotel['integration']['pushtech']['key'] : '') ?>" data-secret="<?php echo (!empty($hotel['integration']['pushtech']['secret']) ? $hotel['integration']['pushtech']['secret'] : '') ?>" data-brand-id="<?php echo $hotel[0]['brand_id'] ?>" data-pushtech-brand-id="<?php echo (!empty($hotel['integration']['pushtech']['integration_brand_id']) ? $hotel['integration']['pushtech']['integration_brand_id'] : '') ?>">edit</a><br />
                                        </div>
                                        <?php if ($integrationEnabled ?? false) { ?>
                                            <input type="radio" name="hotel_<?php echo $hotel[0]['brand_id'] ?>_<?php echo $datamatchProductId ?>" value="<?php echo (hasAssignedProduct($hotel, $datamatchProductId) ? 1 : 0) ?>" <?php echo (hasAssignedProduct($hotel, $datamatchProductId) ? 'checked' : '') ?> onclick="onClickDatamatch(this.value, $(this), <?php echo $hotel[0]['id_hotel'] ?>, <?php echo $hotel[0]['brand_id'] ?>, <?php echo (hasAssignedProduct($hotel, $datamatchProductId) == 1 ? 1 : 0) ?>, <?php echo $datamatchProductId ?>)"> DataMatch <a class="datamatch_edit_button" style="cursor:pointer;" data-toggle="modal" data-target="#datamatchModal" data-datamatch-hotel-id="<?php echo $hotel[0]['id_hotel'] ?>" data-datamatch-brand-id="<?php echo $hotel[0]['brand_id'] ?>">edit</a><br />
                                            <input type="radio" <?php echo ($hotel['integration']['pms']['activated'] == 1 ? 'checked' : '') ?> onclick="onClickIntegrationByType('pms')($(this), <?php echo $hotel[0]['id_hotel'] ?>, <?php echo $hotel[0]['brand_id'] ?>, <?php echo $hotel['integration']['pms']['integration_brand_id'] ?>, <?php echo ($hotel['integration']['pms']['activated'] == 1 ? 1 : 0) ?>, <?php echo ($hasPortalPro) ?>)"> Integration <a class="integration_edit_button" style="cursor:pointer;" data-toggle="modal" data-target="#integrationModal" data-hotel-id="<?php echo $hotel[0]['id_hotel'] ?>" data-brand-id="<?php echo $hotel[0]['brand_id'] ?>">edit</a><br />
                                        <?php } ?>
                                    </form>

                                    <?php
                                    foreach ($products as $product) { ?>
                                        <?php if ((in_array($product['producto'], ['portal_pro', 'portal_redirect']) && !($integrationEnabled ?? false)) || $product['producto'] == 'pushtech' || $product['producto'] == 'datamatch') { ?>
                                            <?php continue ?>
                                        <?php } ?>

                                        <?php
                                        $hasAssignedProduct = hasAssignedProduct($hotel, $product['id']);
                                        ?>
                                        <form name="hotel-products" method="POST">
                                            <input name="hotel_id" type="number" value="<?php echo $hotel[0]['id_hotel'] ?>" hidden>
                                            <input name="product_id" type="number" value="<?php echo $product['id'] ?>" hidden>
                                            <input name="brand_id" value="<?php echo $hotel[0]['brand_id'] ?>" type="hidden">
                                            <input name="product_name" type="text" value="<?php echo array_get($product, 'producto') ?>" hidden>
                                            <input type="radio" <?php echo (($product['producto'] == 'require_room_num' && $hasPortalPro || $product['producto'] == 'review' && hasAssignedProduct($hotel, findProductId($products, 'satisfaction')) == 1) || $product['producto'] == 'portal' ? 'disabled' : '') ?> <?php echo ($product['producto'] == 'review' && hasAssignedProduct($hotel, findProductId($products, 'satisfaction')) == 1 || $product['producto'] == 'portal') ? 'checked' : '' ?> <?php if ($product['producto'] == 'portal_pro') { ?> onclick="if (canPortalProBeEnabled(this, <?php echo $hasPortalPro; ?>, <?php echo $hotel['integration']['pms']['activated']; ?>)) {sendProductActivationRequest(<?php echo $hotel[0]['id_hotel'] ?>, <?php echo $product['id'] ?>, <?php echo $hotel[0]['brand_id'] ?>, '<?php echo array_get($product, 'producto') ?>', this.value);}" <?php } else if ($product['producto'] == 'portal_redirect') { ?> onclick="if (canPortalRedirectBeEnabled(this, <?php echo $hotel['integration']['redirect']['activated']; ?>)) {sendProductActivationRequest(<?php echo $hotel[0]['id_hotel'] ?>, <?php echo $product['id'] ?>, <?php echo $hotel[0]['brand_id'] ?>, '<?php echo array_get($product, 'producto') ?>', this.value);}" <?php } else { ?> onclick="sendProductActivationRequest(<?php echo $hotel[0]['id_hotel'] ?>, <?php echo $product['id'] ?>, <?php echo $hotel[0]['brand_id'] ?>, '<?php echo array_get($product, 'producto') ?>', this.value);" <?php } ?> name="hotel_<?php echo $hotel[0]['brand_id'] ?>_<?php echo $product['id'] ?>" value="<?php echo ($hasAssignedProduct ? '1' : '0') ?>" <?php echo (hasAssignedProduct($hotel, $product['id']) ? 'checked' : '') ?>>
                                            <?php echo ucfirst(str_replace('_', ' ', $product['producto'])) ?>
                                            <?php if ($product['producto'] == 'widget') { ?>
                                                <a class="widget_edit_button" style="cursor:pointer;" data-toggle="modal" data-target="#widgetModal" data-prudct-name="<?php echo array_get($product, 'producto') ?>" data-product-id="<?php echo $product['id'] ?>" data-brand-id="<?php echo $hotel[0]['brand_id'] ?>" data-idHotel="<?php echo $hotel[0]['id_hotel'] ?>">edit</a>
                                            <?php } ?>
                                            <?php if ($product['producto'] == 'portal') { ?>
                                                <a class="portal_configuration_edit_button" style="cursor:pointer;" data-toggle="modal" data-target="#portalConfigurationModal" data-prudct-name="<?php echo array_get($product, 'producto') ?>" data-product-id="<?php echo $product['id'] ?>" data-brand-id="<?php echo $hotel[0]['brand_id'] ?>" data-idHotel="<?php echo $hotel[0]['id_hotel'] ?>" data-hotel-guid="<?php echo data_get($hotel, '0.guid') ?>" data-bypass-active="<?php echo array_get($hotel, '0.bypass_active') ?>" data-show-radius-ticket-to-hosted="<?php echo array_get($hotel, '0.show_radius_ticket_to_hosted') ?> " data-only-hosted-guests="<?php echo array_get($hotel, '0.only_hosted_guests') ?> " data-phone-active="<?php echo array_get($hotel, '0.phone_active') ?> " data-commercial-profile="<?php echo array_get($hotel, '0.commercial_profile') ?>" data-has-access-to-hotspot="<?php echo array_get($hotel, '0.has_access_to_hotspot') ?> " data-add-other-option-on-gender="<?php echo array_get($hotel, '0.add_other_option_on_gender') ?> " data-remove-gender-field="<?php echo array_get($hotel, '0.remove_gender_field') ?> ">edit</a>
                                            <?php } ?>
                                            <?php if ($product['producto'] == 'portal_pro') { ?>
                                                <a class="portalPro_edit_button" style="cursor:pointer;" data-toggle="modal" data-target="#portalProModal" data-product-id="<?php echo $product['id'] ?>" data-hotel-id="<?php echo $hotel[0]['id_hotel'] ?>" data-brand-id="<?php echo $hotel[0]['brand_id'] ?>" data-is_pms_integration_activated="<?php echo $hotel['integration']['pms']['activated'] ?? false ?>" data-first_name="<?php echo $hotel['portalPro']['first_name'] ?>" data-last_name="<?php echo $hotel['portalPro']['last_name'] ?>" data-document_id="<?php echo $hotel['portalPro']['document_id'] ?>" data-room_number="<?php echo $hotel['portalPro']['room_number'] ?>" data-access_code="<?php echo $hotel['portalPro']['access_code'] ?>" data-premium_code="<?php echo $hotel['portalPro']['premium_code'] ?>" data-radius_ticket="<?php echo $hotel['portalPro']['radius_ticket'] ?>" data-premium_ticket="<?php echo $hotel['portalPro']['premium_ticket'] ?>" data-max_validations="<?php echo $hotel['portalPro']['max_validations'] ?>" data-restrictive="<?php echo $hotel['portalPro']['restrictive'] ?>">edit</a>
                                            <?php } ?>
                                            <?php if ($product['producto'] == 'portal_redirect') { ?>
                                                <a class="portalRedirect_edit_button" style="cursor:pointer;" data-toggle="modal" data-target="#portalRedirectModal" data-product-id="<?php echo $product['id'] ?>" data-hotel-id="<?php echo $hotel[0]['id_hotel'] ?>" data-brand-id="<?php echo $hotel[0]['brand_id'] ?>">edit</a>
                                            <?php } ?>
                                            <?php if ($product['producto'] == 'autocheckin') { ?>
                                                <a href="#" class="autocheckin-option" data-toggle="modal" data-target="#autocheckin-modal" data-brand-id="<?php echo $hotel[0]['brand_id'] ?>" data-product-id="<?php echo $product['id']; ?>" data-hotel-id="<?php echo $hotel[0]['id_hotel']; ?>" data-integration-brand-id="<?php echo $hotel['integration']['pms']['integration_brand_id']; ?>">edit</a>
                                            <?php } ?>
                                            <?php if ($product['producto'] == 'payments' && $hasAssignedProduct) { ?>
                                                <a href="#" class="payments-option" data-toggle="modal" data-target="#payments-modal" data-brand-id="<?php echo $hotel[0]['brand_id'] ?>" data-product-id="<?php echo $product['id']; ?>">edit</a>
                                            <?php } ?>
                                            <br />
                                        </form>
                                    <?php } ?>

                                <?php } else { ?>
                                    Archived hotel
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            <?php } ?>
        </div>
    </div>
</div>
<div class="modal fade" tabindex="-1" role="dialog" id="myModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Añadir un hotel</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <h2>Invitar hoteles nuevos</h2>
                        <form method="post">
                            <div class="form-group">
                                <input name="hotelName" class="form-control" type="text" required placeholder="hotel name" />
                            </div>
                            <input type="submit" value="enviar" class="btn btn-primary" name="enviarInvitacion" />
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="pushtechModal" tabindex="-1" role="dialog" aria-labelledby="PushtechModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><img src="../../public/img/pushtech_logo.png" alt="pushtech" height="15"> API credentials</h4>
            </div>
            <div class="modal-body">
                <form method="POST">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="pushtech_account_id">Pushtech account id</label>
                                <input type="text" name="pushtech_account_id" class="form-control" id="pushtech_account_id" placeholder="Insert pushtech account id">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="pushtech_account_secret">Pushtech account secret</label>
                                <input type="text" name="pushtech_account_secret" class="form-control" id="pushtech_account_secret" placeholder="Insert pushtech account secret">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="pushtech_account_environment">Pushtech account environment</label>
                                <select name="pushtech_account_environment" class="form-control" id="pushtech_account_environment">
                                    <option value="eu">Europe (eu)</option>
                                    <option value="us">USA (us)</option>
                                </select>
                            </div>
                        </div>
                        <input type="hidden" id="pushtech_id_hotel" name="id_hotel">
                        <input type="hidden" id="pushtech_brand_id" name="brand_id">
                    </div>
                    <button type="submit" class="btn btn-success">Save id and secret</button>
                </form>
                <hr>
                <div class="row">
                    <form method="POST" id="synchronize_users">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="pushtech_account_id">Synchronize Users</label>
                                <input type="hidden" id="synchronize_brand_id" name="brand_id">
                                <input type="hidden" id="synchronize_pushtech_brand_id" name="integration_brand_id">
                            </div>
                            <div class="form-group">
                                <button id="synchronize_users_button" class="btn btn-info" type="submit">Synchronize</button>
                                <span id="synchronize_users_response"></span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <small>Al guardar se va a probar que las credenciales son válidas</small>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="widgetModal" tabindex="-1" role="dialog" aria-labelledby="RequestRoomModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"> Widget configuration</h4>
            </div>
            <div class="modal-body">
                <form method="POST" id="#widgetUrlForm">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="widget_urls">Introduce widget urls</label>
                                <input type="text" class="form-control" name="widget_urls" id="widget_urls" placeholder="Introduce widget urls">
                            </div>
                        </div>
                        <input id="widget_product_id" name="product_id" type="hidden">
                        <input id="widget_brand_id" name="brand_id" type="hidden">
                        <input id="widget_product_name" name="product_name" type="hidden">
                        <input id="widget_active" name="widget" value="1" type="hidden">
                    </div>
                    <button type="submit" class="btn btn-success">Save configuration</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="portalConfigurationModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Portal Configuration</h4>
            </div>
            <div class="modal-body">
                <div class="row" id="portalConfigurationLoader">
                    <div class="col-lg-12 text-center">
                        <div class="svg-container"> <svg width="100" height="100" viewBox="0 0 38 38" xmlns="http://www.w3.org/2000/svg">
                                <defs>
                                    <linearGradient x1="8.042%" y1="0%" x2="65.682%" y2="23.865%" id="a">
                                        <stop stop-color="#65c3df" stop-opacity="0" offset="0%"></stop>
                                        <stop stop-color="#65c3df" stop-opacity=".631" offset="63.146%"></stop>
                                        <stop stop-color="#65c3df" offset="100%"></stop>
                                    </linearGradient>
                                </defs>
                                <g fill="none" fill-rule="evenodd">
                                    <g transform="translate(1 1)">
                                        <path d="M36 18c0-9.94-8.06-18-18-18" id="Oval-2" stroke="url(#a)" stroke-width="2" transform="rotate(145.839 18 18)">
                                            <animateTransform attributeName="transform" type="rotate" from="0 18 18" to="360 18 18" dur="0.9s" repeatCount="indefinite"></animateTransform>
                                        </path>
                                        <circle fill="#65c3df" cx="36" cy="18" r="1" transform="rotate(145.839 18 18)">
                                            <animateTransform attributeName="transform" type="rotate" from="0 18 18" to="360 18 18" dur="0.9s" repeatCount="indefinite"></animateTransform>
                                        </circle>
                                    </g>
                                </g>
                            </svg> </div>
                    </div>
                </div>
                <div id="portalConfigurationForm">
                    <form method="POST" class="form-inline">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-12">
                                    <input type="checkbox" name="bypass_active">
                                    <label for="bypass_active">Active bypass</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <input type="checkbox" name="phone_active">
                                    <label for="phone_active">Active phone</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <input type="checkbox" name="hide_unsubscribed_clients">
                                    <label for="hide_unsubscribed_clients">Hide unsubscribed clients</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <input type="checkbox" name="commercial_profile">
                                    <label for="commercial_profile">Commercial profile</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 form-group form-inline brand-config-fields">
                                    <label>Redirect URL</label>
                                    <input type="text" value="" name="url_redirect" class="form-control flex-grow-1" style="width: 20em;" placeholder="https://...">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <input type="checkbox" name="show_radius_ticket_to_hosted">
                                    <label for="show_radius_ticket_to_hosted">Show Radius Ticket to Hosted Guests</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <input type="checkbox" name="only_hosted_guests">
                                    <label for="only_hosted_guests">Only Hosted Guests</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <input type="checkbox" name="has_access_to_hotspot">
                                    <label for="has_access_to_hotspot">Has Access to Hotspot</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <input type="checkbox" name="add_other_option_on_gender">
                                    <label for="add_other_option_on_gender">Add Other option on Gender</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <input type="checkbox" name="remove_gender_field">
                                    <label for="remove_gender_field">Remove Gender field</label>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" id="configurationHotelId" name="id_hotel">
                        <input type="hidden" id="configurationHotelGuid" name="guid">
                        <input type="hidden" id="configurationProductId" name="product_id">
                        <input type="hidden" id="configurationBrandId" name="brand_id">

                        <input type="hidden" name="wifiDaysForm" value="1">
                        <div class="row">
                            <div class="col-lg-12">
                                <button type="submit" class="btn btn-success">Save configuration</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="autocheckin-modal" tabindex="-1" role="dialog" aria-labelledby="RequestRoomModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"> Autocheckin configuration</h4>
            </div>
            <div class="modal-body">
                <form method="POST" id="autocheckin-form" action="/lib/webservices/product-config-ws.php">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group autocheckin-data-fields">
                            </div>
                            <div class="form-group form-inline brand-config-fields">
                                <input id="autocheckin-color" type="text" value="" name="background_color" class="form-control color-input" data-huebee style="width: 6em;">
                                <label for="autocheckin-color">Background color</label>
                            </div>
                        </div>
                        <div class="autocheckin-fields">
                            <input id="autocheckin_product_id" name="product_id" value="<?php echo !empty($products) ? $products['autocheckin']['id'] : null; ?>" type="hidden">
                            <input id="autocheckin_brand_id" name="brand_id" value="" type="hidden">
                            <input id="autocheckin_hotel_id" name="hotel_id" value="" type="hidden">
                            <input id="autocheckin_product_name" name="product_name" value="autocheckin" type="hidden">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success">Save configuration</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="payments-modal" tabindex="-1" role="dialog" aria-labelledby="payments-modal">
    <div class="modal-dialog" role="document">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Payments configuration</h4>
            </div>

            <div class="modal-body">
                <div>
                    <ul class="nav nav-tabs" id="nav-tabs-payments"></ul>
                    <div class="tab-content" id="tab-content-payments">
                        <div class="row">
                            <div class="col-lg-12 text-center">
                                <div class="svg-container">
                                    <svg width="100" height="100" viewBox="0 0 38 38" xmlns="http://www.w3.org/2000/svg">
                                        <defs>
                                            <linearGradient x1="8.042%" y1="0%" x2="65.682%" y2="23.865%" id="a">
                                                <stop stop-color="#65c3df" stop-opacity="0" offset="0%"></stop>
                                                <stop stop-color="#65c3df" stop-opacity=".631" offset="63.146%"></stop>
                                                <stop stop-color="#65c3df" offset="100%"></stop>
                                            </linearGradient>
                                        </defs>
                                        <g fill="none" fill-rule="evenodd">
                                            <g transform="translate(1 1)">
                                                <path d="M36 18c0-9.94-8.06-18-18-18" id="Oval-2" stroke="url(#a)" stroke-width="2" transform="rotate(145.839 18 18)">
                                                    <animateTransform attributeName="transform" type="rotate" from="0 18 18" to="360 18 18" dur="0.9s" repeatCount="indefinite"></animateTransform>
                                                </path>
                                                <circle fill="#65c3df" cx="36" cy="18" r="1" transform="rotate(145.839 18 18)">
                                                    <animateTransform attributeName="transform" type="rotate" from="0 18 18" to="360 18 18" dur="0.9s" repeatCount="indefinite"></animateTransform>
                                                </circle>
                                            </g>
                                        </g>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

<?php if ($integrationEnabled ?? false) { ?>
    <!-- DATAMATCH -->
    <div class="modal fade" id="datamatchModal" tabindex="-1" role="dialog" aria-labelledby="datamatchModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"> Datamatch configuration</h4>
                </div>

                <div class="modal-body">
                    <div class="row" id="datamatchLoader">
                        <div class="col-lg-12 text-center">
                            <div class="svg-container"> <svg width="100" height="100" viewBox="0 0 38 38" xmlns="http://www.w3.org/2000/svg">
                                    <defs>
                                        <linearGradient x1="8.042%" y1="0%" x2="65.682%" y2="23.865%" id="a">
                                            <stop stop-color="#65c3df" stop-opacity="0" offset="0%"></stop>
                                            <stop stop-color="#65c3df" stop-opacity=".631" offset="63.146%"></stop>
                                            <stop stop-color="#65c3df" offset="100%"></stop>
                                        </linearGradient>
                                    </defs>
                                    <g fill="none" fill-rule="evenodd">
                                        <g transform="translate(1 1)">
                                            <path d="M36 18c0-9.94-8.06-18-18-18" id="Oval-2" stroke="url(#a)" stroke-width="2" transform="rotate(145.839 18 18)">
                                                <animateTransform attributeName="transform" type="rotate" from="0 18 18" to="360 18 18" dur="0.9s" repeatCount="indefinite"></animateTransform>
                                            </path>
                                            <circle fill="#65c3df" cx="36" cy="18" r="1" transform="rotate(145.839 18 18)">
                                                <animateTransform attributeName="transform" type="rotate" from="0 18 18" to="360 18 18" dur="0.9s" repeatCount="indefinite"></animateTransform>
                                            </circle>
                                        </g>
                                    </g>
                                </svg> </div>
                        </div>
                    </div>
                    <div class="row" id="datamatchForm">
                        <div class="col-lg-12">
                            <form method="POST">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div id="datamatch_frequency_select" class="form-group">
                                            <label for="datamatch_frequency">Frequency in days:</label>
                                            <select class="form-control" id="datamatch_frequency" name="datamatch_frequency">
                                                <?php foreach ($datamatch_frequencies as $dm_key => $dm_frequency) { ?>
                                                    <option value="<?php echo $dm_key ?>"><?php echo $dm_frequency ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <div id="datamatch_integration_config"> </div>
                                        </div>
                                    </div>
                                    <input type="hidden" id="datamatch_hotel_id" name="datamatch_hotel_id">
                                    <input type="hidden" id="datamatch_brand_id" name="datamatch_brand_id">
                                </div>
                                <button id="datamatch_submit_button" type="submit" class="btn btn-success">Save this configuration</button>
                            </form>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>

    <!-- INTEGRATION -->
    <div class="modal fade" id="integrationModal" tabindex="-1" role="dialog" aria-labelledby="integrationModal">
        <div class="modal-dialog" role="document">

            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"> PMS integration configuration</h4>
                </div>

                <div class="modal-body">
                    <div>
                        <ul class="nav nav-tabs" id="nav-tabs-pms"></ul>
                        <div class="tab-content" id="tab-content-pms">
                            <div class="row">
                                <div class="col-lg-12 text-center">
                                    <div class="svg-container">
                                        <svg width="100" height="100" viewBox="0 0 38 38" xmlns="http://www.w3.org/2000/svg">
                                            <defs>
                                                <linearGradient x1="8.042%" y1="0%" x2="65.682%" y2="23.865%" id="a">
                                                    <stop stop-color="#65c3df" stop-opacity="0" offset="0%"></stop>
                                                    <stop stop-color="#65c3df" stop-opacity=".631" offset="63.146%"></stop>
                                                    <stop stop-color="#65c3df" offset="100%"></stop>
                                                </linearGradient>
                                            </defs>
                                            <g fill="none" fill-rule="evenodd">
                                                <g transform="translate(1 1)">
                                                    <path d="M36 18c0-9.94-8.06-18-18-18" id="Oval-2" stroke="url(#a)" stroke-width="2" transform="rotate(145.839 18 18)">
                                                        <animateTransform attributeName="transform" type="rotate" from="0 18 18" to="360 18 18" dur="0.9s" repeatCount="indefinite"></animateTransform>
                                                    </path>
                                                    <circle fill="#65c3df" cx="36" cy="18" r="1" transform="rotate(145.839 18 18)">
                                                        <animateTransform attributeName="transform" type="rotate" from="0 18 18" to="360 18 18" dur="0.9s" repeatCount="indefinite"></animateTransform>
                                                    </circle>
                                                </g>
                                            </g>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <!-- PORTALPRO -->
    <div class="modal fade" id="portalProModal" tabindex="-1" role="dialog" aria-labelledby="portalProModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"> PortalPro configuration</h4>
                </div>
                <div class="modal-body">
                    <div class="row" id="portalProForm">
                        <div class="col-lg-12">
                            <form method="POST" id="portalProConfigForm" action="/lib/webservices/product-config-ws.php">
                                <div class="row" style="margin-left:2%">
                                    <h4 style="font-weight: bold;">PMS Validation</h4>
                                    <div id="pmsValidationWarningMessage"></div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <input type="checkbox" id="portalPro_name_surname" name="portalPro_name_surname" value="1">
                                            <label for="portalPro_name_surname">Guest name and surname</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <input type="checkbox" id="portalPro_document_id" name="portalPro_document_id" value="1">
                                            <label for="portalPro_document_id">Document number</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <input type="checkbox" id="portalPro_room_number" name="portalPro_room_number" value="1">
                                            <label for="portalPro_room_number">Room number</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="portalPro_max_validations">Max Validations </label>
                                            <input type="number" id="portalPro_max_validations" name="portalPro_max_validations" min="0" step="1" value="3">
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <input type="checkbox" id="portalPro_restrictive" name="portalPro_restrictive" value="1">
                                            <label for="portalPro_restrictive">Restrictive </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <hr style="margin-top: 15px; margin-bottom: 15px;">
                                        <h4 style="font-weight: bold;">AccessCodes validation</h4>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <input type="checkbox" id="portalPro_access_code" name="portalPro_access_code" value="1">
                                            <label for="portalPro_access_code">Access Code</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <input type="checkbox" id="portalPro_premium_access_code" name="portalPro_premium_access_code" value="1">
                                            <label for="portalPro_premium_access_code">Premium Access Code</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <hr style="margin-top: 15px; margin-bottom: 15px;">
                                        <h4 style="font-weight: bold;">RadiusTicket validation</h4>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <input type="checkbox" id="portalPro_radius_ticket" name="portalPro_radius_ticket" value="1">
                                            <label for="portalPro_radius_ticket">Radius ticket</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <input type="checkbox" id="portalPro_premium_ticket" name="portalPro_premium_ticket" value="1">
                                            <label for="portalPro_premium_ticket">Premium Ticket</label>
                                        </div>
                                    </div>

                                    <input type="hidden" id="portalPro_product_id" name="product_id">
                                    <input type="hidden" id="portalPro_hotel_id" name="hotel_id">
                                    <input type="hidden" id="portalPro_brand_id" name="brand_id">
                                    <input type="hidden" id="portalPro_product_name" name="product_name" value="portalPro">
                                </div>
                                <button id="portalPro_submit_button" type="submit" class="btn btn-success">Save this configuration</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- PORTAL REDIRECT -->
    <div class="modal fade" id="portalRedirectModal" tabindex="-1" role="dialog" aria-labelledby="portalRedirectModal">
        <div class="modal-dialog" role="document">

            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"> Redirect integration configuration</h4>
                </div>

                <div class="modal-body">
                    <div>
                        <ul class="nav nav-tabs" id="nav-tabs-redirect"></ul>
                        <div class="tab-content" id="tab-content-redirect">
                            <div class="row">
                                <div class="col-lg-12 text-center">
                                    <div class="svg-container">
                                        <svg width="100" height="100" viewBox="0 0 38 38" xmlns="http://www.w3.org/2000/svg">
                                            <defs>
                                                <linearGradient x1="8.042%" y1="0%" x2="65.682%" y2="23.865%" id="a">
                                                    <stop stop-color="#65c3df" stop-opacity="0" offset="0%"></stop>
                                                    <stop stop-color="#65c3df" stop-opacity=".631" offset="63.146%"></stop>
                                                    <stop stop-color="#65c3df" offset="100%"></stop>
                                                </linearGradient>
                                            </defs>
                                            <g fill="none" fill-rule="evenodd">
                                                <g transform="translate(1 1)">
                                                    <path d="M36 18c0-9.94-8.06-18-18-18" id="Oval-2" stroke="url(#a)" stroke-width="2" transform="rotate(145.839 18 18)">
                                                        <animateTransform attributeName="transform" type="rotate" from="0 18 18" to="360 18 18" dur="0.9s" repeatCount="indefinite"></animateTransform>
                                                    </path>
                                                    <circle fill="#65c3df" cx="36" cy="18" r="1" transform="rotate(145.839 18 18)">
                                                        <animateTransform attributeName="transform" type="rotate" from="0 18 18" to="360 18 18" dur="0.9s" repeatCount="indefinite"></animateTransform>
                                                    </circle>
                                                </g>
                                            </g>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
<?php } ?>

<link href="<?php echo DIR_CSS ?>bootstrap-colorpicker.min.css" rel="stylesheet" />
<script src="<?php echo DIR_JS ?>bootstrap-colorpicker.min.js" defer></script>
<script src="/<?php echo DIR_JS ?>relogin.js?v=3"></script>
<script src="<?php echo DIR_JS ?>feedback-errors.js"></script>
<script>
    <?php if ($integrationEnabled ?? false) { ?>
        // integration list with correspondent configs
        var datamatch_integrations_configs = <?php echo json_encode($datamatch_integrations_configs); ?>;
        var integrationConfigsList = <?php echo json_encode($integrationConfigs); ?>;
        var pms_integrations = <?php echo json_encode($pms_integrations); ?>;
        var api_integrations = <?php echo json_encode($api_integrations); ?>;
        var redirect_integrations = <?php echo json_encode($redirect_integrations); ?>;
        var survey_integrations = <?php echo json_encode($survey_integrations); ?>;
        var engine_integrations = <?php echo json_encode($engine_integrations); ?>;

        var integrations = <?php echo json_encode($integrations); ?>;
        var integration_schema;

        var hotelinking_integrations_url = "<?php echo INTEGRATIONS_URL_FRONT ?>";
        var hotelinking_integrations_token = "<?php echo INTEGRATIONS_TOKEN ?>";
    <?php } ?>
    var products = <?php echo json_encode($products) ?>;

    // Check pushtech credentials before activate the product
    function onClickPushtech(value, target, hotel_id, brand_id, activated, product_id) {
        if (target.parent().data('has-keys') == 1) {
            sendProductActivationRequest(hotel_id, product_id, brand_id, "pushtech", value)
        } else {
            target.prop("checked", false);
            alert('Es necesario añadir las credenciales antes de activar el servicio!')
        }
    }

    function sendProductActivationRequest(hotel_id, product_id, brand_id, product_name, active) {
        var anyCheckboxSelected = $('input[name="selectedBrands[]"]:checked').length > 0;

        if (anyCheckboxSelected) {
            var bulkResults = [];
            var brandIds = [];

            $('input[name="selectedBrands[]"]:checked').each(function() {
                brandIds.push($(this).data('brand-id'))
            })

            // Add the brand where the radio button was clicked in case user didn't check its checkbox
            if (!brandIds.includes(brand_id)) {
                brandIds.push(brand_id);
            }

            brandIds.forEach(function(brand_id) {
                var promise = performActivationDeactivation(hotel_id, product_id, brand_id, product_name, active).then(function(output) {
                    return {
                        brand_id: brand_id,
                        output: output
                    }
                }).catch(function(error) {
                    throw {
                        brand_id: brand_id,
                        error: error
                    };
                })

                bulkResults.push(promise);
            });

            Promise.allSettled(bulkResults).then(function(results) {
                handleBulkResults(results, active, product_name, product_id);
            })
        } else {
            performActivationDeactivation(hotel_id, product_id, brand_id, product_name, active).then(function(output) {
                handleSuccess(output, active, product_name, brand_id)
            }).catch(function(error) {
                handleError(error, active, product_name, product_id, brand_id)
            })
        }
    }

    function performActivationDeactivation(hotel_id, product_id, brand_id, product_name, active) {
        return new Promise(function(resolve, reject) {
            var productInput = $('input[name="hotel_' + brand_id + '_' + product_id + '"]');
            if (active === '1') {
                productInput.prop('value', '0');
                productInput.prop('checked', false);
            } else {
                productInput.prop('value', '1');
                productInput.prop('checked', true);
            }

            var formData = {
                hotel_id,
                product_id,
                brand_id,
                product_name,
                active
            };

            $.ajax({
                url: '/lib/webservices/activate-product-ws.php',
                type: 'POST',
                data: formData,
                async: true,
                dataType: 'json',
                success: function(output) {
                    resolve(output);
                },
                error: function(error) {
                    reject(error)
                }
            });
        })

    }

    function handleBulkResults(results, active, product_name, product_id) {
        var lang = '<?php echo $_SESSION["userLang"] ?>';
        var allSuccessful = results.every(result => result.status === "fulfilled");
        if (allSuccessful) {
            var successfulBrandIds = [];

            results.forEach(function(result) {
                successfulBrandIds.push(result.value.brand_id);
            });

            handleSuccess(results[0].value.output, active, product_name, successfulBrandIds)
        } else {
            var unsuccessfulBrands = results.filter(result => result.status !== "fulfilled");
            var unsuccessfulBrandIds = unsuccessfulBrands.map(brand => brand.reason.brand_id)
            var error = unsuccessfulBrands[0].reason.error

            handleError(error, active, product_name, product_id, unsuccessfulBrandIds)

        }
    }

    function handleSuccess(output, active, product_name, brand_id) {
        var lang = '<?php echo $_SESSION["userLang"] ?>';
        var successForm = {
            nError: output.messageCode,
            lang,
            active,
            product_name,
            brand_id
        }
        sendFeedback(successForm)
    }

    function handleError(error, active, product_name, product_id, brand_id) {
        var lang = '<?php echo $_SESSION["userLang"] ?>';
        var errorCode = JSON.parse(error.responseText);
        var errorForm = {
            nError: errorCode,
            lang,
            active,
            product_name,
            brand_id
        };

        if (Array.isArray(brand_id)) {
            brand_id.forEach(function(brandId) {
                updateFailedInput(brandId, product_id, active)
            })
        } else {
            updateFailedInput(brand_id, product_id, active)
        }

        sendFeedback(errorForm)
    }

    function sendFeedback(form) {
        $.ajax({
            url: "/lib/webservices/msgFeedback.php",
            data: form,
            type: 'POST',
            dataType: 'json',
            success: function(output) {
                showError(output);
            }
        });
    }

    function updateFailedInput(brand_id, product_id, active) {
        var productInput = $('input[name="hotel_' + brand_id + '_' + product_id + '"]');
        productInput.prop('value', active);
        if (active === '1') {
            productInput.prop('checked', true);
        } else {
            productInput.removeAttr('checked');
        }
    }

    // Function to perform the search
    var searchText = '';

    function onSearchHotel() {
        // Get the searchInput text
        searchText = $('#searchInput').val().toLowerCase().trim();
        // Send the request
        window.location.assign("private/private-invitar-hotel/?search=" + searchText);
    }

    // Function to get the search param from the queryParams
    function getSearchParamsFromUrl() {
        var urlParams = new URLSearchParams(window.location.search);
        var entries = urlParams.entries();
        for (pair of entries) {
            if (pair[0] == 'search') {
                searchText = pair[1];
            }
        }
        $('#searchInput').val(searchText);
    }

    // Check if Integration is enabled
    function canPortalProBeEnabled(target, btnChecked, integrationEnabled) {
        if (!integrationEnabled && !btnChecked) {
            alert("Its necessary to create a Integration before activate the Portal Pro!\n\nPlease, check the integrations.");
            target.checked = false
            return false
        } else {
            return true
        }
    }

    // Check if the redirect integration is enabled before active the product
    function canPortalRedirectBeEnabled(target, integrationEnabled) {
        if (!integrationEnabled) {
            alert("Its necessary to create or activate the integration before activate the Portal Redirect! \n\nPlease, click on edit.")
            target.checked = false;
            return false
        } else {
            return true
        }
    }

    //to add a checkbox in a specific order
    function insertCheckboxInOrder(targetHtml, searchString, htmlToInsert) {
        if (targetHtml.includes(searchString)) {
            const regex = new RegExp('<div><input name="' + searchString + '" id="autocheckin-' + searchString + '".*?<\/div>');
            const match = targetHtml.match(regex);
            if (match) {
                const targetHtmlWithSearchString = match[0];
                targetHtml = targetHtml.replace(targetHtmlWithSearchString, targetHtmlWithSearchString + htmlToInsert);
            }
        } else {
            targetHtml += htmlToInsert;
        }
        return targetHtml;
    }

    function selectAllCheckboxes() {
        var selectAllCheckbox = document.getElementById('selectAllBrandsCheckbox');
        var checkboxes = document.querySelectorAll('.brandCheckbox');
        checkboxes.forEach(function(checkbox) {
            checkbox.checked = selectAllCheckbox.checked;
        });
    }

    function getSelectedBrandValues() {
        var selectedValues = [];
        $('input[name="selectedBrands[]"]:checked').each(function() {
            var brandId = $(this).data('brand-id')
            var hotelId = $(this).data('hotel-id');
            selectedValues.push({
                brandId: brandId,
                hotelId: hotelId
            });
        });
        return selectedValues;
    }

     // Disable second surname required for spanish if second surname is required
     function handleSecondSurnameSpanishDependency() {
        var secondSurnameParent = $('div[data-group="validate_data_scan"]').has('input[name="name"][value="second_surname"]');

        if(secondSurnameParent.length){
            var isRequiredChecked = secondSurnameParent.find('input[name="required"]').is(':checked');
            var secondSurnameRequiredForSpanish = $('input[name="second_surname_required_for_spanish"]');
            secondSurnameRequiredForSpanish.prop('disabled', isRequiredChecked);
        }
    }

    // Disable required for second surname input if second surname required for Spanish is activated
    function handleRequiredSecondSurnameDependency() {
        var secondSurnameRequiredForSpanish = $('input[name="second_surname_required_for_spanish"]');
        var secondSurnameParent = $('div[data-group="validate_data_scan"]').has('input[name="name"][value="second_surname"]');

        if(secondSurnameParent.length){
            var isSecondSurnameRequiredForSpanishChecked = secondSurnameRequiredForSpanish.is(':checked');
            var requiredInput = secondSurnameParent.find('input[name="required"]');
            requiredInput.prop('disabled', isSecondSurnameRequiredForSpanishChecked);
        }
    }


    $(document).ready(function() {
        // Send request to run the user sync
        var syncUsesForm = $('#synchronize_users');
        syncUsesForm.submit(function(e) {
            // Set brand_id and disable button to avoid multiple clicks
            var brand_id = $('#synchronize_brand_id').val();
            var integration_brand_id = $('#synchronize_pushtech_brand_id').val();
            $('#synchronize_users_button').prop('disabled', true);

            $.ajax({
                type: 'POST',
                url: `${hotelinking_integrations_url}brands/${brand_id}/integrations/${integration_brand_id}/synchronize-users`,
                headers: {
                    'Authorization': hotelinking_integrations_token,
                    'Content-Type': "application/json",
                },
                data: syncUsesForm.serialize(),
                success: function(response, status, xhr) {
                    $('#synchronize_users_button').removeClass("btn-info");
                    $('#synchronize_users_button').addClass("btn-success");
                    $('#synchronize_users_response').html('Request done succesful');
                },
                error: function(jqxhr, textStatus, errorThrown) {
                    console.error(textStatus, errorThrown)
                    $('#synchronize_users_button').removeClass("btn-info");
                    $('#synchronize_users_button').addClass("btn-danger");
                    $('#synchronize_users_response').html('Error..');
                }
            });
            e.preventDefault();
        });

        // Get searchInput from url
        getSearchParamsFromUrl();

        // Perform search on enter press
        $('#searchInput').keyup(function(event) {
            if (event.keyCode == 13) {
                onSearchHotel();
            }
        });

        $('input[name=afName]').mouseover(function() {
            $(this).popover('show');
        });
        $('input[name=afName]').mouseout(function() {
            $(this).popover('hide');
        });

        $('input[name=pushtech]').mouseover(function() {
            $(this).parent().popover('show');
        });
        $('input[name=pushtech]').mouseout(function() {
            $(this).parent().popover('hide');
        });

        //update id hotel on click
        $(".pushtech_edit_button").click(function() {
            $('#pushtech_id_hotel').val($(this).data('idhotel'));
            $('#pushtech_account_id').val($(this).data('key'));
            $('#pushtech_account_secret').val($(this).data('secret'));
            $('#pushtech_brand_id').val($(this).data('brand-id'));
            $('#synchronize_brand_id').val($(this).data('brand-id'));
            $('#synchronize_pushtech_brand_id').val($(this).data('pushtech-brand-id'));

            //When open modal put synchronize button active
            $('#synchronize_users_button').removeClass('btn-success');
            $('#synchronize_users_button').removeClass('btn-danger');

            $('#synchronize_users_button').addClass('btn-info');
            $('#synchronize_users_button').prop('disabled', false);
            $('#synchronize_users_response').html('');
        });

        $(".widget_edit_button").click(function() {
            $('#widget_hotel_id').val($(this).data('idhotel'));
            $('#widget_product_id').val($(this).data('product-id'));
            $('#widget_brand_id').val($(this).data('brand-id'));
            $('#widget_product_name').val($(this).data('prudct-name'));
        });

        $(".portal_configuration_edit_button").click(function() {
            $("#portalConfigurationLoader").fadeIn();
            $("#portalConfigurationForm").hide();
            $('[name="bypass_active"]').prop('checked', false);
            $('[name="hide_unsubscribed_clients"]').prop('checked', false);
            $('[name="phone_active"]').prop('checked', false);
            $('[name="url_redirect').prop('value', '');
            $('[name="show_radius_ticket_to_hosted"]').prop('checked', false);
            $('[name="only_hosted_guests"]').prop('checked', false);
            $('[name="commercial_profile"]').prop('checked', false);
            $('[name="has_access_to_hotspot"]').prop('checked', false);
            $('[name="add_other_option_on_gender"]').prop('checked', false);
            $('[name="remove_gender_field"]').prop('checked', false);


            $('[name="phone_active"]').prop('checked', $(this).data('phone-active'));
            $('[name="bypass_active"]').prop('checked', $(this).data('bypass-active'));
            $('[name="url_redirect').prop('value', $(this).data('url-redirect'));
            $('[name="show_radius_ticket_to_hosted"]').prop('checked', $(this).data('show-radius-ticket-to-hosted'));
            $('[name="only_hosted_guests"]').prop('checked', $(this).data('only-hosted-guests'));
            $('[name="commercial_profile"]').prop('checked', $(this).data('commercial-profile'));
            $('[name="has_access_to_hotspot"]').prop('checked', $(this).data('has-access-to-hotspot'));
            $('[name="add_other_option_on_gender"]').prop('checked', $(this).data('add-other-option-on-gender'));
            $('[name="remove_gender_field"]').prop('checked', $(this).data('remove-gender-field'));


            $('#configurationHotelId').val($(this).data('idhotel'));
            $('#configurationHotelGuid').val($(this).data('hotel-guid'));
            $('#configurationProductId').val($(this).data('product-id'));
            $('#configurationBrandId').val($(this).data('brand-id'));



            $.get('/lib/webservices/product-config-ws.php', {
                'brand_id': $(this).data('brand-id'),
                'product_id': $(this).data('product-id')
            }).done(function(response) {
                if (!response.config) {
                    $('[name="hide_unsubscribed_clients"]').prop('checked', false);
                    $('[name="phone_active"]').prop('checked', false);
                    $('[name="url_redirect').prop('value', '');
                    $('[name="show_radius_ticket_to_hosted"]').prop('checked', false);
                    $('[name="only_hosted_guests"]').prop('checked', false);
                    $('[name="commercial_profile"]').prop('checked', false);
                    $('[name="has_access_to_hotspot"]').prop('checked', false);
                    $('[name="add_other_option_on_gender"]').prop('checked', false);
                    $('[name="remove_gender_field"]').prop('checked', false);

                } else {
                    $('[name="hide_unsubscribed_clients"]').prop('checked', response.config.hide_unsubscribed_clients || false);
                    $('[name="phone_active"]').prop('checked', response.config.phone_active || false);
                    $('[name="url_redirect').prop('value', response.config.url_redirect || '');
                    $('[name="show_radius_ticket_to_hosted"]').prop('checked', response.config.show_radius_ticket_to_hosted || false);
                    $('[name="only_hosted_guests"]').prop('checked', response.config.only_hosted_guests || false);
                    $('[name="commercial_profile"]').prop('checked', response.config.commercial_profile || false);
                    $('[name="has_access_to_hotspot"]').prop('checked', response.config.has_access_to_hotspot || false);
                    $('[name="add_other_option_on_gender"]').prop('checked', response.config.add_other_option_on_gender || false);
                    $('[name="remove_gender_field"]').prop('checked', response.config.remove_gender_field || false);

                }

                $("#portalConfigurationLoader").hide();
                $("#portalConfigurationForm").fadeIn();

            });
        });

        $(".request_room_edit_button").click(function() {
            $('#id_hotel_display_room_solicitude').val($(this).data('idhotel'));
            $('#id_brand_display_room_solicitude').val($(this).data('idbrand'));
            if ($(this).data('display-request') == 1) {
                $('#display_room_solicitude').prop('checked', true);
            } else {
                $('#display_room_solicitude').prop('checked', false);
            }
        });

        $(".PMS_eddit_button").click(function() {
            $('#id_hotel_PMS_validation').val($(this).data('idhotel'));
            var element = document.getElementById('PMS_integrations');
            element.value = $(this).data('pmsintegration');
        });

        var disableScanConfig = ["advanced_scan",
            "optional_scan",
            "custom_scan_text",
            "identity_document_signature_required",
            "send_identity_documents_to_reception",
            "send_identity_documents_to_PMS",
            "scan_children_like_adults"
        ];

        $(".autocheckin-option").click(function(e) {
            e.preventDefault();
            var selected = $(this).parent().find('input[type="radio"]').prop('checked');
            $("#autocheckin-modal .modal-dialog").width("75vw");

            var html = '';
            let disableSendDocumentsPageHtml = '';
            var identification = '';
            var brandId = $(this).data('brand-id')
            var integrationBrandId = $(this).data('integration-brand-id');
            var portalRedirectId = products['portal_redirect'].id
            var hasPortalRedirectActivated = $('[name="' + portalRedirectId + '"]').val();

            $('#autocheckin_brand_id').val($(this).data('brand-id'));
            $('#autocheckin_hotel_id').val($(this).data('hotel-id'));
            $('#autocheckin-form .autocheckin-data-fields').html('');

            $.get('/lib/webservices/product-config-ws.php', {
                'brand_id': $(this).data('brand-id'),
                'product_id': $(this).data('product-id')
            }).done(function(response) {
                $(".autocheckin-data-fields").on("click", ".add-reservation-option", function(e) {
                    e.preventDefault();
                    e.stopImmediatePropagation();
                    let optionsCount = $(".reservation_inputs_option").length;
                    let reservationOption = $(`#reservation_inputs_${optionsCount - 1}`).get(0).outerHTML;
                    reservationOption = reservationOption.replaceAll(`data-group="${optionsCount - 1}"`, `data-group="${optionsCount}"`);
                    reservationOption = reservationOption.replaceAll(`id="reservation_inputs_${optionsCount - 1}"`, `id="reservation_inputs_${optionsCount}"`);
                    $(".res_inputs_options").append(reservationOption);
                });

                $(".autocheckin-data-fields").on("click", ".delete-reservation-option", function(e) {
                    e.preventDefault();
                    e.stopImmediatePropagation();
                    let optionsCount = $(".reservation_inputs_option").length;
                    if (optionsCount >= 2) {
                        $(`#reservation_inputs_${optionsCount - 1}`).remove();
                    }
                });

                $(".autocheckin-data-fields").on("click", ".autocheckin-required-values", function(e) {
                    e.preventDefault();
                    e.stopImmediatePropagation();
                    $(".autocheckin-required-values-wrapper").append('<p id="autocheckin-required-values-searching">Searching...</p>');
                    $.get('/lib/webservices/integrations-ws.php', {
                        'brand_id': brandId,
                        'integration_brand_id': integrationBrandId,
                        'action_method': 'requiredValues'
                    }).done(function(response) {
                        $("#autocheckin-required-values-searching").remove();
                        if (response.data.length) {
                            $(".autocheckin-required-values-wrapper")
                                .append('<ul class="autocheckin-required-values-list"></ul>');

                            response.data.forEach(function(value) {
                                $(".autocheckin-required-values-list").append("<li>" + value + "</li>")
                            });
                        } else {

                            $(".autocheckin-required-values-wrapper")
                                .append('<p>No required values found</p>');
                        }

                    });
                });

                var title;

                for (var i in response.data) {
                    if (i == 'active') {
                        continue;
                    }

                    title = i[0].toUpperCase() + i.replace(/_/g, ' ').substring(1);

                    title == 'Telephone' ? title = 'Telephone request form' : title;
                    switch (typeof response.data[i]) {
                        case 'object':
                            var types = ['number', 'text', 'date', 'email', 'select', 'autocomplete', 'phone'];
                            var data = response.data[i];
                            var subtitle;

                            identification += '<div id="autocheckin-identification"><div><label>' + title + '</label></div>';
                            // structure is like this: {key: values, key_1: values}
                            for (var objectKeyName in data) {

                                subtitle = objectKeyName[0].toUpperCase() + objectKeyName.replace(/_/g, ' ').substring(1);
                                var active = objectKeyName == "validate_data_scan" || objectKeyName == "reservation_inputs" || objectKeyName == "child_form" ? "<div class=\"col-sm-1\"><strong>Active</strong></div>" : ""
                                var required = objectKeyName == "validate_data_scan" || objectKeyName == "child_form" ? "<div class=\"col-sm-1\"><strong>Required</strong></div>" : ""
                                var fillFromHolder = objectKeyName == "validate_data_scan" || objectKeyName == "child_form" ? "<div class=\"col-sm-1\"><strong>Fill from Holder</strong></div>" : ""
                                var hideFilled = objectKeyName == "child_form" ? "<div class=\"col-sm-1\"><strong>Hide Filled</strong></div>" : ""
                                var notFillOnReception = (objectKeyName == "validate_data_scan" || objectKeyName == "child_form") ? '<div class="col-sm-1"><strong>Not Fill On Reception</strong></div>' : '';

                                if (objectKeyName == "reservation_inputs") {
                                    identification += '<div style="margin-left: 1em;" class="res_inputs_options"><div class="row mt2"><label class="col-sm-4">' + subtitle + '</label>' + '</div>';
                                    identification += '<div class="res_inputs_actions"><div class="row mt2"><label class="col-sm-4">' + 'Actions' + '</label>' + '</div>';
                                    identification += '<div><a href="#" class="add-reservation-option">+ Add option</a></div>';
                                    identification += '<div><a href="#" class="delete-reservation-option">- Delete option</a></div></div>';
                                } else if (objectKeyName == "validate_data_scan") {
                                    identification += '<div style="margin-left: 1em;" class="' + objectKeyName + '"><div class="mt2"><label>' + subtitle + '</label></div>';

                                    identification += '<div class="autocheckin-required-values-wrapper"><a href="#" class="btn btn-primary autocheckin-required-values">Show required values</a></div>';
                                } else {
                                    identification += '<div style="margin-left: 1em;" class="' + objectKeyName + '"><div class="mt2"><label>' + subtitle + '</label></div>';
                                }
                                if (objectKeyName === "reservation_inputs") {
                                    identification += '<div class="row mt2">' + active + required + ' <div class="col-sm-3"><strong>Name</strong></div><div class="col-sm-2"><strong>Type</strong></div><div class="col-sm-2"><strong>Position</strong></div><div class="col-sm-2"><strong>Min Length</strong></div><div class="col-sm-2"><strong>Max Length</strong></div></div>';
                                } else if (objectKeyName == "child_form") {
                                    identification += '<div class="row mt2">' + active + required + fillFromHolder + hideFilled + notFillOnReception + ' <div class="col-sm-1"><strong>Name</strong></div><div class="col-sm-2"><strong>Type</strong></div><div class="col-sm-1"><strong>Min Length</strong></div><div class="col-sm-1"><strong>Max Length</strong></div></div>';
                                } else {
                                    var spacing = objectKeyName === "validate_data_scan" ? 2 : 3;
                                    identification += '<div class="row mt2">' + active + required + fillFromHolder + notFillOnReception + ' <div class="col-sm-' + spacing + '"><strong>Name</strong></div><div class="col-sm-2"><strong>Type</strong></div><div class="col-sm-2"><strong>Min Length</strong></div><div class="col-sm-2"><strong>Max Length</strong></div></div>';
                                }
                                identification += data[objectKeyName].map(function(items, index) {
                                    var typesHtml = '';
                                    if (objectKeyName == "reservation_inputs") {
                                        typesHtml += '<div class="' + objectKeyName + '" id="' + objectKeyName + '_' + index + '"><hr>';
                                        typesHtml += '<div class="mt2 ' + objectKeyName + '_option">'
                                    }

                                    for (var item in items) {

                                        var options = '<div class="col-sm-2 type-select"><select name="type" class="input form-control" data-group="' + index + '">';
                                        options += types.map(function(type) {
                                            var selected = items[item].type == type ? 'selected="selected"' : '';
                                            return '<option value="' + type + '" ' + selected + '>' + type + '</option>';
                                        }).join('');

                                        options += '</select></div>';

                                        if (objectKeyName === "reservation_inputs") {
                                            var positionOptions = '<div class="col-sm-2 type-select"><select name="position" class="input form-control" data-group="' + index + '">';
                                            positionOptions += items.map(function(type, index) {
                                                var selected = items[item].position == (index + 1) ? 'selected="selected"' : '';
                                                return '<option value="' + (index + 1) + '" ' + selected + '>' + (index + 1) + '</option>';
                                            }).join('');
                                            positionOptions += '</select>';
                                        }

                                        typesHtml += '<div class="row mt2" data-group="' + objectKeyName + '">';

                                        //checks if CCAA config is active or not in order to enable/disable province config
                                        var ccaaActive = false;

                                        if(items[item].name === "province" && response.data.identification.validate_data_scan){
                                            var ccaaItem = response.data.identification.validate_data_scan[0].find(item => item.name === "CCAA");
                                            if (ccaaItem) {
                                                ccaaActive = ccaaItem.active === 'true';
                                            }
                                        }

                                        if (objectKeyName == "validate_data_scan" || objectKeyName == "reservation_inputs" || objectKeyName == "child_form") {
                                            var checked = items[item].active == 'true' ? 'checked="checked"' : '';

                                            var activeDisabled = items[item].name == 'birthday' ? 'disabled="disabled"' : '';

                                            if (items[item].name === "province" && !ccaaActive) {
                                                checked = '';
                                                activeDisabled = 'disabled="disabled"';
                                            }

                                            typesHtml += '<div class="col-sm-1"><input style="vertical-align:middle" class="input" name="active" type="checkbox" data-group="' + index + '" ' + checked + activeDisabled + '></div>';
                                        }

                                        if (objectKeyName == "validate_data_scan" || objectKeyName == "child_form") {
                                            var requiredChecked = items[item].required == 'true' ? 'checked="checked"' : '';

                                            var requiredDisabled = items[item].name == 'birthday' ? 'disabled="disabled"' : '';

                                            if (items[item].name === "province" && !ccaaActive) {
                                                requiredChecked = '';
                                                requiredDisabled = 'disabled="disabled"';
                                            }

                                            typesHtml += '<div class="col-sm-1"><input style="vertical-align:middle" class="input" name="required" type="checkbox" data-group="' + index + '" ' + requiredChecked + requiredDisabled + '></div>';
                                        }

                                        if (objectKeyName === "child_form") {
                                            var fillFromHolderChecked = items[item].fill_from_holder == 'true' ? 'checked="checked"' : '';
                                            var hideFilledChecked = items[item].hide_filled == 'true' ? 'checked="checked"' : '';
                                            var notFillReceptionChecked = items[item].not_fill_on_reception == 'true' ? 'checked="checked"' : '';
                                            typesHtml += '<div class="col-sm-1"><input style="vertical-align:middle" class="input" name="fill_from_holder" type="checkbox" data-group="' + index + '" ' + fillFromHolderChecked + '></div>';
                                            typesHtml += '<div class="col-sm-1"><input style="vertical-align:middle" class="input" name="hide_filled" type="checkbox" data-group="' + index + '" ' + hideFilledChecked + '></div>';
                                            typesHtml += '<div class="col-sm-1"><input style="vertical-align:middle" class="input" name="not_fill_on_reception" type="checkbox" data-group="' + index + '" ' + notFillReceptionChecked + '></div>';
                                            if (objectKeyName == "child_form") {
                                                typesHtml += '<div class="col-sm-1"><input class="input form-control" name="name" type="text" value="' + items[item].name + '" data-group="' + index + '"></div>';
                                            } else {
                                                typesHtml += '<div class="col-sm-2"><input class="input form-control" name="name" type="text" value="' + items[item].name + '" data-group="' + index + '"></div>';
                                            }
                                            typesHtml += options;
                                        } else if (objectKeyName === "validate_data_scan") {
                                            var fillFromHolderChecked = items[item].fill_from_holder == 'true' ? 'checked="checked"' : '';
                                            var notFillReceptionChecked = items[item].not_fill_on_reception == 'true' ? 'checked="checked"' : '';

                                            var fillFromHolderDisabled = '';

                                            if (items[item].name === "province" && !ccaaActive) {
                                                fillFromHolderChecked = '';
                                                fillFromHolderDisabled = 'disabled="disabled"';
                                            }
                                            
                                            typesHtml += '<div class="col-sm-1"><input style="vertical-align:middle" class="input" name="fill_from_holder" type="checkbox" data-group="' + index + '" ' + fillFromHolderChecked + fillFromHolderDisabled + '></div>';
                                            typesHtml += '<div class="col-sm-1"><input style="vertical-align:middle" class="input" name="not_fill_on_reception" type="checkbox" data-group="' + index + '" ' + notFillReceptionChecked + '></div>';
                                            typesHtml += '<div class="col-sm-2"><input class="input form-control" name="name" type="text" value="' + items[item].name + '" data-group="' + index + '"></div>';
                                            typesHtml += options;
                                        } else {
                                            typesHtml += '<div class="col-sm-3"><input class="input form-control" name="name" type="text" value="' + items[item].name + '" data-group="' + index + '"></div>';
                                            typesHtml += options;
                                        }


                                        var disabled = {
                                            'maxmin': (items[item].type == 'text') ? 'block' : 'none',
                                            'select': (items[item].type == 'select' || items[item].type == 'autocomplete') ? 'block' : 'none'

                                        };
                                        var minLengthValue = items[item].minLength || '';
                                        var maxLengthValue = items[item].maxLength || '';
                                        var positionValue = items[item].position || '';
                                        var select = JSON.stringify((items[item].options || []));

                                        if (objectKeyName === "reservation_inputs") {
                                            typesHtml += '<div style="display: block" data-group="' + objectKeyName + '" class="lengths">';
                                            typesHtml += positionOptions;
                                            typesHtml += '</div>';
                                        }
                                        if (objectKeyName !== "reservation_inputs") {
                                            typesHtml += '<div style="display: ' + disabled.maxmin + '" data-group="' + objectKeyName + '" class="lengths">';
                                        }
                                        if (objectKeyName == "child_form") {
                                            typesHtml += '<div class="col-sm-1"><input style="width:100%" class="input form-control" name="minLength" type="number" value="' + minLengthValue + '" data-group="' + index + '" placeholder="minLengthValue "></div>';
                                            typesHtml += '<div class="col-sm-1"><input style="width:100%" class="input form-control" name="maxLength" type="number" value="' + maxLengthValue + '" data-group="' + index + '" placeholder="maxLengthValue "></div>';
                                        } else {
                                            typesHtml += '<div class="col-sm-2"><input style="width:100%" class="input form-control" name="minLength" type="number" value="' + minLengthValue + '" data-group="' + index + '" placeholder="minLengthValue "></div>';
                                            typesHtml += '<div class="col-sm-2"><input style="width:100%" class="input form-control" name="maxLength" type="number" value="' + maxLengthValue + '" data-group="' + index + '" placeholder="maxLengthValue "></div>';
                                        }

                                        typesHtml += '</div>';

                                        typesHtml += '<div style="display: ' + disabled.select + '" data-group="' + objectKeyName + '" class="select col-sm-4">';
                                        typesHtml += '<textarea style="width:100%" class="input form-control" name="select" data-group="' + index + '">' + select + '</textarea>';
                                        typesHtml += '</div></div>';

                                    }

                                    if (objectKeyName == "reservation_inputs") {
                                        typesHtml += '</div></div>';
                                    }

                                    return typesHtml;
                                }).join('<i>OR</i>');
                                identification += '</div>';
                            }
                            identification += '</div><hr>';
                            break;

                        case 'boolean':
                            var checked = response.data[i] ? 'checked="checked"' : '';
                            var disabled = '';
                            var disableScanActive = response.data["disable_scan"];
                            var disableAddressAutocomplete = response.data["disable_address_autocomplete"];
                            var disableOnlyAddressAutocomplete = response.data["disable_only_address_autocomplete"];
                            var disabledInputs = {
                                redirect_link: () => !Number(hasPortalRedirectActivated),
                                disable_send_documents_page: () => {
                                    if (!response.data["signed_documents"]) {
                                        checked = '';
                                        return true;
                                    }
                                    return false;
                                },
                                // Scan related inputs
                                disable_scan: () => {
                                    return Object.entries(response.data).some(([key, value]) => disableScanConfig.includes(key) && value);
                                },
                                advanced_scan: () => disableScanActive,
                                optional_scan: () => disableScanActive,
                                identity_document_signature_required: () => disableScanActive,
                                send_identity_documents_to_reception: () => disableScanActive,
                                send_identity_documents_to_PMS: () => disableScanActive,
                                scan_children_like_adults: () => disableScanActive,
                                disable_address_autocomplete: () => disableOnlyAddressAutocomplete,
                                disable_only_address_autocomplete: () => disableAddressAutocomplete,

                            }

                            if (disabledInputs[i]) {
                                disabled = disabledInputs[i]() ? "disabled" : '';
                            }
                            //save scan_on_reception html to add it later, below reception_signature. Same with disable_send_documents_page , to add it below signed_documents
                            if (i === "disable_send_documents_page") {
                                checkboxHtml = '<div><input name="' + i + '" id="autocheckin-' + i + '"type="checkbox" value="1" ' + disabled + ' ' + checked + '> <label for="autocheckin-' + i + '">' + title + '</label></div>';
                                disableSendDocumentsPageHtml = checkboxHtml
                            } else if (i.substring(0, 7) !== "custom_") {
                                html += '<div><input name="' + i + '" id="autocheckin-' + i + '"type="checkbox" value="1" ' + disabled + ' ' + checked + '> <label for="autocheckin-' + i + '">' + title + '</label></div>';
                            }
                            break;
                        default:
                            if (title === "Token key") {
                                html += '<div class="form-group form-inline "><label>' + title + '</label><input class="form-control" id="tokenKeyInput" name="' + i + '" type="text" style="width: 10em;margin-left:0.5em;margin-right:0.5em;" value="' + response.data[i] + '"><button id="generateRandomTokenButton" class="btn btn-success">Generate random</button></div>';
                            } else {
                                html += '<div class="form-group form-inline "><input class="form-control" name="' + i + '" type="text" style="width: 3em;" value="' + response.data[i] + '"> <label>' + title + '</label></div>';
                            }
                            break;
                    }
                }

                // adding scan_on_reception and disable_send_documents_page html in order
                html = insertCheckboxInOrder(html, "signed_documents", disableSendDocumentsPageHtml);

                html += '<div class="mt2"><label>Customized texts</label></div>'
                for (var i in response.data) {
                    title = i[0].toUpperCase() + i.replace(/_/g, ' ').substring(1);
                    var checked = response.data[i] ? 'checked="checked"' : '';

                    if (i.substring(0, 7) === "custom_") {
                        html += '<div><input name="' + i + '" id="autocheckin-' + i + '"type="checkbox" value="1" ' + ' ' + checked + '> <label for="autocheckin-' + i + '">' + title + '</label></div>';
                    }
                }

                html = '<div id="autocheckin-fields">' + html + '</div>';

                $('#autocheckin-form .autocheckin-data-fields').html(identification + html);
                $('#autocheckin-color').val(response.background_color);

                handleSecondSurnameSpanishDependency()
                handleRequiredSecondSurnameDependency()


                // check if disable_scan is active, after custom_scan_text is generated, to disable it
                if (response.data && response.data["disable_scan"] || false) {
                    $('#autocheckin-custom_scan_text').prop('disabled', true);
                }
            }).fail(function(response) {
                try {
                    $('#autocheckin-form').html(response.responseJSON.errors);
                } catch (e) {
                    $('#autocheckin-form').html(e.message);
                }
            });


        });

        //In order to allow disable_send_documents_page config, signed_documents must be enabled
        $('body').on('click', '#autocheckin-signed_documents', function(e) {
            $('#autocheckin-disable_send_documents_page').attr('disabled', !e.target.checked);
            if (!e.target.checked) $('#autocheckin-disable_send_documents_page').attr('checked', false);
        })

        //Disable province config y CCAA is disabled
        $('body').on('change', 'div[data-group="validate_data_scan"]', function() {
            var ccaaInput = $(this).find('input[name="name"][value="CCAA"]');

            if (ccaaInput.length) {
                var activeCheckbox = $(this).find('input[name="active"]');

                var isActiveChecked = activeCheckbox.is(':checked');

                var provinceActiveCheckbox = $('div[data-group="validate_data_scan"]').has('input[name="name"][value="province"]').find('input[name="active"]');
                var provinceRequiredCheckbox = $('div[data-group="validate_data_scan"]').has('input[name="name"][value="province"]').find('input[name="required"]');
                var provinceFillFromHolderCheckbox = $('div[data-group="validate_data_scan"]').has('input[name="name"][value="province"]').find('input[name="fill_from_holder"]');

                if (!isActiveChecked) {
                    provinceActiveCheckbox.prop('disabled', true).prop('checked', false);
                    provinceRequiredCheckbox.prop('disabled', true).prop('checked', false);
                    provinceFillFromHolderCheckbox.prop('disabled', true).prop('checked', false);
                } else {
                    provinceActiveCheckbox.prop('disabled', false);
                    provinceRequiredCheckbox.prop('disabled', false);
                    provinceFillFromHolderCheckbox.prop('disabled', false);
                }

            }
        });

        //Disable second surname required for spanish if second surname is required
        $('body').on('change', 'div[data-group="validate_data_scan"] input[name="required"]', function() {
            handleSecondSurnameSpanishDependency()
        })

        //Disable second surname required input if second surname for spanish is checked
        $('body').on('change', 'input[name="second_surname_required_for_spanish"]', function () {
            handleRequiredSecondSurnameDependency();
        });

        // Disable 'disable_only_address_autocomplete' checkbox when 'disable_address_autocomplete' is checked
        // Disable 'disable_address_autocomplete' checkbox when 'disable_only_address_autocomplete' is checked
        $('body').on('click', '#autocheckin-disable_address_autocomplete, #autocheckin-disable_only_address_autocomplete', function () {
            
            const disableGeneral = $('#autocheckin-disable_address_autocomplete').prop('checked');
            const disableOnly = $('#autocheckin-disable_only_address_autocomplete').prop('checked');

            $('#autocheckin-disable_only_address_autocomplete').prop('disabled', disableGeneral);
            $('#autocheckin-disable_address_autocomplete').prop('disabled', disableOnly);
        });

        //If any of the following options get checked, disabled_scan will be disabled
        $('body').on('click', '#autocheckin-advanced_scan, #autocheckin-optional_scan, #autocheckin-custom_scan_text, #autocheckin-identity_document_signature_required, #autocheckin-send_identity_documents_to_reception, #autocheckin-send_identity_documents_to_PMS, #autocheckin-scan_children_like_adults', function() {
            var scanActive = false;
            disableScanConfig.some(function(scanConfig) {
                if ($('#autocheckin-' + scanConfig).prop('checked')) {
                    scanActive = true;
                    $('#autocheckin-disable_scan').prop('disabled', scanActive);
                    return true;
                } else {
                    $('#autocheckin-disable_scan').prop('disabled', scanActive);
                }
            });
        });

        $('body').on('click', '#autocheckin-disable_scan', function(e) {
            disableScanConfig.forEach(function(scanConfig) {
                $('#autocheckin-' + scanConfig).prop('disabled', e.target.checked)
            });
        })

        $('body').on('click', '#generateRandomTokenButton', function(e) {
            e.preventDefault();

            var characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&';
            var randomString = '';
            for (var i = 0; i < 12; i++) {
                var randomIndex = Math.floor(Math.random() * characters.length);
                randomString += characters.charAt(randomIndex);
            }

            $("#tokenKeyInput").val(randomString);
        });


        $('#autocheckin-form').submit(function(e) {
            e.preventDefault();

            var selectedValues = getSelectedBrandValues();

            var data = {
                'data': {},
                'background_color': $('#autocheckin-color').val(),
                'selectedBrandsValues': selectedValues
            };

            $('#autocheckin-form .autocheckin-fields input').each(function() {
                data[this.name] = this.value;
            });

            $('#autocheckin-fields input').each(function() {
                data.data[this.name] = this.value;
                if ($(this).attr('type') == 'checkbox' && !$(this).prop('checked')) {
                    data.data[this.name] = 0;
                }
            });

            data.data['identification'] = {
                'reservation_inputs': [],
                'reservation_filters': [],
                'validate_data_scan': [],
                'child_form': []
            };

            for (var identification in data.data['identification']) {
                var inputs = [];
                var index, group, type, selection;

                $('#autocheckin-identification .' + identification + ' .input').each(function() {
                    group = $(this).data('group');
                    selection = $(this).parent().parent().find('select').val();

                    if (!inputs[group]) {
                        inputs[group] = [];
                        index = 0;
                    }

                    if (!inputs[group][index]) {
                        inputs[group][index] = {};
                    }

                    switch (this.name) {
                        case 'active':
                            inputs[group][index]['active'] = $(this).is(':checked');
                            break;
                        case 'required':
                            inputs[group][index]['required'] = $(this).is(':checked');
                            break;
                        case 'fill_from_holder':
                            inputs[group][index]['fill_from_holder'] = $(this).is(':checked');
                            break;
                        case 'hide_filled':
                            inputs[group][index]['hide_filled'] = $(this).is(':checked');
                            break;
                        case 'not_fill_on_reception':
                            inputs[group][index]['not_fill_on_reception'] = $(this).is(':checked');
                            break;
                        case 'type':
                            type = this.value;
                            inputs[group][index]['type'] = this.value;
                            break;
                        case 'name':
                            inputs[group][index]['name'] = this.value;
                            break;
                        case 'position':
                            inputs[group][index]['position'] = parseInt(this.value);
                            break;

                        case 'minLength':
                            if (!isNaN(parseInt(this.value)) && type == 'text') {
                                inputs[group][index]['minLength'] = parseInt(this.value);
                            }
                            break;

                        case 'maxLength':
                            if (!isNaN(parseInt(this.value)) && type == 'text') {
                                inputs[group][index]['maxLength'] = parseInt(this.value);
                            }
                            break;

                        case 'select':
                        case "autocomplete":

                            if (selection == 'select' || selection == 'autocomplete') {
                                try {
                                    inputs[group][index]['options'] = JSON.parse(this.value);
                                } catch (e) {
                                    inputs[group][index]['options'] = [];
                                    console.error('Invalid object ' + e.message());
                                }
                            }
                            index = inputs[group].length;
                            break;
                    }

                });
                data.data['identification'][identification] = inputs;
            }

            $.post($(this).attr('action'), data).done(function(response) {
                var lang = '<?php echo $_SESSION["userLang"] ?>';

                $('#autocheckin-modal').modal('hide');

                var feedbackForm = {
                    nError: response.messageCode,
                    lang,
                    product_name: response.productName
                };

                if (response.messageCode === "2041") {
                    feedbackForm.brand_id = response.success;
                } else {
                    feedbackForm.brand_id = response.failure;
                }
                sendFeedback(feedbackForm);

            }).fail(function(response) {
                try {
                    $('#autocheckin-form').html(response.responseJSON.errors);
                } catch (e) {
                    $('#autocheckin-form').html(e.message);
                }
            });
        });

        $('#portalProConfigForm').submit(function(e) {
            e.preventDefault();

            var selectedValues = getSelectedBrandValues();

            var data = {
                'data': {},
                'selectedBrandsValues': selectedValues,
                'product_id': $('#portalPro_product_id').val(),
                'hotel_id': $('#portalPro_hotel_id').val(),
                'brand_id': $('#portalPro_brand_id').val(),
                'product_name': $('#portalPro_product_name').val(),
            };

            var serializedFormData = $(this).find(":input:not(:hidden)").serializeArray();

            var formatDataObject = {};
            serializedFormData.forEach(function(item) {
                formatDataObject[item.name] = item.value
            })
            data['data'] = formatDataObject

            $.post($(this).attr('action'), data).done(function(response) {

                var lang = '<?php echo $_SESSION["userLang"] ?>';

                $('#portalProModal').modal('hide');

                var feedbackForm = {
                    nError: response.messageCode,
                    lang,
                    product_name: response.productName
                };

                if (response.messageCode === "2041") {
                    feedbackForm.brand_id = response.success;
                } else {
                    feedbackForm.brand_id = response.failure;
                }

                sendFeedback(feedbackForm);
                setTimeout(function() {
                    location.reload(true);
                }, 3000);

            }).fail(function(response) {
                try {
                    $('#portalProForm').html(response.responseText);
                } catch (e) {
                    $('#portalProForm').html(e.message);
                }
            })

        });

        $('body').on('change', '#autocheckin-form select', function() {

            switch (this.value) {
                case 'text':
                    $(this).closest('.type-select').next('.lengths').show();
                    $(this).closest('.type-select').parent().find('.select').hide();
                    break;

                case 'select':
                    $(this).closest('.type-select').parent().find('.select').show();
                    $(this).closest('.type-select').next('.lengths').hide();
                    break;

                case 'autocomplete':
                    $(this).closest('.type-select').parent().find('.select').show();
                    $(this).closest('.type-select').next('.lengths').hide();
                    break;

                default:
                    $(this).closest('.type-select').next('.lengths').show();
                    $(this).closest('.type-select').parent().find('.select').hide();
                    break;
            }
        });

        $(".payments-option").click(function(e) {
            e.preventDefault();
            listPaymentConfigForBrand($(this).data('brand-id'));
        });

        <?php if ($integrationEnabled ?? false) { ?>

            // DATAMATCH
            $(".datamatch_edit_button").click(function() {
                $("#datamatchLoader").fadeIn();
                $("#datamatchForm").hide();
                $('#datamatch_hotel_id').val($(this).data('datamatch-hotel-id'));
                $('#datamatch_brand_id').val($(this).data('datamatch-brand-id'));

                checkIntegrationForBrand($(this));
            });

            // INTEGRATION CONFIG
            $(".integration_edit_button").click(function() {
                showIntegrationLoader();
                listIntegrationConfigForBrandByType('pms')($(this).data('brand-id'));
            });

            // PORTALPRO
            $(".portalPro_edit_button").click(function() {
                // Set default config for portal pro form.
                $('#portalpro-error-config').remove();
                $('#portalPro_submit_button').prop('disabled', true);

                // Set values.
                $('#portalPro_product_id').val($(this).data('product-id'));
                $('#portalPro_hotel_id').val($(this).data('hotel-id'));
                $('#portalPro_brand_id').val($(this).data('brand-id'));

                // Check if is activated pms integration to let active pms validation
                if (!$(this).data('is_pms_integration_activated')) {
                    // Add warning message
                    $('#pmsValidationWarningMessage').append('<p class="text-warning" id="portalpro-error-config">Warning: First enable the integration before configure pms validation.</p>');
                    // Block portalPro config for pms validation
                    $('#portalPro_name_surname').prop('checked', false).prop('disabled', true);
                    $('#portalPro_document_id').prop('checked', false).prop('disabled', true);
                    $('#portalPro_room_number').prop('checked', false).prop('disabled', true);
                } else {
                    $('#portalPro_name_surname').prop(
                        'checked',
                        $(this).data('first_name') || $(this).data('last_name') ?
                        true :
                        false
                    );
                    $('#portalPro_document_id').prop('checked', $(this).data('document_id') ? true : false);
                    $('#portalPro_room_number').prop('checked', $(this).data('room_number') ? true : false);
                }
                $('#portalPro_restrictive').prop('checked', $(this).data('restrictive') ? true : false);
                $('#portalPro_max_validations').val($(this).data('max_validations'));
                // Access code values
                $('#portalPro_access_code').prop('checked', $(this).data('access_code') ? true : false);
                $('#portalPro_premium_access_code').prop('checked', $(this).data('premium_code') ? true : false);
                // Radius ticket values
                $('#portalPro_radius_ticket').prop('checked', $(this).data('radius_ticket') ? true : false);
                $('#portalPro_premium_ticket').prop('checked', $(this).data('premium_ticket') ? true : false);

                // Let be configured
                $('#portalPro_submit_button').prop('disabled', false);
            });

            // PORTAL REDIRECT
            $(".portalRedirect_edit_button").click(function() {
                // showIntegrationLoader();
                listIntegrationConfigForBrandByType('redirect')($(this).data('brand-id'), $(this).data('product-id'));
            });
        <?php } ?>

        $('.color-input').colorpicker({
            container: '.brand-config-fields',
            format: 'hex',
            align: 'left'
        });
    });
</script>
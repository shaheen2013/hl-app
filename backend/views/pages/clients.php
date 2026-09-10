<?php //Miramos si esta definida la variable de control de index.php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
} ?>

<?php include LANG . $_SESSION['userLang'] . '/clients-datatable.php'; ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.15/css/dataTables.bootstrap.min.css">
<script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.15/js/dataTables.bootstrap.min.js"></script>

<!-- DatePicker CSS & JS -->
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.min.css"/>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js"></script>

<div id="wrapper">
    <?php include TEMPLATES . 'hotel-sidebar.php'; ?>
    <div id="page-content-wrapper">
        <div class="top-bar">

            <?php include TEMPLATES . 'referrals-menu.php' ?>
            <img class="topbarLogo visible-xs" src="<?php echo DIR_IMG . 'topbar-logo-xs.png' ?>" width="77" height="70" alt="top bar logo">
        </div>
        <div class="utility-bar">
            <div class="col-lg-12">
                <h1 class="pull-left"><i class="fa fa-truck"></i> <?php echo $clientsLang['Clients List'] ?></h1>
                <div class="breadcrumbs pull-right">
                    <ul>
                        <?php include(TEMPLATES . 'breadcrumbs.php'); ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="mainContent" id="fullContainer">
            <div class="col-lg-12 mt clients-table-container">
                <table id="clients-table" class="table cell-border compact order-column nowrap table-interactive">
                    <thead>
                    <tr>
                        <?php foreach($_SESSION['clientsColumns'] as $key => $value) {?>
                            <td><?php echo $value ?></td>
                        <?php } ?>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                    </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Modal -->
            <div id="inputEmails" class="modal fade" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">

                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                            </button>
                            <h4 class="modal-title"><?php echo $clientsLang['csvModalHeader'] ?> </h4>
                        </div>

                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <p><?php echo $clientsLang['csvModalText'] ?></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <textarea id="emails_textarea" rows="5" name="email" method="post" form="export_form" class="form-control" placeholder="email@example.org..."></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <input type="button" id="modalExportButton" value="<?php echo $clientsLang['exportButton'] ?>"
                                    class="btn btn-primary" />
                                <button type="button" class="btn btn-default" data-dismiss="modal"> <?php echo $clientsLang['close'] ?> </button>
                            </div>
                      
                    </div>  
                </div>   
            </div>
            <!-- End Modal -->

        </div>
    </div>
</div>

<script src="<?php echo DIR_JS ?>feedback-errors.js"></script>
<script>

    $(document).ready(function() {

        var dates = [];
        var searchByChain = false;
        var numVisits = null;
        var hosted = true;
        var subscribed = false;
        var phone = "-"
        var avatar = '<?php echo DIR_IMG; ?>avatar.jpg';
        var csvHeaders = <?php echo json_encode($_SESSION['clientsColumns']) ?>;
        var selectedValue = 'email';
       
        var table = $('#clients-table').on('preXhr.dt', function(e, settings, json){
            showLoader();
        }).DataTable( {
            "language": <?php echo json_encode($languageTable); ?>,
            "processing": false,
            "serverSide": true,
            "scrollX": true,
            "scrollY": '50vh',
            "autoWidth": false,
            "searchDelay": 700,
            "ajax": {
                "url": '<?php echo(SECURE_BASE_PATH . LIB . 'webservices/clients-ws.php') ?>',
                "method": "POST",
                "data": function ( d ) {
                    d.id_hotel = <?php echo(array_get($_SESSION,'h_logueado', array_get($_SESSION,'staff_id_hotel'))) ?>;
                    d.hide_unsubscribed = <?php echo isset($clientsConfiguration['config']['hide_unsubscribed_clients']) && $clientsConfiguration['config']['hide_unsubscribed_clients'] ? $clientsConfiguration['config']['hide_unsubscribed_clients'] : 0 ?>;
                    d.phone_active = <?php echo isset($clientsConfiguration['config']['phone_active']) && $clientsConfiguration['config']['phone_active'] ? $clientsConfiguration['config']['phone_active'] : 0 ?>;
                    d.commercial_profile = <?php echo isset($clientsConfiguration['config']['commercial_profile']) && $clientsConfiguration['config']['commercial_profile'] ? $clientsConfiguration['config']['commercial_profile'] : 0 ?>;

                    if(numVisits){
                        d.numVisits = numVisits;
                    }

                    //add hotel or chain search
                    <?php if (isChain()) {?>
                    d.id_chain = <?php echo(array_get($_SESSION, 'c_logueado')) ?>;
                    if (searchByChain){
                        d.search_chain = true;
                    }
                    <?php }?>
                    
                    //add dates to post data
                    if(dates && dates.length > 0 && dates[0]!== undefined && dates[1] !== undefined){
                        var from = dates[0];
                        var to = dates[1];
                        d.from = from.getFullYear() + "-" + (from.getMonth() + 1) + "-" + from.getDate()
                        d.to = to.getFullYear() + "-" + (to.getMonth() + 1) + "-" + to.getDate()
                    }

                    d.hosted = hosted;
                    d.subscribed = subscribed;
                    d.search_by = selectedValue;

                    return d;
                }
            },
            "order": [],
            // "orders": [[10,'desc']],
            //custom menu values for paging
            "lengthMenu": [[50, 100, 500], [50, 100, 500]],
            "columns": [
                <?php

                foreach($_SESSION['clientsColumns'] as $key => $value) {
                if($key!= $clientsLang['user_id'] && $key!= "comentario"){ ?>
                {
                    "data": "<?php echo $key ?>"
                },
                <?php }
                } ?>
            ],

            //define custom rendering of columns
            "columnDefs": getColumnsDefs(),

            //custom dom representation for dataTables
            "dom": "<'row dataTables_header'<'col-sm-12 col-xs-12 dataTables_buttons'><'col-md-10 col-xs-12 mt2 dataTables_search'><'col-md-2 col-xs-12 mt2 dataTables_dates'>>" +
            "<'row dataTables_header'<'col-sm-3'><'col-sm-5'><'col-sm-4 dataTables_loyalty'>>" +
            "<'row'<'dataTables_custom_loader'><'col-sm-12 dataTables_main'rt>>" +
            "<'row dataTables_footer'<'col-xs-6 col-sm-3'l><'col-xs-6 col-sm-2'i><'col-xs-12 col-sm-7'p>>",

            "initComplete": function (settings, json){
                $('.utility-bar h1').append('<span id="recordsTotal"></span>');

                if (json.recordsTotal >= 0){
                    $('#recordsTotal').html(' ('+ json.recordsTotal + ')');
                    $('#clients-table_wrapper').show()
                }

                $(".phantomContent").css({
                    'width': ($(".dataTables_scrollHead")[0].scrollWidth + 'px')
                });

                $(function(){
                    $(".topScroll").scroll(function(){
                        $(".dataTables_scrollBody")
                            .scrollLeft($(".topScroll").scrollLeft());
                    });
                    $(".dataTables_scrollBody").scroll(function(){
                        $(".topScroll")
                            .scrollLeft($(".dataTables_scrollBody").scrollLeft());
                    });
                });

            }

        })
            .on('xhr', function (e, settings, json, xhr) {
                if (json.recordsTotal >= 0){
                    $('#recordsTotal').html(' ('+ json.recordsTotal + ')');
                }

                hideLoader();
            });

        //add the datepicker to dataTables dom
        $('.dataTables_search').prepend('<div id="date-buttons" class="col-md-4 col-xs-12" data-toggle="buttons"><p><?php echo $clientsLang['search date'] ?></p><div class="input-daterange input-group col-md-5 col-xs-12" id="datepicker" style="width:100%"><span class="input-group-addon"><?php echo $clientsLang['Dates'] ?></span> <input type="text" class=" form-control" name="start" placeholder="<?php echo $clientsLang['Start'] ?>" /> <span class="input-group-addon"><?php echo $clientsLang['To'] ?></span> <input type="text" class=" form-control" name="end"  placeholder="<?php echo $clientsLang['End'] ?>"/></div></div>')
            
        $('.dataTables_search').prepend('<div id="accommodated-buttons" class="btn-group col-md-2 col-xs-12" data-toggle="buttons"> <form id="filter_by_status"> <p> <?php echo $clientsLang['by status'] ?> </p> <select id="select_filter_client" name="status" class="form-control selectpicker" style="width:100%" title="<?php echo $clientsLang['by status']; ?>"> <option class="hosted" value="hosted"> <?php echo $clientsLang['hosted'] ?> </option> <?php if (!(isset($clientsConfiguration['config']['hide_unsubscribed_clients']) && $clientsConfiguration['config']['hide_unsubscribed_clients'])): ?> <option class="subscribed" value="subscribed" name="subscribed"> <?php echo $clientsLang['subscribed'] ?> </option> <?php endif; ?> <option class="all" value="all" name="all"> <?php echo $clientsLang['all'] ?> </option> </select></form> </div>');

        //show the toggle chain/hotel buttons only when hotel is part of chain
        <?php if (isChain() && $_SESSION['hotel_activated'] == 1 ) {?>

        var buttons = '<div id="show_clients" class="btn-group col-md-2 col-xs-12" data-toggle="buttons"> <form id="show_clients"> <p> <?php echo $clientsLang['show clients'] ?> </p> <select id="select_filter_brand" name="type_of_brand" class="form-control selectpicker" style="width:100%" title="<?php echo $clientsLang['show clients'] ?> "> <option class="hotel" value="hotel"> <?php echo $clientsLang['By Hotel'] ?> </option> <option class="chain" value="chain"> <?php echo $clientsLang['By Chain'] ?> </option> </select></form> </div>';
        $('.dataTables_search').prepend(buttons)

        <?php } else { ?>

        var buttons = '<div id="show_clients" class="btn-group col-md-2 col-xs-12" data-toggle="buttons"> <form id="show_clients"> <p> <?php echo $clientsLang['show clients'] ?> </p> <select id="select_filter_brand" name="type_of_brand" class="form-control selectpicker" style="width:100%" title="<?php echo $clientsLang['show clients'] ?> "> <option class="hotel" value="hotel"> <?php echo $clientsLang['By Hotel'] ?> </option> </select></form> </div>';
        $('.dataTables_search').prepend(buttons)

        <?php

        } ?>

        $('.dataTables_custom_loader').prepend('<div class="svg-container"><img style="display: block;margin: auto;" src="https://images.hotelinking.com/ui/Loader_black.gif" alt="loader" height="50" width="50"></div>')
        showLoader();         
        // Build search dropdown options
        var selectedFilter = '';
        var searchOptionsHTML = '';
        <?php
        $filters = ['email','name','res_id','pms_id', 'access_code'];
        foreach ($filters as $filter): ?>
        searchOptionsHTML += '<option value="<?php echo $filter; ?>"><?php echo $clientsLang[$filter]; ?></option> ';
        <?php endforeach; ?>

        // Add search dropdown
        $('.dataTables_search').prepend(
            '<div class="btn-group col-md-2 col-xs-6">' +
                '<p><?php echo $clientsLang["search by"]; ?></p>' +
                '<select id="searchType" class="form-control selectpicker" style="width:100%" title="<?php echo $clientsLang["search by"]; ?>">' +
                    searchOptionsHTML +
                '</select>' +
            '</div>' +
            '<div class="btn-group col-md-2 col-xs-6">' +
                '<p><?php echo $clientsLang["search client"]; ?></p>' +
                '<div class="input-group">' +
                    '<input type="text" id="mySearchText" class="form-control" placeholder="<?php echo $clientsLang["search client"]; ?>">' +
                    '<span id="search-icon" class="input-group-addon"><i class="fa fa-search"></i></span>' +
                '</div>' +
            '</div>'
        );
        $('.dataTables_search').on('change', '#searchType', function() {
        selectedValue = $(this).val();
        });



        //add the reset button
        $('.dataTables_dates').prepend('<div class="col-xs-12"><p>&nbsp</p><button class="btn btn-default btn-primary dataTables_reset pull-right" style="width: 100%;"><?php echo $clientsLang['Reset'] ?></button></div>')

        //export buttons (disabled to staff)
        <?php if (isset($_SESSION['h_logueado'])) { ?>

        // Export Select
        $('.dataTables_buttons').append('<div class="col-sm-12 visible-xs"><div class="btn-group pull-right" style="width:100%"> <form id="export_form" method="post" action="<?php echo(SECURE_BASE_PATH . LIB . 'webservices/clients-ws.php')?>"> <p> <?php echo $clientsLang['exportButtonLabel'] ?> </p><select id="export-type" name="export-type" form="export_form" class="form-control selectpicker" title="<?php echo $clientsLang['selectTitle'] ?>"> <option class="export-query" value="export-query""> <?php echo $clientsLang['Search Results'] ?> </option> <option class="export-all" value="export-all"> <?php echo $clientsLang['All Clients'] ?> </option> </select> <button type="button" class="btn btn-default" data-toggle="modal" data-target="#inputEmails"> <option class="subscribed" value="subscribed"> <?php echo $clientsLang['export'] ?> </option> </button></form> </div></div>');
        $('.dataTables_buttons').append('<div class="hidden-xs pull-right"><div class="btn-group" style="width:100%; padding-right: 15px;"> <form id="export_form" method="post" action="<?php echo(SECURE_BASE_PATH . LIB . 'webservices/clients-ws.php')?>"> <p> <?php echo $clientsLang['exportButtonLabel'] ?> </p><select id="export-type" name="export-type" form="export_form" class="form-control selectpicker" title="<?php echo $clientsLang['selectTitle'] ?>"> <option class="export-query" value="export-query""> <?php echo $clientsLang['Search Results'] ?> </option> <option class="export-all" value="export-all"> <?php echo $clientsLang['All Clients'] ?> </option> </select> <button type="button" class="btn btn-default" data-toggle="modal" data-target="#inputEmails"> <option class="subscribed" value="subscribed"> <?php echo $clientsLang['export'] ?> </option> </button></form> </div></div>');

        <?php } ?>
        var typingTimer;
        var doneTypingInterval = 700;

        // unbid keyup event
        $('#mySearchText').unbind('keyup');

        //Detect when the user is typing
        $('#mySearchText').keypress(function(e) {
             if (e.which == 13) { // Enter code is 13
                 doneTyping(); 
                 return false; 
             }
        });

        $('#search-icon').click(function() {
            doneTyping();
        });

        //user is "finished typing," do something
        function doneTyping () {
            table.search($('#mySearchText').val()).draw();
        }

        //listen to change date in datepicker
        //format date as UTC and only if end date > start date then reload ajax
        $('.input-daterange').datepicker({
            "format": 'yyyy-mm-dd'
        }).on(
            'changeDate', function(e){
                dates = [];
                $('.input-daterange input').each(function() {
                    dates.push($(this).datepicker('getUTCDate'));
                });
                if (dates[0] < dates[1]){
                    table.ajax.reload();
                }

            }
        );

        //toggle between search by chain or search by hotel, reload ajax
        $('#select_filter_brand').on('change', function(e) {
            if ($('#select_filter_brand option:selected').val() === 'hotel'){
                searchByChain = false;
                table.ajax.reload();
            } else if ($('#select_filter_brand option:selected').val() === 'chain'){
                searchByChain = true;
                table.ajax.reload();
            }
        })
        

        $('#select_filter_client').on('change', function(e) {
            filter_applied = $('#select_filter_client option:selected').val();

            hosted = false;
            subscribed = false;
            all = false;
            if (filter_applied === 'hosted') {
                hosted = true;
            } else if(filter_applied == 'subscribed') {
                subscribed = true;
            } else {
                all = true;
            }
            
            table.ajax.reload();
        })

        //clean up styling for dataTables
        $('.dataTables_filter input').removeClass('input-sm')
        $('.dataTables_scrollFoot').remove();
        $.fn.DataTable.ext.pager.numbers_length = 9;

        $(' tbody').on('click', 'tr', function () {
            var data = table.row( this ).data();

            if (!window.location.origin) {
                window.location.origin = window.location.protocol + "//" + window.location.hostname + (window.location.port ? ':' + window.location.port: '');
            }

            redirectUrl = window.location.origin + "/clients-profile/" + data["user_id"];
            location.href = redirectUrl;
        } );


        //click reset button triggers
        //remove values of date inputs
        //reset dates array
        //reset search input
        //reload table via ajax
        $('.dataTables_reset').on('click', function(e){
            $('#datepicker > input ').val("");
            $('#mySearchText').val("");
            $('#select_filter_brand').val("hotel").change();
            $('#select_filter_client').val("hosted").change();
            dates = []
            table.search('')
            table.ajax.reload();
        })

        //manually show Loader and hide table
        function showLoader(){
            $('.dataTables_custom_loader').fadeIn();
            $('.dataTables_main').hide();
            $('.dataTables_footer').hide();
        }

        //manually hide Loader and show table
        function hideLoader(){
            $('.dataTables_custom_loader').hide();
            $('.dataTables_main').fadeIn();
            $('.dataTables_footer').fadeIn();
        }

        $('#modalExportButton').on('click', function(e){
            var extraData = {"export-type": $("#export-type").val(), "emails": $('#emails_textarea').val()}

            $.ajax({
                "url": '<?php echo(SECURE_BASE_PATH . LIB . 'webservices/clients-ws.php') ?>',
                "data": Object.assign(extraData, table.ajax.params()),
                type: 'POST',
                success: function(response) {
                    $.ajax({
                    url: "/lib/webservices/msgFeedback.php",
                    data: "nError=2039&lang=<?php echo $_SESSION['userLang'] ?>",
                    type: 'POST',
                        success: function(output) {
                            $('#inputEmails').modal('toggle');
                            data = $.parseJSON(output);
                            showError(data, 5000);
                        }
                        });
                }, 
                error: function(response) {
                    $.ajax({
                        url: "/lib/webservices/msgFeedback.php",
                        data: "nError=4077&lang=<?php echo $_SESSION['userLang'] ?>",
                        type: 'POST',
                            success: function(output) {
                                data = $.parseJSON(output);
                                showError(data, 5000);
                            }
                        });
                }
            });
        });

    });

    function getFormattedDate(date) {
        var year = date.getFullYear();

        if(isNaN(year)) return 'N/A';

        var month = (1 + date.getMonth()).toString();
        month = month.length > 1 ? month : '0' + month;

        var day = date.getDate().toString();
        day = day.length > 1 ? day : '0' + day;

        return day + '/' + month + '/' + year;
    }

    function getColumnsDefs() {
        var columns = [
                //add dt-center to each cell
                {
                    "className": "dt-center", 
                    "targets": "_all",
                    "createdCell": function (td, cellData, rowData, row, col) {
                        $(td).attr('title', cellData);
                        $(td).css({
                            "max-width": "20em",
                            "text-overflow": "ellipsis",
                            "white-space": "nowrap",
                            "overflow": "hidden",
                        });
                    }
                },
                {"orderSequence" : ['desc', 'asc'], "targets": "_all"},

                //for the hotel column
                {
                    "targets": 2,
                    "render": function ( data, type, row ) {
                        if (!row['brand_name']){
                            return "<?php echo $_SESSION['hotelName'] ?>";
                        } else {
                            return data;
                        }
                    }
                },

                //for the check_in column
                {
                    "targets": 4,
                    "render": function ( data, type, row ) {
                        if (!row['check_in'] || row['check_in'] == "null"){
                            return "-";
                        } else {
                            return row['check_in'];
                        }
                    }
                },

                //for the check_out column
                {
                    "targets": 5,
                    "render": function ( data, type, row ) {
                        if (!row['check_out'] || row['check_out'] == "null"){
                            return "-";
                        } else {
                            return row['check_out'];
                        }
                    }
                },

                //for the channel column
                {
                    "targets": 6,
                    "render": function ( data, type, row ) {
                        if (!row['channel'] || row['channel'] == "null"){
                            return "-";
                        } else {
                            return row['channel'];
                        }
                    }
                },

                //for the agency column
                {
                    "targets": 7,
                    "render": function ( data, type, row ) {
                        if (!row['agency'] || row['agency'] == "null"){
                            return "-";
                        } else {
                            return row['agency'];
                        }
                    }
                },

                //for the subscribe column
                {
                    "targets": 8,
                    "render": function ( data, type, row ) {
                        if (row['unsubscribed'] == 0){
                            return "<?php echo $clientsLang['yes'] ?>";
                        } else {
                            return "<?php echo $clientsLang['no'] ?>";
                        }
                    }
                },

                //for the room column
                {
                    "targets": 9,
                    "render": function ( data, type, row ) {
                        if (!row['room_number'] || row['room_number'] == "null"){
                            return "-";
                        } else {
                            return row['room_number'];
                        }
                    }
                },

            ];
    
            if (<?php echo json_encode(isset($phoneFormIsActive) && $phoneFormIsActive === true); ?>) {
                columns.push(
            {
                "targets": 10,
                "render": function (data, type, row) {
                    if (!row['phone_number'] || row['phone_number'] === "null") {
                        return "-";
                    } else {
                        return row['phone_number'];
                    }
                }
            });  
        }
        return columns;
    }
</script>
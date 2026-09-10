<div class="mt2">
    <div class="col-lg-12 text-center">
        <h2>Búsqueda de usuarios</h2>
        <div class="btn-group" role="group" aria-label="...">
            <a href="<?php echo SECURE_BASE_PATH . 'private/' . $urlTree['private-invitar-hotel']?>" class="btn btn-default"><i class="fa fa-arrow-left" aria-hidden="true"></i> Volver</a>
            <a class="btn btn-warning" href="app/logout" title="logout">Logout</a>
        </div>
    </div>
    <div class="col-lg-12">
        <?php                   
            if ($searchInput) { ?>
                <h2 class="col-sm-4">Listado de usuarios</h2>
                <div class="col-sm-offset-4 col-sm-3 mt2">
                    <input class="form-control" type="text" placeholder="Search by email" aria-label="Search" id="searchInput">
                </div>
                <button class="col-sm-1 mt2 btn btn-success" type="button" onClick="onSearchUser()">Search</button>
                <?php if (empty($userBrand)) { ?>
                    <div class="col-md-6 col-md-offset-3 text-center" style="margin-top: 10px;" id= "userNotFoundMessage">
                        <strong>
                            <p class="text-danger">Ningún usuario ha sido encontrado.</p>
                        </strong>
                    </div>
                <?php } else { ?>
                    <div id= "table" class="table-responsive">
                        <table class="table table-hover table-bordered table-striped">
                            <tr>
                                <td>User ID</td>
                                <td>Name</td>
                                <td>Email</td>
                                <td>Brand ID</td>
                                <td>Date</td>
                                <td>Suscribed</td>
                                <td>Actions</td>
                            </tr>
                            <?php foreach ($userBrand as $user) { ?>
                                <tr>
                                    <td><?php echo $user['user_id']; ?></td>
                                    <td><?php echo $user['name']; ?></td>
                                    <td><?php echo $user['email']; ?></td>
                                    <td><?php echo $user['brand_id']; ?></td>
                                    <td><?php echo date('Y-m-d', strtotime($user['date'])); ?></td>
                                    <td><?php echo ($user['unsubscribed'] == 0) ? 'Yes' : 'No'; ?></td>
                                    <td>
                                        <a onclick="changeHotel(<?php echo $user['hotel_id']; ?>, window.location.origin + '/clients-profile/<?php echo $user['user_id']; ?>', true)" class="btn btn-primary">Dashboard Login</a>
                                    </td>    
                                </tr>
                            <?php } ?>
                        </table>
                    </div>
                <?php } ?>
        <?php } else { ?>
            <div class="row mt4">
                <div class="col-sm-offset-1 col-sm-8 text-center">
                    <input class="form-control input-lg text-center" type="text" placeholder="Search by email" aria-label="Search" id="searchInput">
                </div>
                <button class="btn btn-success col-sm-1 input-lg" type="button" onClick="onSearchUser()">Search</button>
            </div>
        <?php } ?>
        <div id="emptySearchMessage" style="display: none">
            <div class="col-md-6 col-md-offset-3 text-center" style="margin-top: 10px;">
                <strong>
                    <p class="text-danger">Por favor ingrese un término de búsqueda.</p>
                </strong>
            </div>
        </div>        
    </div>
</div>

<script src="/<?php echo DIR_JS ?>relogin.js?v=3"></script>

<script>
    let searchText = '';
    function onSearchUser() {
        $('#emptySearchMessage').hide(); 
        $("#userNotFoundMessage").hide()
        // Get the searchInput text
        searchText = $('#searchInput').val().toLowerCase().trim();
        if (searchText === '') {
            $('#emptySearchMessage').show(); 
            $('#table').hide(); 
            let newURL= ''
            if(window.location.pathname != "/private/private-search-users/") {
                newURL = window.location.pathname + '/?search=';
            } else if(window.location.search){
                newURL = window.location.pathname  + '?search=';
            }          
            window.history.replaceState({}, document.title, newURL);
        } else {
            // Send the request  
            window.location.assign("<?php echo SECURE_BASE_PATH . 'private/' ?>private-search-users/?search=" + searchText);
        }
    }

    $(document).ready(function() {
        let searchInputValue = "<?php echo isset($searchInput) ? $searchInput : ''; ?>";
        $('#searchInput').val(searchInputValue)
        // Perform search on enter press
        $('#searchInput').keyup(function(event) {
            if (event.keyCode == 13) {
                onSearchUser();
            }
        })
    })   
</script>
<?php
include LANG . $_SESSION['userLang'] . '/statistics/pager.php';
include LIB . 'pager.php';

$resultsPerPage = $resultsPerPage ?? 10;
$paginate = getPager($numberPages, $page);

?>
<form id="filterForm" method="post">
    <div style="display: inline-block;" class="ui left floated pagination" >
        <?php echo $lang['show']; ?> &nbsp
        <select style="width:1em" name="resultsList" form="filterForm" class="ui dropdown" onchange='submitFilterForm(1)'>
            <option <?php echo $resultsPerPage == "10" ? 'selected="selected"': '' ?> value="10">10</option>
            <option <?php echo $resultsPerPage == "50" ? 'selected="selected"': '' ?> value="50">50</option>
            <option <?php echo $resultsPerPage == "100" ? 'selected="selected"': '' ?> value="100">100</option>
            <option <?php echo $resultsPerPage == "500" ? 'selected="selected"': '' ?> value="500">500</option>
        </select>
        &nbsp <?php echo $lang['results']; ?>
    </div>

    <div style="min-height: 2em;" class="ui right floated pagination menu">
        <?php
        echo (is_string($paginate['prev'])) ?
            '<span class="disabled disabledAnchor icon item paginateItem"><i class="left chevron icon"></i></span>' :
            '<a onclick="submitFilterForm(' . $paginate['prev'] . ')" class="icon item paginateItem"><i class="left chevron icon"></i></a>';

        foreach($paginate['pages'] as $curr_page) {
            echo (is_string($curr_page)) ?
                '<span class="active active-page item paginateItem">' . $curr_page . '</span>' :
                '<a onclick="submitFilterForm(' . $curr_page . ')" class="item paginateItem">' . $curr_page . '</a>';
        }

        echo (is_string($paginate['next'])) ?
            '<span class="disabled disabledAnchor icon item paginateItem"><i class="right chevron icon"></i></span>' :
            '<a onclick="submitFilterForm(' . $paginate['next'] . ')" style="padding: 0 !important; vertical-align: middle; display: inline-block; margin: auto;" class="icon item"><i class="right chevron icon"></i></a>';
        ?>
    </div>

    <input type="hidden" name="rangeStart" value="<?php echo $_POST['rangeStart'] ?? ""; ?>">
    <input type="hidden" name="rangeEnd" value="<?php echo $_POST['rangeEnd'] ?? ""; ?>">
    <input type="hidden" name="chainSearch" value="<?php echo $_SESSION['chainSearch'] ?? ""; ?>">
</form>
<script>
    // Table sorting
    $(document).ready(function () {
        $('.comparison_table').tablesort();
    });

    // Submit form
    function submitFilterForm(page) {
        var requestedPage = (page) ? page : $('.active-page')[0].getAttribute("value");
        $('<input />').attr('type', 'hidden')
            .attr('name', "page")
            .attr('value', requestedPage)
            .appendTo('#filterForm');

        $("#filterForm").submit();
        return true;
    }
</script>
<style>
    .disabledAnchor {
        pointer-events: none !important;
        cursor: default;
    }

    .paginateItem {
        padding: .8rem 0rem !important;
        vertical-align: middle !important;
        display: inline-block !important;
    }
</style>

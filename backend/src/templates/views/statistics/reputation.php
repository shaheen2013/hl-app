<?php include LANG . $_SESSION['userLang'] . '/statistics/reputation.php' ?>

<?php $this->layout('_layout::layout', [
    'title' => $this->e($title),
    'page_title' => $this->e($page_title),
    'page_icon' => $this->e($page_icon),
    'hotel_name' => $this->e($hotel_name),
    'hotel_logo' => $this->e($hotel_logo),
    'current_page' => $this->e($current_page),
    'current_subPage' => $this->e($current_subPage),
    'url' => $url
]) ?>

<?php
//Insert dashboard top menu
$this->insert('partials::menus/dashboard_menu', ['url' => $url])
?>

<div class="grid" data-isotope='{ "itemSelector": ".grid-item", "percentPosition" : true, "masonry" : {"columnWidth" : ".grid-sizer"}}' id="user_statistics">
    <?php if ($satisfactionSurveyProductActive) { ?>
        <div class="grid-sizer"></div>
        <div class="grid-item grid-item-huge">
            <?php $this->insert('statistics::multiple_lines', [
                'name' => $lang['score time'],
                'id' => 'score_in_time',
                'data' => [
                    ['label' => 'AVG', 'data' => $averageScores, 'backgroundColor' => 'rgba(0, 0, 0, .5)', 'borderColor' => 'rgba(0,0,0,1)', 'pointBackgroundColor' => 'rgba(0,0,0,1)'],
                    ['label' => $lang['cut-off score'], 'data' => $reputation_score_limit, 'borderColor' => '#a3e635', 'borderDash' => [10, 5], 'pointRadius' => 0, 'pointBackgroundColor' => '#a3e635']
                ],
                'backgroundColor' => ['rgba(0, 0, 0, .5)'],
                'labels' => $dates,
                'label' => '',
                'parent' => $this,
                'steps' => $averageScoresSteps,
                'lapse' => true,
                'tooltip_title' => $lang['title score time'],
                'tooltip_content' => $lang['explanation score time']
            ]) ?>
        </div>
        <div class="grid-item">
            <?php $this->insert('statistics::doughnut_and_single_number', [
                'name' => $lang['survey done'],
                'id' => 'reputation_survey',
                'data' => [$surveysFulfilled, $surveysUnfulfilled],
                'backgroundColor' => [ 'rgba(0, 0, 0, 1)', 'rgba(163, 230, 53, 1)'],
                'labels' => [ucfirst($lang['done']) . ': ' . $surveysFulfilled . '%', ucfirst($lang['not done']) . ': ' . $surveysUnfulfilled . '%'],
                'value' => thousandsCurrencyFormat($surveysSent),
                'label' => $lang["total"],
                'parent' => $this,
                'tooltip_title' => $lang['title survey done'],
                'tooltip_content' => $lang['explanation survey done']
            ]) ?>
        </div>
        <div class="grid-item">
            <?php $this->insert('statistics::single_number', [
                'name' => $lang['average score'],
                'value' => $averageScore,
                'label' => $lang['out of'],
                'subLabel' => $reputation_score_limit ? "\n {$lang['cut-off score']} : {$reputation_score_limit[0]}" : null,
                'tooltip_title' => $lang['title average score'],
                'tooltip_content' => $lang['explanation average score']
            ]) ?>
        </div>
        <div class="grid-item grid-item-big">
            <?php $this->insert('statistics::single_number', [
                'name' => $lang['response time'],
                'value' => $averageTimelapseResponse,
                'label' => $parsedAverageTimelapseResponse,
                'tooltip_title' => $lang['title response time'],
                'tooltip_content' => $lang['explanation response time']
            ]) ?>
        </div>
        <div class="grid-item grid-item-big">
            <?php
            $this->insert('statistics::bar', [
                'name' => $lang['percent score'],
                'id' => 'points',
                'labels' => ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10'],
                'label' => $lang['percent users'],
                'data' => $answersByScore,
                'backgroundColor' => ['#000000', '#a3e635', '#38f7ea', '#715AFF'],
                'steps' => 10,
                'parent' => $this,
                'abs' => true,
                'decimals' => 1,
                'tooltip_title' => $lang['title percent score'],
                'tooltip_content' => $lang['explanation percent score']
            ]) ?>
        </div>
        <div class="grid-item grid-item-big">
            <?php $this->insert('statistics::bar', [
                'name' => $lang['score gender'],
                'id' => 'points_genre',
                'labels' => $gendersLabels,
                'label' => $lang['average score'],
                'data' => $gendersAvg,
                'backgroundColor' => ['#000000', '#a3e635', '#38f7ea', '#715AFF'],
                'steps' => 10,
                'parent' => $this,
                'tooltip_title' => $lang['title score gender'],
                'tooltip_content' => $lang['explanation score gender'],
                'abs' => true
            ]) ?>
        </div>
        <div class="grid-item grid-item-big">
            <?php $this->insert('statistics::bar', [
                'name' => $lang['score country'],
                'id' => 'countries',
                'labels' => $countries,
                'label' => $lang['score'],
                'data' => $answersByCountries,
                'backgroundColor' => ['#000000', '#a3e635', '#38f7ea', '#715AFF'],
                'steps' => 1,
                'parent' => $this,
                'tooltip_title' => $lang['title score country'],
                'tooltip_content' => $lang['explanation score country'],
                'abs' => true
            ]) ?>
        </div>
        <div class="grid-item grid-item-big">
            <?php $this->insert('statistics::bar', [
                'name' => $lang['score age'],
                'id' => 'generation_name',
                'labels' => $generationLabels,
                'label' => $lang['score'],
                'data' => $generationData,
                'backgroundColor' => ['#000000', '#a3e635', '#38f7ea', '#715AFF'],
                'steps' => 1,
                'parent' => $this,
                'tooltip_title' => $lang['title score age'],
                'tooltip_content' => $lang['explanation score age'],
                'abs' => true
            ]) ?>
        </div>
        <?php
        if ($categories) { ?>
            <div class="grid-item grid-item-huge">
                <?php $this->insert('statistics::bar', [
                    'name' => $lang['average score per category'],
                    'id' => 'customized_category',
                    'labels' => $categoryLabels,
                    'label' => 'AVG',
                    'data' => $categoriesData,
                    'backgroundColor' => [
                        'rgba(0, 0, 0, 1)',
                        'rgba(163, 230, 53, 1)',
                        'rgba(56, 247, 234, 1)',
                        'rgba(113, 90, 255, 1)',
                        'rgba(0, 0, 0, .6)',
                        'rgba(0, 0, 0, .4)',
                        'rgba(0, 0, 0, .2)',
                    ],
                    'steps' => 1,
                    'parent' => $this,
                    'tooltip_title' => 'media de puntuación por categoría',
                    'tooltip_content' => 'media de puntuación por categoría',
                    'abs' => true
                ]) ?>
            </div>

        <?php } ?>

        <?php
        if ($categoriesOrdered):
            foreach ($categoriesOrdered as $category):
                // Get all categories with same name but with different id
                $groupedCategories = filterResultsByValue($categoriesOrdered, 'category', $category->category);

                // Remove repeated categories from array of questions
                $categoriesOrdered = array_filter($categoriesOrdered, function ($category) use ($groupedCategories) {
                    return !in_array($category->id, array_pluck($groupedCategories, 'id'));
                });
                
                // Filter all rating questions of the same category
                $categoryQuestions = filterResultsByExistence($questions, 'category_id', $groupedCategories, 'id');
                $uniqueCategoryQuestions = getUniqueValues($categoryQuestions, 'question');

                // Filter all multiresponse questions of the same category
                $multiresponseCategoryQuestions = filterResultsByExistence($multiresponseQuestions, 'category_id', $groupedCategories, 'id');
                $uniqueMultiresponseQuestions = getUniqueValues($multiresponseCategoryQuestions, 'question');
        ?>
                <?php if($uniqueCategoryQuestions || $multiresponseCategoryQuestions): ?>
                    <div class="grid-item grid-item-huge">
                        <div style="overflow:auto;max-height:60vh;" class="ui card fluid scrolling content">
                            <table class="ui basic table unstackable">
                                <thead class="stickyRow">
                                    <tr>
                                        <th style="min-width: 30em;max-width: 30em;width:100%" class="stickyColumn"><h3><?php echo data_get($category, 'category') ?></h3></th>
                                        <?php if (count($distinctBrands) > 1): ?>
                                            <th><h3 class="ui center aligned"><?php echo $lang["total"] ?></h3></th>
                                            <th style="min-width: 8em;max-width:8em;"></th>
                                        <?php endif; ?>
                                        <?php foreach($distinctBrands as $key => $brandName) { ?>
                                            <th><h3 class="ui center aligned"><?php echo $brandName ?></h3></th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($uniqueCategoryQuestions as $question) : ?>
                                        <tr>
                                            <td class="stickyColumn">
                                                <h4 class="ui image header">
                                                    <div style="width:100%" class="statistic-row-cell content">
                                                        <p><?php echo $question ?></p>
                                                        <span class="tooltip"><?php echo $lang["question"] ?></span>
                                                    </div>
                                                </h4>
                                            </td>
                                            <?php if (count($distinctBrands) > 1): ?>
                                                <?php
                                                    $allResponses = filterResultsByValue($categoryQuestions, 'question', $question);
                                                    $totalCountResponses = computeCount($allResponses, 'count');
                                                    $totalAvgResponses =  computeAvg($allResponses, $totalCountResponses);
                                                ?>
                                                <td class="basicRow">
                                                    <div style="width:100%;margin:0 0 2em 0;" class="statistic-row-cell ui center aligned grid">
                                                        <div <?php echo getFontStyle(true) ?> class="value eight wide column">
                                                            <?php echo thousandsCurrencyFormat($totalCountResponses) ?>
                                                            <span class="tooltip"><?php echo $lang["total answers"]?></span>
                                                        </div>
                                                        <div <?php echo getFontStyle(true) ?> class="value eight wide column">
                                                            <?php echo $totalAvgResponses ?>
                                                            <span class="tooltip"><?php echo $lang["average"]?></span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="basicRow"></td>
                                            <?php endif; ?>
                                            <?php foreach($distinctBrands as $key => $brandName) : 
                                                $brandResponse = filterResultsByValue($categoryQuestions, 'question', $question, $brandName);
                                                $totalCountResponses = computeCount($brandResponse, 'count');
                                                $totalAvgResponses =  computeAvg($brandResponse, $totalCountResponses);
                                            ?>
                                                <td class="basicRow">
                                                    <div style="width:100%;margin:0 0 2em 0;" class="statistic-row-cell ui center aligned grid">
                                                        <div <?php echo getFontStyle($brandResponse) ?> class="value eight wide column">
                                                            <?php echo $brandResponse ? thousandsCurrencyFormat($totalCountResponses) : $lang["N/A"] ?>
                                                            <span class="tooltip"><?php echo $lang["total answers"]?></span>
                                                        </div>
                                                        <div <?php echo getFontStyle($brandResponse) ?>  class="value eight wide column">
                                                            <?php echo $brandResponse ? $totalAvgResponses : $lang["N/A"] ?>
                                                            <span class="tooltip"><?php echo $lang["average"]?></span>
                                                        </div>
                                                    </div>
                                                </td>
                                            <?php endforeach; ?>
                                        </tr>
                                    <?php endforeach; ?>

                                    <?php foreach ($uniqueMultiresponseQuestions as $questionIndex => $question):
                                        $allResponses = filterResultsByValue($multiresponseCategoryQuestions, 'question', $question);
                                        $responses = array_flatten(array_column(array_values($allResponses), 'responses'));
                                        $uniqueResponses = getUniqueValues($responses, 'response');

                                        foreach ($uniqueResponses as $index => $response):
                                    ?>
                                            <tr>
                                        <?php if ($index === array_keys($uniqueResponses)[0]): ?>
                                            <!-- In the first iteration of a multi-answer question, a row is painted with only the question to add the answers below it -->
                                                <td class="stickyColumn">
                                                    <h4 class="ui image header">
                                                        <div style="width:100%" class="statistic-row-cell content">
                                                            <p><?php echo $question ?></p>
                                                            <span class="tooltip"><?php echo $lang["question"] ?></span>
                                                        </div>
                                                    </h4>
                                                </td>
                                                <?php if (count($distinctBrands) > 1): ?>  
                                                    <td></td>
                                                    <td class="basicRow"></td>
                                                <?php endif;?>
                                                <?php foreach($distinctBrands as $key => $brandName): ?>
                                                    <td></td>
                                                <?php endforeach;?>
                                            </tr>
                                            <tr>
                                        <?php endif; ?>
                                                <td class="ui very basic table stickyColumn basicRow"><?php echo '&emsp;' . $response ?></td>
                                            <?php if (count($distinctBrands) > 1):
                                                $allBrandResponses = filterResultsByValue($responses, 'response', $response);
                                                $totalCountResponses = computeCount($allBrandResponses, 'count');
                                            ?>
                                                <td class="ui very basic table basicRow">
                                                    <div style="width:100%;margin:0 0 2em 0;" class="statistic-row-cell ui center aligned grid">
                                                        <div <?php echo getFontStyle(true) ?> class="value eight wide column">
                                                            <?php echo thousandsCurrencyFormat($totalCountResponses) ?>
                                                            <span class="tooltip"><?php echo $lang["total answers"]?></span>
                                                        </div>
                                                        <div class="value eight wide column">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="ui very basic table basicRow"></td>
                                            <?php endif; ?>
                                                <?php foreach($distinctBrands as $key => $brandName): 
                                                    $brandResponse = filterResultsByValue($responses, 'response', $response, $brandName);
                                                    $totalCountResponses = computeCount($brandResponse, 'count');
                                                ?>
                                                    <td class="ui very basic table basicRow">
                                                        <div style="width:100%;margin:0 0 2em 0;" class="statistic-row-cell ui center aligned grid">
                                                            <div <?php echo getFontStyle($brandResponse) ?> class="value eight wide column">
                                                                <?php echo $brandResponse ? thousandsCurrencyFormat($totalCountResponses) : $lang["N/A"] ?>
                                                                <span class="tooltip"><?php echo $lang["total answers"]?></span>
                                                            </div>
                                                            <div class="value eight wide column">
                                                            </div>
                                                        </div>
                                                    </td>
                                                <?php endforeach; ?>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
<?php  
                endif;
            endforeach;
        endif; 
    } else {
        $this->insert('partials::statistics/satisfaction-survey-no-activated-message');
    } 
?>
</div>

<?php
    // Helper functions to print question tables

    function filterResultsByValue($array, $searchKey, $value, $brandName = null)
    {
        return array_filter($array, function ($element) use ($searchKey, $value, $brandName) {
            $extraCondition = $brandName ? data_get($element, 'hotel_name') == $brandName : true;
            return trim(data_get($element, $searchKey)) == trim($value) && $extraCondition;
        });
    }
    
    function filterResultsByExistence($originalArray, $originalSearchKey, $searchArray, $searchArrayKey)
    {
        return array_filter($originalArray, function ($element) use ($originalSearchKey, $searchArray, $searchArrayKey) {
            return in_array(data_get($element, $originalSearchKey), array_pluck($searchArray, $searchArrayKey));
        });
    }

    function getUniqueValues($array, $key)
    {
        return array_values(array_unique(array_map('trim', array_column($array, $key))));

    }

    function getFontStyle($large)
    {
        $size =  $large ? '1.8' : '1';

        return 'style="font-size:' . $size . 'rem; color:black"';
    }
?>

<script src="<?php echo $this->asset('/public/javascript/isotope.pkgd.min.js') ?>"></script>
<script src="<?php echo $this->asset('/public/javascript/moment.min.js') ?>"></script>
<script src="<?php echo $this->asset('/public/javascript/calendar.min.js') ?>"></script>
<script src="<?php echo $this->asset('/public/javascript/charts.min.js') ?>"></script>
<script src="<?php echo $this->asset('/public/javascript/templates/dashboards.min.js') ?>"></script>
<script src="<?php echo $this->asset('/public/javascript/templates/statistics/doughnut.min.js') ?>"></script>
<script src="<?php echo $this->asset('/public/javascript/templates/statistics/bar.min.js') ?>"></script>
<script src="<?php echo $this->asset('/public/javascript/templates/statistics/horizontalBar.min.js') ?>"></script>
<script src="<?php echo $this->asset('/public/javascript/templates/statistics/line.min.js') ?>"></script>
<script src="<?php echo $this->asset('/public/javascript/templates/statistics/multiple_lines.min.js') ?>"></script>
<script src="<?php echo $this->asset('/public/javascript/chartjs-plugin-datalabels.min.js') ?>"></script>

<style>
    @media only screen and (min-width: 700px) {
        .stickyColumn {
            position:sticky;
            left:0;
            z-index:2;
            background-color:white !important;
        }
    }

    .stickyRow {
        position:sticky;
        top:0;
        z-index:3;
        background-color:white;
    }
    
    .basicRow {
        min-width:15em;
        width: auto !important;
    }

</style>
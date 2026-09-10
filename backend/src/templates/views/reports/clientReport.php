<?php
    $clientsLang =  include LANG . $hotelLang . '/statistics/users.php';
    $reputationLang = include LANG . $hotelLang . '/statistics/reputation.php' ;
    $clicksLang = include LANG . $hotelLang . '/statistics/clicks.php' ;
    $loyaltyLang = include LANG . $hotelLang . '/statistics/loyalty.php';
    $engagementLang = include LANG . $hotelLang . '/statistics/engagement.php';
    $staffEngagementLang = include LANG . $hotelLang . '/statistics/staffEngagement.php';
?>

<?php $this->layout('_layout::report', [
    'title' => $this->e($title),
    'page_title' => $this->e($page_title),
    'page_icon' => $this->e($page_icon),
    'hotel_name' => $this->e($hotel_name),
    'hotel_logo' => $this->e($hotel_logo),
    'hotel_lang' => $hotelLang,
    'origin_date_title' => $this->e($origin_date_title),
    'finish_date_title' => $this->e($finish_date_title),
    'url' => $url
]) ?>

<div>
    <h2><?php echo $clientsLang['stats clients']?></h2>
    <div class="report_row">
        <div class="full_statistic_div statistic_div">
            <?php $this->insert('statistics::line', [
                'name' => $clientsLang['users time'],
                'id' => 'users_in_time',
                'data' => $users,
                'backgroundColor' => 'transparent',
                'labels' => $users_days,
                'label' => '',
                'parent' => $this,
                'steps' => $user_steps,
                'lapse' => false,
                'tooltip_title' => null,
                'tooltip_content' => null
            ]) ?>
        </div>
    </div>
    <div class="report_row">
        <div class="full_statistic_div statistic_div">
            <?php $this->insert('statistics::single_number', [
                'name' => $clientsLang['total clients'],
                'value' => thousandsCurrencyFormat($this->e($totalUsers)),
                'label' => 'Clients',
                'tooltip_title' => null,
                'tooltip_content' => null
            ]) ?>
        </div>

    </div>
    <div class="report_row">
        <div class="statistic_div">
            <?php $this->insert('statistics::doughnut', [
                'name' => $clientsLang['female vs male'],
                'id' => 'mvsf',
                'data' => $genderData,
                'backgroundColor' => $background,
                'labels' => $genderLabels,
                'parent' => $this,
                'tooltip_title' => 'Usuarios por sexo',
                'tooltip_content' => 'Cantidad de hombres y mujeres que visitan el hotel.',

            ]) ?>
        </div>
        <div class="statistic_div">
            <?php $this->insert('statistics::doughnut', [
                'name' => $clientsLang['form vs face'],
                'id' => 'fbvsf',
                'data' => $sourcesData,
                'backgroundColor' => $background,
                'labels' => $sourcesLabels,
                'parent' => $this,
                'tooltip_title' => null,
                'tooltip_content' => null,

            ]) ?>
        </div>
    </div>
    <div class="report_row">
        <div class="statistic_div">
            <?php $this->insert('statistics::doughnut', [
                'name' => $clientsLang['accommodated graph'],
                'id' => 'accommodated',
                'data' => $accommodatedData,
                'backgroundColor' => $background,
                'labels' => $accommodatedLabels,
                'parent' => $this,
                'tooltip_title' => null,
                'tooltip_content' => null,

            ]) ?>
        </div>
        <div class="statistic_div">
            <?php $this->insert('statistics::doughnut', [
                'name' => $clientsLang['subscribed graph'],
                'id' => 'subscribed',
                'data' => $subscribedData,
                'backgroundColor' => $background,
                'labels' => $subscribedLabels,
                'parent' => $this,
                'tooltip_title' => null,
                'tooltip_content' => null,

            ]) ?>
        </div>
    </div>
    <div class="report_row">
        <div class="full_statistic_div  statistic_div ">
            <?php $this->insert('statistics::bar', [
                'name' => $clientsLang['country connection'],
                'id' => 'city',
                'labels' => $countryLabels,
                'label' => $clientsLang['this location'],
                'data' => $countryData,
                'backgroundColor' => $background,
                'steps' => 20,
                'parent' => $this,
                'tooltip_title' => null,
                'tooltip_content' => null,
                'event_click'      => null,
                'simple_bar'      => false,
            ]) ?>
        </div>
    </div>

    <div class="report_row">
        <div class="full_statistic_div  statistic_div ">
            <?php $this->insert('statistics::bar', [
                'name' => $clientsLang['connection age'],
                'id' => 'age',
                'labels' => $generationLabels,
                'label' => $clientsLang['this age'],
                'data' => $generationData,
                'backgroundColor' => $background,
                'steps' => 20,
                'parent' => $this,
                'tooltip_title' => null,
                'tooltip_content' => null,
                'event_click'      => null,
                'simple_bar'      => false,
            ]) ?>
        </div>
    </div>
    <div class="report_row">
        <div class="full_statistic_div  statistic_div ">
            <?php $this->insert('statistics::bar', [
                'name' => $clientsLang['devices'],
                'id' => 'device',
                'labels' => $devicesLabels,
                'label' => $clientsLang['this device'],
                'data' => $devicesData,
                'backgroundColor' => $background,
                'steps' => 10,
                'parent' => $this,
                'tooltip_title' => null,
                'tooltip_content' => null,
                'event_click'      => null,
                'simple_bar'      => false,
            ]) ?>
        </div>
    </div>
    <div style="page-break-before:always;"></div>
    <h2><?php echo $clicksLang['stats clicks'] ?></h2>
    <div class="report_row">
        <div class="full_statistic_div statistic_div">
            <?php $this->insert('statistics::single_number', [
                'name' => $clicksLang['facebook connection'],
                'value' => thousandsCurrencyFormat($socialMediaUsers),
                'label' => $clicksLang['connections'],
                'tooltip_title' => null,
                'tooltip_content' => null
            ]) ?>
        </div>
    </div>
    <div class="report_row">
        <div class="statistic_div">
            <?php $this->insert('statistics::single_number', [
                'name' => $clicksLang['medium range'],
                'value' => thousandsCurrencyFormat($averageFacebookFriends),
                'label' => $clicksLang['friends'],
                'tooltip_title' => null,
                'tooltip_content' => null
            ]) ?>
        </div>
        <div class="statistic_div">
            <?php $this->insert('statistics::single_number', [
                'name' => $clicksLang['title post facebook'],
                'value' => thousandsCurrencyFormat($socialMediaPublications),
                'label' => $clicksLang['publishing'],
                'tooltip_title' => null,
                'tooltip_content' => null
            ]) ?>
        </div>
    </div>
    <div class="report_row">
        <div class="full_statistic_div statistic_div">
            <?php $this->insert('statistics::single_number', [
                'name' => $clicksLang['average post'],
                'value' => $averageSocialMediaShares . ' %',
                'label' => $clicksLang['to total'],
                'tooltip_title' => null,
                'tooltip_content' => null
            ]) ?>
        </div>
    </div>

    <?php
    if ($surveysSent > 0) {
    ?>
        <div style="page-break-before:always;"></div>
        <h2><?php echo $reputationLang['stats reputation'] ?></h2>
        <div class="report_row">
            <div class="full_statistic_div statistic_div">
                <?php $this->insert('statistics::line', [
                    'name' => $reputationLang['score time'],
                    'id' => 'score_in_time',
                    'data' => $averageScores,
                    'backgroundColor' => 'transparent',
                    'labels' => $dates,
                    'label' => '',
                    'parent' => $this,
                    'steps' => $averageScoresSteps,
                    'lapse' => false,
                    'tooltip_title' => null,
                    'tooltip_content' => null
                ]) ?>
            </div>
        </div>
        <div class="report_row">
            <div class="statistic_div">
                <?php $this->insert('statistics::doughnut_and_single_number', [
                    'name' => $reputationLang['survey done'],
                    'id' => 'reputation_survey',
                    'data' => [$surveysFulfilled, $surveysUnfulfilled],
                    'backgroundColor' => $background,
                    'labels' => [ucfirst($reputationLang['done']) . ': ' . $surveysFulfilled . '%', ucfirst($reputationLang['not done']) . ': ' . $surveysUnfulfilled . '%'],
                    'value' => thousandsCurrencyFormat($this->e($surveysSent)),
                    'label' => $reputationLang['total'],
                    'parent' => $this,
                    'tooltip_title' => null,
                    'tooltip_content' => null,

                ]) ?>
            </div>
            <div class="statistic_div">
                <?php $this->insert('statistics::single_number', [
                    'name' => $reputationLang['average score'],
                    'value' => $averageScore,
                    'label' => $reputationLang['out of'],
                    'tooltip_title' => null,
                    'tooltip_content' => null
                ]) ?>
            </div>
        </div>
        <div class="report_row">
            <div class="full_statistic_div statistic_div">
                <?php $this->insert('statistics::bar', [
                    'name' => $reputationLang['percent score'],
                    'id' => 'points',
                    'labels' => ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10'],
                    'label' => '% usuarios',
                    'data' => $answersByScore,
                    'backgroundColor' => $background,
                    'steps' => 10,
                    'parent' => $this,
                    'abs' => true,
                    'decimals' => 1,
                    'tooltip_title' => null,
                    'tooltip_content' => null,
                    'event_click'      => null,
                    'simple_bar'      => false,
                ]) ?>
            </div>
        </div>
        <div class="report_row">
            <div class="full_statistic_div statistic_div">
                <?php $this->insert('statistics::bar', [
                    'name' =>  $reputationLang['score gender'],
                    'id' => 'points_genre',
                    'labels' => $gendersLabels,
                    'label' => 'Average rating',
                    'data' => $gendersAvg,
                    'backgroundColor' => $background,
                    'steps' => 10,
                    'parent' => $this,
                    'tooltip_title' => null,
                    'tooltip_content' => null,
                    'event_click'      => null,
                    'simple_bar'      => false,
                    'abs' => true
                ]) ?>
            </div>
        </div>
        <div class="report_row">
            <div class="full_statistic_div statistic_div">
                <?php $this->insert('statistics::bar', [
                    'name' =>  $reputationLang['score country'],
                    'id' => 'countries',
                    'labels' => $countries,
                    'label' => 'rating',
                    'data' => $answersByCountries,
                    'backgroundColor' => $background,
                    'steps' => 1,
                    'parent' => $this,
                    'tooltip_title' => null,
                    'tooltip_content' => null,
                    'event_click'      => null,
                    'simple_bar'      => false,
                    'abs' => true
                ]) ?>
            </div>
        </div>
        <div class="report_row">
            <div class="full_statistic_div statistic_div">
                <?php $this->insert('statistics::bar', [
                    'name' =>  $reputationLang['score age'],
                    'id' => 'generation_name',
                    'labels' => $satisfactionGenerationLabels,
                    'label' => 'Rating',
                    'data' => $satisfactionGenerationData,
                    'backgroundColor' => $background,
                    'steps' => 1,
                    'parent' => $this,
                    'tooltip_title' => null,
                    'tooltip_content' => null,
                    'event_click'      => null,
                    'simple_bar'      => false,
                    'abs' => true
                ]) ?>
            </div>
        </div>
        <div class="report_row">
            <div class="full_statistic_div statistic_div">
                <?php $this->insert('statistics::single_number', [
                    'name' => $reputationLang['response time'],
                    'value' => $averageTimelapseResponse,
                    'label' => $parsedAverageTimelapseResponse,
                    'tooltip_title' => null,
                    'tooltip_content' => null
                ]) ?>
            </div>
        </div>

        <?php if (!empty($categories)) : ?>
            <div style="page-break-before:always;"></div>

            <div class="report_row">
                <div class="full_statistic_div statistic_div">
                    <?php $this->insert('statistics::bar', [
                        'name' => $reputationLang['average score per category'],
                        'id' => 'customized_category',
                        'labels' => $categoryLabels,
                        'label' => 'AVG',
                        'data' => $categoriesData,
                        'backgroundColor' => $background,
                        'steps' => 1,
                        'parent' => $this,
                        'abs' => true,
                        'tooltip_title' => 'media de puntuación por categoría',
                        'tooltip_content' => 'media de puntuación por categoría',
                    ]) ?>
                </div>
            </div>

            <?php foreach ($categoriesOrdered as $category) {
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

               if($uniqueCategoryQuestions || $multiresponseCategoryQuestions):

            ?>
                    <div class="grid-item grid-item-huge">
                        <div class="ui card fluid">
                            <div class="content">
                                <div class="header">
                                    <?php echo data_get($category, 'category') ?><i style="color:black" class="ui icon question circle primary-color has-tooltip right floated" data-variation="wide" data-content="<?php echo "Nota media de las preguntas de la categoria '" . data_get($category, 'category') . "' " ?>" data-title="Nota media"></i>
                                </div>
                            </div>

                            <div class="content ui center aligned">
                                <div>
                                    <div>
                                        <?php foreach ($uniqueCategoryQuestions as $question) : 
                                            $brandName = !$chainSearch ? $this->e($hotel_name) : null;

                                            $allResponses = filterResultsByValue($categoryQuestions, 'question', $question, $brandName);
                                            $totalCountResponses = computeCount($allResponses, 'count');
                                            $totalAvgResponses =  computeAvg($allResponses, $totalCountResponses);    
                                         
                                        ?>
                                            <div class="statistic-row">
                                                <div class="statistic-row-cell main-cell has-tooltip ui ">
                                                    <p>
                                                        <?php echo $question ?>
                                                    </p>
                                                    <span class="tooltip"><?php echo $reputationLang["question"]?></span>
                                                </div>

                                                <div class="statistic-row-cell center">
                                                    <div style="color:black" class=" value"><?php echo thousandsCurrencyFormat($totalCountResponses) ?>
                                                        <span class="tooltip"><?php echo $reputationLang["total answers"]?></span>
                                                    </div>
                                                </div>

                                                <div class="statistic-row-cell center">
                                                    <div style="color:black" class="value"><?php echo $totalAvgResponses ?>
                                                        <span class="tooltip"><?php echo $reputationLang["average"]?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                        <?php foreach ($uniqueMultiresponseQuestions as $questionIndex => $question) : 
                                            $allResponses = filterResultsByValue($multiresponseCategoryQuestions, 'question', $question);
                                            $responses = array_flatten(array_column(array_values($allResponses), 'responses'));
                                            $uniqueResponses = getUniqueValues($responses, 'response');

                                            $responsesCount = array_map(function($response) use ($responses, $chainSearch, $hotel_name){ 
                                                $brandName = !$chainSearch ? $this->e($hotel_name) : null;
                                                $allBrandResponses = filterResultsByValue($responses, 'response', $response, $brandName);

                                                return computeCount($allBrandResponses, 'count');
                                            }, $uniqueResponses);
                                            
                                        ?>
                                            <div class="statistic-row">
                                                <div style="width:99%" class="statistic-row-cell left main-cell has-tooltip ui ">
                                                    <p>
                                                        <?php echo $question ?>
                                                    </p>
                                                    <span class="tooltip"><?php echo $reputationLang["question"]?></span>
                                                </div>
                                            </div>
                                            <div class="statistic-row">
                                                <?php
                                                $this->insert('statistics::horizontalBar', [
                                                    'id' => 'multiresponse_question_' . $questionIndex . '_' . $question,
                                                    'labels' => $uniqueResponses,
                                                    'data' => $responsesCount,
                                                    'backgroundColor' => $background,
                                                    'steps' => createStepsForRates($responsesCount),
                                                    'parent' => $this,
                                                    'abs' => true,
                                                    'numberElements' => array_sum($responsesCount)
                                                ]);
                                                ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php } ?>
        <?php endif; ?>
    <?php } ?>
    <?php if (!empty($satisfactionInteraction) || !empty($reviewInteraction) || !empty($birthdayInteraction)) : ?>
        <div style="page-break-before:always;"></div>
        <h2><?php echo $engagementLang['stats engagement'] ?></h2>
        <?php if (!empty($satisfactionInteraction)) { ?>
            <div class="report_row">
                <div class="full_statistic_div statistic_div">
                    <?php $this->insert('statistics::multiple_lines', [
                        'name' => $engagementLang['ratio satisfaction'],
                        'id' => 'user_satisfaction_rate',
                        'data' => [
                            [
                                'label' => $engagementLang['sent'],
                                'data' => array_get($satisfactionInteraction, 'sent'),
                                'backgroundColor' => $sentBackground,
                                'borderColor' => $sentBorder,
                                'pointBackgroundColor' => $sentBorder
                            ],
                            [
                                'label' => $engagementLang['opens'],
                                'data' => array_get($satisfactionInteraction, 'opens'),
                                'backgroundColor' => $opensBackground,
                                'borderColor' => $opensBorder,
                                'pointBackgroundColor' => $opensBorder
                            ],
                            [
                                'label' => $engagementLang['clicks'],
                                'data' => array_get($satisfactionInteraction, 'clicks'),
                                'backgroundColor' => $clicksBackground,
                                'borderColor' => $clicksBorder,
                                'pointBackgroundColor' => $clicksBorder
                            ],
                        ],
                        'labels' => array_get($satisfactionInteraction, 'dates'),
                        'parent' => $this,
                        'steps' => array_get($satisfactionInteraction, 'steps'),
                        'lapse' => false,
                        'average_opens' => array_get($satisfactionInteraction, 'opens_avg'),
                        'average_clicks' => array_get($satisfactionInteraction, 'clicks_avg'),
                        'tooltip_title' => null,
                        'tooltip_content' => null,

                    ]); ?>
                </div>
            </div>
        <?php } ?>

        <?php if (!empty($reviewInteraction)) : ?>
            <div class="report_row">
                <div class="full_statistic_div statistic_div">
                    <?php $this->insert('statistics::multiple_lines', [
                        'name' => $engagementLang['ratio review'],
                        'id' => 'user_review_rate',
                        'data' => [
                            [
                                'label' => $engagementLang['sent'],
                                'data' => array_get($reviewInteraction, 'sent'),
                                'backgroundColor' => $sentBackground,
                                'borderColor' => $sentBorder,
                                'pointBackgroundColor' => $sentBorder
                            ],
                            [
                                'label' => $engagementLang['opens'],
                                'data' => array_get($reviewInteraction, 'opens'),
                                'backgroundColor' => $opensBackground,
                                'borderColor' => $opensBorder,
                                'pointBackgroundColor' => $opensBorder
                            ],
                            [
                                'label' => $engagementLang['clicks'],
                                'data' => array_get($reviewInteraction, 'clicks'),
                                'backgroundColor' => $clicksBackground,
                                'borderColor' => $clicksBorder,
                                'pointBackgroundColor' => $clicksBorder
                            ],
                        ],
                        'labels' => array_get($reviewInteraction, 'dates'),
                        'parent' => $this,
                        'steps' => array_get($reviewInteraction, 'steps'),
                        'lapse' => false,
                        'average_opens' => array_get($reviewInteraction, 'opens_avg'),
                        'average_clicks' => array_get($reviewInteraction, 'clicks_avg'),
                        'tooltip_title' => null,
                        'tooltip_content' => null
                    ]) ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!empty($birthdayInteraction)) : ?>
            <div class="report_row">
                <div class="full_statistic_div statistic_div">
                    <?php $this->insert('statistics::multiple_lines', [
                        'name' => $engagementLang['ratio birthday'],
                        'id' => 'user_birthday_rate',
                        'data' => [
                            [
                                'label' => $engagementLang['sent'],
                                'data' => array_get($birthdayInteraction, 'sent'),
                                'backgroundColor' => $sentBackground,
                                'borderColor' => $sentBorder,
                                'pointBackgroundColor' => $sentBorder
                            ],
                            [
                                'label' => $engagementLang['opens'],
                                'data' => array_get($birthdayInteraction, 'opens'),
                                'backgroundColor' => $opensBackground,
                                'borderColor' => $opensBorder,
                                'pointBackgroundColor' => $opensBorder
                            ],
                            [
                                'label' => $engagementLang['clicks'],
                                'data' => array_get($birthdayInteraction, 'clicks'),
                                'backgroundColor' => $clicksBackground,
                                'borderColor' => $clicksBorder,
                                'pointBackgroundColor' => $clicksBorder
                            ],
                        ],
                        'labels' => array_get($birthdayInteraction, 'dates'),
                        'parent' => $this,
                        'steps' => array_get($birthdayInteraction, 'steps'),
                        'lapse' => false,
                        'average_opens' => array_get($birthdayInteraction, 'opens_avg'),
                        'average_clicks' => array_get($birthdayInteraction, 'clicks_avg'),
                        'tooltip_title' => null,
                        'tooltip_content' => null
                    ]) ?>
                </div>
            </div>
        <?php endif; ?>


        <?php if (!empty($offerInteraction)) : ?>
            <div class="report_row">
                <div class="full_statistic_div statistic_div">
                    <?php $this->insert('statistics::multiple_lines', [
                        'name' => $engagementLang['ratio offer'],
                        'id' => 'user_offer_rate',
                        'data' => [
                            [
                                'label' => $engagementLang['sent'],
                                'data' => array_get($offerInteraction, 'sent'),
                                'backgroundColor' => $sentBackground,
                                'borderColor' => $sentBorder,
                                'pointBackgroundColor' => $sentBorder
                            ],
                            [
                                'label' => $engagementLang['opens'],
                                'data' => array_get($offerInteraction, 'opens'),
                                'backgroundColor' => $opensBackground,
                                'borderColor' => $opensBorder,
                                'pointBackgroundColor' => $opensBorder
                            ],
                            [
                                'label' => $engagementLang['clicks'],
                                'data' => array_get($offerInteraction, 'clicks'),
                                'backgroundColor' => $clicksBackground,
                                'borderColor' => $clicksBorder,
                                'pointBackgroundColor' => $clicksBorder
                            ],
                        ],
                        'labels' => array_get($offerInteraction, 'dates'),
                        'parent' => $this,
                        'steps' => array_get($offerInteraction, 'steps'),
                        'lapse' => false,
                        'average_opens' => array_get($offerInteraction, 'opens_avg'),
                        'average_clicks' => array_get($offerInteraction, 'clicks_avg'),
                        'tooltip_title' => null,
                        'tooltip_content' => null
                    ]) ?>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <?php if (!empty($warningInteraction) || !empty($birthdayWarningInteraction) || !empty($loyalWarningInteraction)) : ?>
        <div style="page-break-before:always;"></div>
        <h2><?php echo $staffEngagementLang['stats staffEngagement'] ?></h2>
        <?php if (!empty($warningInteraction)) : ?>
            <div class="report_row">
                <div class="full_statistic_div statistic_div">
                    <?php $this->insert('statistics::multiple_lines', [
                        'name' => $staffEngagementLang['notify client'],
                        'id' => 'user_warning_satisfaction_rate',
                        'data' => [
                            [
                                'label' => $engagementLang['sent'],
                                'data' => array_get($warningInteraction, 'sent'),
                                'backgroundColor' => $sentBackground,
                                'borderColor' => $sentBorder,
                                'pointBackgroundColor' => $sentBorder
                            ],
                            [
                                'label' => $engagementLang['opens'],
                                'data' => array_get($warningInteraction, 'opens'),
                                'backgroundColor' => $opensBackground,
                                'borderColor' => $opensBorder,
                                'pointBackgroundColor' => $opensBorder
                            ],
                            [
                                'label' => $engagementLang['clicks'],
                                'data' => array_get($warningInteraction, 'clicks'),
                                'backgroundColor' => $clicksBackground,
                                'borderColor' => $clicksBorder,
                                'pointBackgroundColor' => $clicksBorder
                            ],
                        ],
                        'labels' =>  array_get($warningInteraction, 'dates'),
                        'parent' => $this,
                        'steps' => array_get($warningInteraction, 'steps'),
                        'lapse' => false,
                        'average_opens' => array_get($warningInteraction, 'opens_avg'),
                        'tooltip_title' => null,
                        'tooltip_content' => null
                    ]) ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if (!empty($birthdayWarningInteraction)) : ?>
            <div class="report_row">
                <div class="full_statistic_div statistic_div">
                    <?php $this->insert('statistics::multiple_lines', [
                        'name' => $staffEngagementLang['notify birthday'],
                        'id' => 'user_warning_birthday_rate',
                        'data' => [
                            [
                                'label' => $engagementLang['sent'],
                                'data' => array_get($birthdayWarningInteraction, 'sent'),
                                'backgroundColor' => $sentBackground,
                                'borderColor' => $sentBorder,
                                'pointBackgroundColor' => $sentBorder
                            ],
                            [
                                'label' => $engagementLang['opens'],
                                'data' => array_get($birthdayWarningInteraction, 'opens'),
                                'backgroundColor' => $opensBackground,
                                'borderColor' => $opensBorder,
                                'pointBackgroundColor' => $opensBorder
                            ],
                            [
                                'label' => $engagementLang['clicks'],
                                'data' => array_get($birthdayWarningInteraction, 'clicks'),
                                'backgroundColor' => $clicksBackground,
                                'borderColor' => $clicksBorder,
                                'pointBackgroundColor' => $clicksBorder
                            ],
                        ],
                        'labels' => array_get($birthdayWarningInteraction, 'dates'),
                        'parent' => $this,
                        'steps' => array_get($birthdayWarningInteraction, 'steps'),
                        'lapse' => false,
                        'average_opens' => array_get($birthdayWarningInteraction, 'opens_avg'),
                        'tooltip_title' => null,
                        'tooltip_content' => null
                    ]) ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if (!empty($loyalWarningInteraction)) : ?>
            <div class="report_row">
                <div class="full_statistic_div statistic_div">
                    <?php $this->insert('statistics::multiple_lines', [
                        'name' => $staffEngagementLang['notify return client'],
                        'id' => 'user_loyalty_rate',
                        'data' => [
                            [
                                'label' => $engagementLang['sent'],
                                'data' => array_get($loyalWarningInteraction, 'sent'),
                                'backgroundColor' => $sentBackground,
                                'borderColor' => $sentBorder,
                                'pointBackgroundColor' => $sentBorder
                            ],
                            [
                                'label' => $engagementLang['opens'],
                                'data' => array_get($loyalWarningInteraction, 'opens'),
                                'backgroundColor' => $opensBackground,
                                'borderColor' => $opensBorder,
                                'pointBackgroundColor' => $opensBorder
                            ],
                            [
                                'label' => $engagementLang['clicks'],
                                'data' => array_get($loyalWarningInteraction, 'clicks'),
                                'backgroundColor' => $clicksBackground,
                                'borderColor' => $clicksBorder,
                                'pointBackgroundColor' => $clicksBorder
                            ],
                        ],
                        'labels' => array_get($loyalWarningInteraction, 'dates'),
                        'parent' => $this,
                        'steps' => array_get($loyalWarningInteraction, 'steps'),
                        'lapse' => false,
                        'average_opens' => array_get($loyalWarningInteraction, 'opens_avg'),
                        'tooltip_title' => null,
                        'tooltip_content' => null
                    ]) ?>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
<?php if ((!empty($recurrentClientsTimeline) && !empty($recurrentDays)) || !empty($recurrentAvg) || !empty($recurrentClients)) : ?>
    <div style="page-break-before:always;"></div>
    <h2><?php echo $loyaltyLang['Loyalty stats'] ?></h2>

    <?php if (!empty($recurrentClientsTimeline) && !empty($recurrentDays)) : ?>
        <div class="report_row">
            <div class="full_statistic_div statistic_div">
                <?php $this->insert('statistics::bar', [
                    'name'            => $loyaltyLang['daily_visitors'],
                    'id'              => 'recurrent_visitors',
                    'data'            => $recurrentClientsTimeline,
                    'backgroundColor' => $background,
                    'labels'          => $recurrentDays,
                    'label'           => "",
                    'parent'          => $this,
                    'steps'           => $recurrentClientsSteps,
                    'lapse'           => true,
                    'simple_bar'      => true,
                    'event_click'     => null,
                    'tooltip_title'   => null,
                    'tooltip_content' => null
                ]) ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="report_row">
        <?php if (!empty($recurrentAvg)) : ?>
            <div class="statistic_div">
                <?php $this->insert('statistics::single_number', [
                    'name'            => $loyaltyLang['visitors_recurrents'],
                    'value'           => $recurrentAvg . '%',
                    'label'           => $loyaltyLang['visitors'],
                    'tooltip_title'   => null,
                    'tooltip_content' => null
                ]); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($recurrentClients)) : ?>
            <div class="statistic_div">
                <?php $this->insert('statistics::single_number', [
                    'name'            => $loyaltyLang['total_visitors_recurrents'],
                    'value'           => thousandsCurrencyFormat($recurrentClients),
                    'label'           => $loyaltyLang['visitors'],
                    'tooltip_title'   => null,
                    'tooltip_content' => null,
                ]) ?>
            </div>
        <?php endif; ?>

    </div>
<?php endif; ?>

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

?>

<?php $this->push('scripts') ?>
<script src="<?php echo $this->asset('/public/javascript/isotope.pkgd.min.js') ?>"></script>
<script src="<?php echo $this->asset('/public/javascript/moment.min.js') ?>"></script>
<script src="<?php echo $this->asset('/public/javascript/calendar.min.js') ?>"></script>
<script src="<?php echo $this->asset('/public/javascript/charts.min.js') ?>"></script>
<script src="<?php echo $this->asset('/public/javascript/chartjs-plugin-datalabels.min.js') ?>"></script>
<script src="<?php echo $this->asset('/public/javascript/templates/dashboards.min.js') ?>"></script>
<script src="<?php echo $this->asset('/public/javascript/templates/statistics/doughnut.min.js') ?>"></script>
<script src="<?php echo $this->asset('/public/javascript/templates/statistics/bar.min.js') ?>"></script>
<script src="<?php echo $this->asset('/public/javascript/templates/statistics/bar-interactions.min.js') ?>"></script>
<script src="<?php echo $this->asset('/public/javascript/templates/statistics/horizontalBar.min.js') ?>"></script>
<script src="<?php echo $this->asset('/public/javascript/templates/statistics/line.min.js') ?>"></script>
<script src="<?php echo $this->asset('/public/javascript/templates/statistics/multiple_lines.min.js') ?>"></script>

<?php $this->stop() ?>
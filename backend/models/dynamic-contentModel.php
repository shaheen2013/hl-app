<?php

//======================================================================
// DYNAMIC LANGUAGES
//======================================================================

/**
 * Function that returns all used languages
**/
function getDynamicLanguages()
{
    $cacheName = 'hlDynamicLanguages';
    $cache = getFromCache($cacheName);
    if (!$cache) {
        $sql = "SELECT name, id FROM languages ";
        $row = lecturaArray($sql);

        if ($row) {
            setToCache($cacheName, $row);
        }
    } else {
        $row = $cache->get();
    }
    return $row;
}

//======================================================================
// BRAND CUSTOM CONTENT
//======================================================================

/**
 * Set brand custom content value for a hotel or a chain
 * @param int $hotel_id
 * @param int $chain_id
 * @param string $pageName
 * @param string $pageState
 * @param int $active
 * @param string $configuration
 * @throws Exception
 */
function setBrandCustomContent($hotel_id, $chain_id, $pageName, $pageState, $active, $configuration)
{
    $brand_id = $hotel_id ? $hotel_id : $chain_id;
    $brand_type = $hotel_id ? 'hotel_id' : 'chain_id';
    $sql = "
        INSERT INTO
            brand_custom_content
            ($brand_type, custom_content_id, custom_content_state_id, active, configuration) 
        VALUES
            (
                $brand_id,
                (
                    SELECT
                        id
                    FROM
                         custom_content
                    WHERE
                          name = '$pageName'
                ),
                (
                    SELECT
                       id
                    FROM
                         custom_content_state
                    WHERE
                        name = '$pageState' AND
                        custom_content_id =
                        (
                            SELECT
                                id
                            FROM
                                custom_content
                            WHERE name = '$pageName'
                        )
                ),
                $active,
                '$configuration'
            ) 
            ON DUPLICATE KEY
            UPDATE
                active = VALUES(active),
                configuration = VALUES(configuration),
                custom_content_state_id = VALUES(custom_content_state_id)";

    global $log;
    $log->debug($sql);

    escritura($sql);

    deleteCacheByTag("hlDynamicModule_$pageName" . "_$brand_type=$brand_id");
}

//======================================================================
// DYNAMIC CUSTOM TEXTS
//======================================================================

/**
 * @param string $custom_text_name
 * @param int $hotel_id
 * @param int $chain_id
 * @param string $langName
 * @param string $custom_text_value
 * @return int|string
 * @throws Exception
 */
function setBrandCustomTextsValues($custom_text_name, $hotel_id,$chain_id, $langName, $custom_text_value){
    $brand_id = $hotel_id ? $hotel_id : $chain_id;
    $brand_type = $hotel_id ? 'hotel_id' : 'chain_id';
    $custom_text_name = '{{'.$custom_text_name.'}}';
    $sql ="INSERT INTO brand_custom_texts ($brand_type, value, custom_text_id, language_id) VALUES ($brand_id, '$custom_text_value', (SELECT id FROM custom_texts WHERE name = '$custom_text_name'), (SELECT id FROM languages WHERE name = '$langName')) ON DUPLICATE KEY UPDATE value = '$custom_text_value'";
    return escritura($sql);
}

/**
 * get all custom_texts from a specific module and brand
 * @param int $module_id
 * @param int $hotel_id
 * @param int $chain_id
 * @return array
 */
function getBrandCustomTextsValues($module_id, $hotel_id,$chain_id){
    $brand_id = $hotel_id ? $hotel_id : $chain_id;
    $brand_type = $hotel_id ? 'hotel_id' : 'chain_id';
    $sql = "SELECT custom_texts.name, defaults.value as default_value, brand_values.value, languages.name as language  FROM brand_custom_texts as defaults
LEFT JOIN languages ON languages.id = defaults.language_id
LEFT JOIN custom_texts ON defaults.custom_text_id =custom_texts.id
RIGHT JOIN custom_module_texts ON defaults.custom_text_id = custom_module_texts.custom_text_id
LEFT JOIN brand_custom_texts as brand_values ON languages.id = brand_values.language_id AND brand_values.$brand_type=$brand_id
WHERE custom_module_texts.custom_module_id = $module_id  AND defaults.hotel_id is null and  defaults.chain_id is null";
    $row =  lecturaArray($sql);
    return $row;
}

/**
 * get all custom_texts from a specific module and brand, all them parsed by lang
 * @param int $module_id
 * @param int $hotel_id
 * @param int $chain_id
 * @return array
 */
function getParsedBrandCustomTextsValues($module_id, $hotel_id,$chain_id){
    $brandCustomTexts = getBrandCustomTextsValues($module_id, $hotel_id,$chain_id);
    $langs = getDynamicLanguages();
    $parsedBrandCustomTexts = [];
    foreach ($langs as $lang){
        foreach ($brandCustomTexts as $brandCustomText){
            if(array_get($brandCustomText,'language') == array_get($lang, 'name')){
                $parsedBrandCustomTexts[array_get($lang, 'name')][]=$brandCustomText;
            }
        }
    }
    return $parsedBrandCustomTexts;
}
//======================================================================
// DYNAMIC CUSTOM VARS
//======================================================================

/**
 * Returns all custom vars names and description from the gave moduleName
 * @param string $moduleName
 * @return array|mixed
 */
function getModuleVarsByModuleName($moduleName)
{
    $cacheName = "hlModuleVars_$moduleName";
    $cache = getFromCache($cacheName);
    if (!$cache) {
        $sql = "SELECT name, description FROM custom_module_vars WHERE custom_module_id = (SELECT id FROM custom_module where name = $moduleName) ";
        $row = lecturaArray($sql);
        if ($row) {
            setToCache($cacheName, $row);
        }
    } else {
        $row = $cache->get();
    }
    return $row;
}

/**
 * Set value to a custom_var linked to a brand (hotel_id or chain_id)
 * @param int $hotel_id
 * @param int $chain_id
 * @param string $varName
 * @param string $varValue
 * @throws Exception
 */
function setBrandCustomVars($hotel_id, $chain_id, $varName, $varValue)
{
    $con = conectar();

    $brand_id = $hotel_id ? $hotel_id : $chain_id;
    $brand_type = $hotel_id ? 'hotel_id' : 'chain_id';
    $varName = '{{'.$varName.'}}';

    $varValue = mysqli_real_escape_string($con, $varValue);

    $sql="INSERT INTO brand_custom_vars ($brand_type, value, custom_vars_id) VALUES ($brand_id, '$varValue', (SELECT id FROM custom_vars WHERE name = '$varName')) ON DUPLICATE KEY UPDATE value = '$varValue'";

    escritura($sql, $con);
}

/**
 * gets all custom_vars values linked to a brand (hotel_id or chain_id)
 * @param int $module_id
 * @param int $hotel_id
 * @param int $chain_id
 * @return array|mixed
 */
function getBrandCustomVarsValues($module_id, $hotel_id, $chain_id)
{
    $chainLJcondition = "is null";
    if ($chain_id) {
        $chainLJcondition = " = $chain_id ";
    }
    $sql = "SELECT custom_vars.name,brand_custom_vars.value, defaults.value as default_value, chain_vars.value as chain_value FROM custom_vars
LEFT JOIN brand_custom_vars ON custom_vars.id = brand_custom_vars.custom_vars_id AND brand_custom_vars.hotel_id = $hotel_id
LEFT JOIN brand_custom_vars as defaults ON custom_vars.id = defaults.custom_vars_id AND defaults.hotel_id is null AND defaults.chain_id is null
LEFT JOIN brand_custom_vars as chain_vars ON custom_vars.id = chain_vars.custom_vars_id AND chain_vars.hotel_id is null AND chain_vars.chain_id $chainLJcondition
RIGHT JOIN custom_module_vars ON custom_module_vars.custom_vars_id = custom_vars.id
WHERE custom_module_vars.custom_module_id = $module_id";

    return lecturaArray($sql);
}

function getCustomVarsDefaultValue($custom_var_id) {
    $sql = "SELECT value FROM brand_custom_vars where hotel_id IS NULL AND chain_id IS NULL and custom_vars_id = $custom_var_id";
    
    return lecturaArray($sql);
} 

//======================================================================
// DYNAMIC CUSTOM MODULE CONTENT
//======================================================================

/**
 * function that sets module content for a specific brand (hotel or chain, if both are get, hotel will be set)
 * @param int $hotel_id
 * @param int $chain_id
 * @param string $custom_texts
 * @param int $active
 * @param string$pageName
 * @param string $moduleName
 * @throws Exception
 */
function setBrandCustomModuleContent($hotel_id, $chain_id, $custom_texts, $active, $pageName, $moduleName)
{
    $brand_id = $hotel_id ? $hotel_id : $chain_id;
    $brand_type = $hotel_id ? 'hotel_id' : 'chain_id';
    $langs = getDynamicLanguages();
    foreach ($langs as $lang) {
        $language = array_get($lang, 'name');
        $custom_text = array_get($custom_texts, $language);
        $custom_text = mysqli_real_escape_string(conectar(), $custom_text);
        $sql = "INSERT INTO brand_custom_module_content (brand_custom_content_id, custom_module_id, language_id, content, active)
VALUES ((SELECT id FROM brand_custom_content WHERE $brand_type=$brand_id AND custom_content_id = (SELECT id FROM custom_content WHERE name = '$pageName'))
, (SELECT id FROM custom_module WHERE name = '$moduleName'), (SELECT id FROM languages WHERE name='$language'), '$custom_text', $active) ON DUPLICATE KEY UPDATE active = $active, content='$custom_text', custom_module_id = (SELECT id FROM custom_module WHERE name = '$moduleName')";
        escritura($sql);
    }

    deleteCacheByTag("hlDynamicModule_$pageName" . "_$brand_type=$brand_id");
}

/**
 * function that gets all info from a brand_custom_module_content (hotel, chain and default values)
 * @param $hotel_id
 * @param $chain_id
 * @param $pageName
 * @param $moduleName
 * @param bool $active
 * @return array|mixed
 */
function getHotelCustomContentId($hotel_id, $chain_id, $pageName, $moduleName, $active = false)
{
    $cacheName = "hlDynamicModule_$pageName" . "_hotel_id=$hotel_id" . "_chain_id=$chain_id"."_active=$active". '_module=' . $moduleName;
    $cacheTags = [
        "hlDynamicModule_$pageName" . "_chain_id=$chain_id",
        "hlDynamicModule_$pageName" . "_hotel_id=$hotel_id",
        "hlDynamicModule_$pageName" . "_hotel_id=$hotel_id" . '_module=' . $moduleName
    ];

    $activeWhere = '';
    $chainActiveWhere ='';
    if ($active) {
        $activeWhere = ' AND brand_custom_module_content.active=1';
        $chainActiveWhere = 'AND chain_content.active=1';
    }
    $chainSearch = 'in (0)';
    if ($chain_id) {
        $chainSearch = " = $chain_id";
    }
    $cache = getFromCache($cacheName);

    if (!$cache) {
        $sql = "
            SELECT
                brand_custom_module_content.content,
                chain_content.content as chain_content,
                chain_content.active as chain_module_active,
                defaults.content as default_content,
                brand_custom_content.configuration as configuration,
                languages.name as lang,
                custom_content.name as custom_content,
                custom_module.id as custom_module_id,
                custom_module.name as custom_module,
                custom_content_state.name as state,
                brand_custom_module_content.active as module_active,
                brand_custom_content.active as content_active 
            FROM
                brand_custom_module_content as defaults  
            LEFT JOIN
                languages
            ON
                defaults.language_id = languages.id
            LEFT JOIN
                custom_content
            ON
                custom_content.name = '$pageName'
            RIGHT JOIN
                custom_module
            ON
                custom_module.id = defaults.custom_module_id  
            LEFT JOIN
                brand_custom_content
            ON
                brand_custom_content.custom_content_id = custom_content.id AND
                brand_custom_content.hotel_id = $hotel_id
            LEFT JOIN
                brand_custom_content as chain_custom_content
            ON
                chain_custom_content.custom_content_id = custom_content.id AND
                chain_custom_content.chain_id $chainSearch 
            LEFT JOIN
                custom_content_state
            ON
                brand_custom_content.custom_content_state_id = custom_content_state.id 
            LEFT JOIN
                brand_custom_module_content
            ON
                brand_custom_module_content.id != defaults.id AND
                brand_custom_module_content.custom_module_id = defaults.custom_module_id AND
                brand_custom_module_content.language_id = defaults.language_id AND
                brand_custom_module_content.brand_custom_content_id=brand_custom_content.id $activeWhere
            LEFT JOIN
                brand_custom_module_content as chain_content
            ON
                chain_content.id != defaults.id AND
                chain_content.custom_module_id = defaults.custom_module_id AND
                chain_content.language_id = defaults.language_id AND
                chain_content.brand_custom_content_id=chain_custom_content.id  $chainActiveWhere 
            WHERE
                defaults.brand_custom_content_id is null AND
                custom_module.name = '$moduleName'";


        $row = lecturaArray($sql);
        if ($row) {
            setToCache($cacheName, $row, 604800, $cacheTags);
        }
    } else {
        $row = $cache->get();
    }

    return $row;
}

/**
 * function that gets all info from a brand_custom_module_content (hotel, chain and default values) and parses it to ease
 * its readability
 * @param int $hotel_id
 * @param int $chain_id
 * @param string $pageName
 * @param string $moduleName
 * @param int $active
 * @return null
 */
function getParsedHotelCustomContentId($hotel_id, $chain_id, $pageName, $moduleName, $active = 0)
{
    $hotelModules = getHotelCustomContentId($hotel_id, $chain_id, $pageName, $moduleName, $active);
    $parsedHotelModule = null;
    $arrayIntroAndReturn = array("\n", "\r");
    foreach ($hotelModules as $hotelModule) {
        $hotelContentEscaped = addslashes(array_get($hotelModule, 'content') ?? '');
        $parsedHotelModule[array_get($hotelModule, 'lang')] = str_replace($arrayIntroAndReturn, '' , $hotelContentEscaped);
        $chainContentEscaped = addslashes(array_get($hotelModule, 'chain_content') ?? '');
        $parsedHotelModule['chain_info'][array_get($hotelModule, 'lang')] = str_replace($arrayIntroAndReturn, '' , $chainContentEscaped);
        $parsedHotelModule['default'][array_get($hotelModule, 'lang')] = array_get($hotelModule, 'default_content');
    }
    $parsedHotelModule['active'] = array_get($hotelModules, '0.module_active');
    $parsedHotelModule['state'] = array_get($hotelModules, '0.state');
    $parsedHotelModule['configuration'] = array_get($hotelModules, '0.configuration');
    $parsedHotelModule['module_id'] = array_get($hotelModules, '0.custom_module_id');
    $parsedHotelModule['chain_active'] = array_get($hotelModules, '0.chain_module_active');

    return $parsedHotelModule;
}

/**
 * Returns brand_custom_module_content value to display grouped by languages
 * @param int $hotel_id
 * @param int $chain_id
 * @param string $pageName
 * @param string $moduleName
 * @param int $active
 * @return array
 */
function getBrandCustomContent($hotel_id, $chain_id, $pageName, $moduleName, $active = 1)
{
    $langs = getDynamicLanguages();
    $brandContent = getParsedHotelCustomContentId($hotel_id, $chain_id, $pageName, $moduleName, $active);
    $brandVars = [];
    $brandTexts = [];
    if (array_get($brandContent, 'module_id')) {
        $brandVars = getBrandCustomVarsValues(array_get($brandContent, 'module_id'), $hotel_id, $chain_id);
        $brandTexts = getParsedBrandCustomTextsValues(array_get($brandContent, 'module_id'), $hotel_id, $chain_id);
    }
    if ($brandContent) {
        $brandConfiguration=array_get($brandContent, 'configuration');

        if ($brandConfiguration=='default') {
            $contentIndex='default.';
            $secondaryAltContentIndex='';
            $altContentIndex='';
            $varsIndex='default_value';
            $secondaryVars = '';
            $altVarsIndex ='';
        } elseif ($brandConfiguration =='own_vars') {
            $contentIndex='default.';
            $secondaryAltContentIndex='';
            $altContentIndex='';
            $varsIndex='value';
            $altVarsIndex = 'chain_value';
            $secondaryVars  ='default_value';
        } else {
            $contentIndex='';
            $altContentIndex='chain_info.';
            $secondaryAltContentIndex='default.';
            $varsIndex='value';
            $altVarsIndex = 'chain_value';
            $secondaryVars  ='default_value';
        }

        foreach ($langs as $lang) {
            $langName = array_get($lang, 'name');
            $brandText = array_get($brandTexts, $langName);
            $brandContent[$langName] = $content = array_get($brandContent, $contentIndex.$langName)?array_get($brandContent, $contentIndex.$langName):(array_get($brandContent, $altContentIndex.$langName)?array_get($brandContent, $altContentIndex.$langName):array_get($brandContent, $secondaryAltContentIndex.$langName));
            if ($brandVars) {
                foreach ($brandVars as $brandVar) {
                    $strToReplace = array_get($brandVar, 'name');
                    $strReplace = array_get($brandVar, $varsIndex, null) ? array_get($brandVar, $varsIndex) : (array_get($brandVar, $altVarsIndex) ? array_get($brandVar, $altVarsIndex) : array_get($brandVar, $secondaryVars));
                    $content = str_replace($strToReplace, $strReplace, $content);
                    $brandContent[$langName] = $content;
                }
            }
            if ($brandText) {
                foreach ($brandText as $text) {
                    $strToReplace = array_get($text, 'name');
                    $strReplace = array_get($text, 'value', null) ? array_get($text, 'value') : (array_get($text, 'chain_value') ? array_get($text, 'chain_value') : array_get($text, 'default_value'));
                    $content = str_replace($strToReplace, $strReplace, $content);
                    $brandContent[$langName] = $content;
                }
            }
            $brandContent[$langName] = stripslashes($brandContent[$langName]);
        }
        return $brandContent;
    }
}

<?php

/**
 * @param $chain_id
 * @return array|mixed|null
 * @throws Exception
 */
function getBrandEprivacyInfo($chain_id){
    $cacheName = 'brand_eprivacy_chain_' . $chain_id;
    $cache = getFromCache($cacheName);
    if(!$cache){
    $sql="SELECT company_name,company_address,company_nif,company_email, restricted_portal, CASE WHEN max(products.producto) IS NOT NULL THEN 1 ELSE 0 END as not_hotel 
    FROM 
        brand_eprivacy 
    LEFT JOIN 
        cadena ON brand_eprivacy.chain_id = cadena.id
    LEFT JOIN 
        brands on brands.chain_id = cadena.id
    LEFT JOIN 
        brand_product ON brand_product.brand_id = brands.id AND brand_product.active = 1
    LEFT JOIN 
        products ON products.id = brand_product.product_id AND producto = 'not_hotel'
    WHERE 
        cadena.id = $chain_id
    GROUP BY 
        brands.id";

        $row =  lectura($sql);
        if($row){
            $tags = array ('brand_eprivacy_' . $chain_id);
            setToCache($cacheName, $row, 31536000, $tags);
        }
    } else {
        //Get result from cache
        $row = $cache->get();
    }

    return $row;
}

/**
 * @param $chain_id
 * @param $company_name
 * @param $company_address
 * @param $company_nif
 * @param $company_email
 * @param $restricted_portal
 * @throws Exception
 */
function setBrandEprivacyInfo($chain_id, $company_name, $company_address, $company_nif, $company_email, $restricted_portal){
    $sql = "INSERT INTO brand_eprivacy (chain_id,
company_name,
company_address,
company_nif,
company_email, restricted_portal) VALUES ($chain_id, '$company_name', '$company_address', '$company_nif', '$company_email', $restricted_portal) ON DUPLICATE KEY UPDATE
company_name='$company_name',
company_address='$company_address',
company_nif='$company_nif',
company_email='$company_email',
restricted_portal=$restricted_portal
";
    escritura($sql);
    $cacheName = 'brand_eprivacy_chain_' . $chain_id;
    deleteCacheByKey($cacheName);

}

function getEprivacyPermisions($brandParentID){
    global $log;
    $log->info("Fetching ePrivacy permissions for brand parent ID: $brandParentID");
    $sql = "SELECT brands.id, max(products.producto) as active
        FROM brands 
        LEFT JOIN brand_product ON 
            brand_product.brand_id = brands.id AND brand_product.active = 1
        LEFT JOIN products ON 
            products.id = brand_product.product_id AND products.producto = 'eprivacy_responsible'
        LEFT JOIN hoteles ON hoteles.id = brands.hotel_id 
        WHERE brands.parent_id = $brandParentID AND active IS NULL AND hoteles.activated=1
        GROUP BY brands.id";
    
    return empty(lecturaArray($sql));
}

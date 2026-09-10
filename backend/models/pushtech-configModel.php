<?php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//Include pushtech api LIB
include_once LIB . 'pushtech_api.php';
/**
 * Get a list of all active offers
 * @param $id_hotel
 * @return array of offers
 */
function ObtainOfferList($id, $chain_id)
{
    $cache = getFromCache('offerList_' . $id);
    if (!$cache) {
        $con = conectar(1);
        $id = mysqli_real_escape_string($con, $id);
        $chain_id = mysqli_real_escape_string($con, $chain_id);

        $sql = "SELECT hotel_oferta_lang.id_oferta, hotel_oferta_lang.nombre
            FROM hotel_oferta_lang
            INNER JOIN hotel_oferta 
            ON hotel_oferta.id = hotel_oferta_lang.id_oferta";

        if(!empty($chain_id)){
            $sql .= " WHERE hotel_oferta.id_cadena = '$chain_id'";
        }else{
            $sql .= " WHERE hotel_oferta.id_hotel = '$id'";
        }

        $sql .= " AND hotel_oferta_lang.lang = '" . $_SESSION['userLang'] . "'";
        $row = lecturaArray($sql, $con);
        //Set cache
        if ($row) {
            setToCache('offerList_' . $id, $row, 150);
        }
    } else {
        $row = $cache->get();
    }

    return $row;
}


/**
 * Upsert campaign on database from Hotelinking Dashboard
 * @param $campaign
 * @param $offer
 * @param $mapping_id
 */
function upsertCampaign($id_campaign, $id_offer, $days_valid = NULL, $id_hotel, $id_chain = NULL, $id_mapping = NULL)
{
    $con = conectar();

    $id_campaign = mysqli_real_escape_string($con, $id_campaign);
    $id_offer = mysqli_real_escape_string($con, $id_offer);
    $id_hotel = mysqli_real_escape_string($con, $id_hotel);
    $id_mapping = mysqli_real_escape_string($con, $id_mapping);
    $id_chain = mysqli_real_escape_string($con, $id_chain);

    if(!empty($id_mapping)){
        
        //if its an update get the old values for the key id_mapping
        $con2 = conectar();
        $mapping = lectura("SELECT id_campaign, id_offer FROM pushtech_campaigns_mappings WHERE id = $id_mapping", $con2);
        $old_id_campaign = $mapping['id_campaign'];
        $old_id_offer = $mapping['id_offer'];

        //update id_campaign, id_offer only for the hotels of the chain that have the old values of id_campaing and id_offer
        $sql = "UPDATE pushtech_campaigns_mappings SET id_campaign = '$id_campaign', id_offer = '$id_offer', days_valid = '$days_valid' WHERE id_campaign='$old_id_campaign' AND id_offer='$old_id_offer' AND ";

        //change query if its a unique hotel or if it's part of a chain
        if(empty($id_chain)){
            $sql.="id_hotel='$id_hotel'";
        } else {
            $sql.="id_cadena= '$id_chain'";
        }

    }else{
        $sql = "INSERT INTO pushtech_campaigns_mappings (id_campaign, id_offer, id_hotel, id_cadena, days_valid) VALUES ";

        //change query if its a unique hotel or if it's part of a chain
        if (empty($id_chain)) {
            $query_values[] = "('$id_campaign', '$id_offer', '$id_hotel', '$id_chain', '$days_valid')"; 
        } else {

            //get hotel_ids for the chain
            $hotel_ids = getHotelIDsForChainID($id_chain);
            
            //for each hotel of the chain add a value row to be inserted for the query 
            foreach($hotel_ids as $id){
                $query_values[] = "('$id_campaign', '$id_offer', ".$id.", '$id_chain', '$days_valid')"; 
            }
        }
        
        //concat the values to the query
        $sql .= implode(',', $query_values);

    }


    //created in redeem-email-campaign
    deleteCacheByHotelOrChain('pushtech_mapped_offers', $id_hotel);
    deleteCacheByHotelOrChain('pushtech_mapped_campaigns', $id_hotel);

    //write to db but dont close the connection
    escritura($sql, $con, false);
    
    //check if something changed
    if(mysqli_affected_rows($con) > 0 ){
        desconectar($con);
        return 1;
    } else {
        desconectar($con);
        return 0;
    }
}

/**
 * Delete a mapping from database
 * @param $id
 * @param $id_hotel
 * @return int|string
 */

function deleteMapping($id, $id_hotel, $id_chain)
{

    $con = conectar();
    $id = mysqli_real_escape_string($con, $id);
    $id_hotel = mysqli_real_escape_string($con, $id_hotel);
    $id_chain = mysqli_real_escape_string($con, $id_chain);

    //change query if its a unique hotel or if it's part of a chain
    if (empty($id_chain)) {
        $sql = "DELETE FROM pushtech_campaigns_mappings WHERE id = $id";
        $result = escritura($sql, $con, false);
    } else {
        $con2 = conectar();
        $mapping = lectura("SELECT id_campaign, id_offer FROM pushtech_campaigns_mappings WHERE id = $id", $con2);
        $old_id_campaign = $mapping['id_campaign'];
        $old_id_offer = $mapping['id_offer'];
        $sql = "DELETE FROM pushtech_campaigns_mappings WHERE id_cadena = $id_chain AND id_campaign='$old_id_campaign' AND id_offer='$old_id_offer'";
        $result = escritura($sql, $con, false);
    }

    //if rows have been affected then deleted worked
    if(mysqli_affected_rows($con) > 0 ){

        //created in redeem-email-campaign
        deleteCacheByHotelOrChain('pushtech_mapped_campaigns', $id_hotel);
        deleteCacheByHotelOrChain('pushtech_mapped_offers', $id_hotel);
        
        desconectar($con);
        return 1;
    } else {
        desconectar($con);
        return 0;
    }
}

/**
 * Get hotel booking engine
 * @param $id_hotel
 * @return array|null
 */
function getBookingEngine($id_hotel){
    $con = conectar();
    $id_hotel = mysqli_real_escape_string($con, $id_hotel);
    $sql = "SELECT booking_engine FROM hoteles WHERE id = $id_hotel";
    $result = lectura($sql, $con);
    return $result['booking_engine'];
}
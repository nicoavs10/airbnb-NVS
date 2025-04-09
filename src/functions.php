<?php

/* Add your functions here */


function dbConnect(){
    // defined in config.php but restate credentials
    $servername = "mysqlServer";
    $username = "fakeAirbnbUser";
    $password = "apples11Million";
    $database = "fakeAirbnb";
    $dbport = "3306";

    try {
        $db = new PDO("mysql:host=$servername;dbname=$database;charset=utf8mb4;port=$dbport", $username, $password);
    }
    catch(PDOException $e) {
        echo $e->getMessage();
    }
    return $db;
}

// include config file
require_once(__DIR__ . '/../config/config.php');

// neighborhoods in alphabetical order
function getNeighborhoods($db) {
    $stmt = $db->prepare("SELECT * FROM neighborhoods ORDER BY neighborhood ASC");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// list of room types in alphabetical order
function getRoomTypes($db) {
    $stmt = $db->prepare("SELECT DISTINCT type AS roomType FROM roomTypes ORDER BY type ASC");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function getListings($db, $neighborhoodId, $roomType, $guests) {
    // Start building the basic SQL query to get listing information
    $sqlQuery = "SELECT l.id, l.name, l.pictureUrl, l.accommodates, l.price, l.rating, 
              n.neighborhood, rt.type as roomType
              FROM listings l
              JOIN neighborhoods n ON n.id = l.neighborhoodId
              JOIN roomTypes rt ON rt.id = l.roomTypeId";
    
    
    $filterConditions = []; // WHERE conditions (such as "price > 100")
    $filterValues = [];     // actual values like "100"
    

    // store neighborhood condition and value IF a SPECIFIC neighborhood was chosen
    if ($neighborhoodId) {
        $filterConditions[] = "n.id = ?"; 
        $filterValues[] = $neighborhoodId; 
    }
    
    if ($roomType) {
        $filterConditions[] = "rt.type = ?"; 
        $filterValues[] = $roomType;
    }
    
    if ($guests) {
        $filterConditions[] = "l.accommodates >= ?"; // make sure we have more than the specified accomdations
        $filterValues[] = $guests;
    }
    

    // This adds the conditions to the SQL statement -- WHERE
    if (count($filterConditions) > 0) {
        $sqlQuery .= " WHERE " . implode(" AND ", $filterConditions);
    }
    // LIMIT TO 20 listings
    $sqlQuery .= " ORDER BY RAND() LIMIT 20";
    
    $preparedStatement = $db->prepare($sqlQuery);
    $preparedStatement->execute($filterValues);
    $listingResults = $preparedStatement->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($listingResults as $index => $listingItem) {
        // trying to correctly format dollars
        $listingResults[$index]['price'] = number_format((float)$listingItem['price'], 2, '.', '');
    }
    
    // Return the list of listings
    return $listingResults;
}

function getListingDetails($db, $id) {
    // sql statement for neighborhood, room type, and host name from given id
    $stmt = $db->prepare("SELECT l.*, n.neighborhood, rt.type as roomType, h.hostName as host,
        (SELECT GROUP_CONCAT(a.amenity SEPARATOR ', ')
         FROM listingAmenities la
         JOIN amenities a ON a.id = la.amenityID
         WHERE la.listingID = l.id) as amenities
         FROM listings l
         JOIN neighborhoods n ON n.id = l.neighborhoodId
         JOIN roomTypes rt ON rt.id = l.roomTypeId
         JOIN hosts h ON h.id = l.hostId
         WHERE l.id = ?");
    $stmt->execute([$id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Trying to get price to work correctly
    if ($result && isset($result['price'])) {
        $result['price'] = number_format((float)$result['price'], 2, '.', '');
    }
    
    return $result;
}

?>



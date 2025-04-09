<?php
/* PHP code for AJAX interaction goes here */
// Include functions to handle the database queries.

require_once("functions.php");

$db = dbConnect();

if (isset($_GET['id'])) {
    $listingId = $_GET['id'];
    
    // Basic query to get listing details
    $query = "SELECT l.*, h.hostName as host_name, n.neighborhood as neighborhood_name 
             FROM listings l 
             LEFT JOIN hosts h ON l.hostId = h.id 
             LEFT JOIN neighborhoods n ON l.neighborhoodId = n.id
             WHERE l.id = ?";
    
    $stmt = $db->prepare($query);
    $stmt->execute([$listingId]);
    $listing = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Trying to get price formatted properly
    $listing['price'] = number_format((float)$listing['price'], 2, '.', '');
    
    // Get amenities
    $amenitiesQuery = "SELECT a.amenity 
                      FROM listingAmenities la 
                      JOIN amenities a ON la.amenityId = a.id 
                      WHERE la.listingId = ?";
    
    $amenStmt = $db->prepare($amenitiesQuery);
    $amenStmt->execute([$listingId]);
    $amenities = $amenStmt->fetchAll(PDO::FETCH_COLUMN);
    
    // Add amenities as a comma-separated string
    $listing['amenities'] = implode(', ', $amenities);
    

    echo json_encode($listing);
}

?>

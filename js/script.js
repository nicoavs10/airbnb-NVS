// // javascript goes here

$(document).ready(function() {
  // Event listener for View buttons 
  $('.viewListing').on('click', function() {
    // Get listing ID from the button ID
    const listingId = $(this).attr('id');
    
    console.log('Fetching details for listing ID:', listingId);
    
    // AJAX fetch listing details
    $.ajax({
      url: 'src/ajax.php',
      type: 'GET',
      data: {
        action: 'getListingDetails',
        id: listingId
      },
      dataType: 'json',
      success: function(response) {        
        
        // Update modal content with the listing details
        $('#modal-title').text(response.name);
        $('#modal-image img').attr('src', response.pictureUrl);
        
        // update footer w/ listing details
        let footerHTML = `
          <p>${response.neighborhood_name || 'Neighborhood not available'}</p>
          <p>$${parseFloat(response.price).toFixed(2)} / night</p>
          <p>Accommodates ${response.accommodates}</p>
          <p><i class="bi bi-star-fill"></i> ${parseFloat(response.rating).toFixed(2)}</p>
          <p>Hosted by ${response.host_name || 'Unknown Host'}</p>
        `;
        
        // Add amenities if available
        if (response.amenities) {
          footerHTML += `<p>Amenities: ${response.amenities}</p>`;
        }
        
        footerHTML += `<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>`;
        
        $('.modal-footer').html(footerHTML);
      }
    });
  });
});
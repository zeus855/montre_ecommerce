$(function() {
    $('.js-quantity').on('click', function(e){
      e.preventDefault();
        let quantity = $(this).data('value');
      let montreId = $(this).data('montre');
      
      if (quantity) {
         window.location.href = `update/${montreId}/element/${quantity}`;
      }
    });

    $('.js-moins_quantity').on('click', function(e){
      e.preventDefault();
      let quantity = $(this).data('value');
    let montreId = $(this).data('montre');
    
    if (quantity) {
       window.location.href = `update-moins/${montreId}/element/${quantity}`;
    }
  });


  $('.js-delete_panier').on('click', function(e){
    e.preventDefault();
    let montreId = $(this).data('montre');
  
  
     window.location.href = `update-delete/${montreId}/element/`;
     
  
});
  });
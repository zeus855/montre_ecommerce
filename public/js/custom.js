$(function() {
    $('.js-quantity').on('change', function(e){
        let quantity = $(this).val();
      let montreId = $(this).data('montre');
      
      if (quantity) {
         window.location.href = `update/${montreId}/element/${quantity}`;
      }
    });
  });
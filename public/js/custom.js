$(function () {
  $(".js-quantity").on("click", function (e) {
    e.preventDefault();
    let quantity = $(this).data("value");
    let montreId = $(this).data("montre");

    if (quantity) {
      window.location.href = `update/${montreId}/element/${quantity}`;
    }
  });

  $(".js-moins_quantity").on("click", function (e) {
    e.preventDefault();
    let quantity = $(this).data("value");
    let montreId = $(this).data("montre");

    if (quantity) {
      window.location.href = `update-moins/${montreId}/element/${quantity}`;
    }
  });

  $(".js-delete_panier").on("click", function (e) {
    e.preventDefault();
    let montreId = $(this).data("montre");

    window.location.href = `update-delete/${montreId}/element/`;
  });

  // Gestion des adresses de livraison et de facturation

  $(".js-show-facturation").on("change", function (e) {
    $(".js-facturations").toggleClass("d-none");
  });

  $(".js-selected").on("change", function (e) {
    if (
      $(".js-facturations").hasClass("d-none") &&
      $(this).data("type") == "livraison"
    ) {
      $(".js-paiment").toggleClass("d-none");
    } else {
      if ($(".js-selected:checked").length == 2) {
        $(".js-paiment").toggleClass("d-none");
      }
    }
  });

  // $(".js-delete_adresse").on("click", function (e) {
  //   e.preventDefault();
  //   let adresseId = $(this).data("adresse");

  //   window.location.href = `panier/delete_adresses/${adresseId}`;
  // });

  $(".js-paiment").on("click", function (e) {
    result = [];
    $(".js-selected:checked").each(function (index, value) {
      console.log(index, value);
      $id = $(value).data("id");
      $type = $(value).data("type");
      if ($.trim(value)) {
        result[$type] = $id;
      }
    });

    $.ajax({
      url: "/selection/adresse",
      data: { result: result },
      success: function (data) {
        window.location.href = data["url"];
      },
    });
  });

  // ----------------------------------------------------
});

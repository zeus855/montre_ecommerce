$(function () {
  $(".js-quantity").on("click", function (e) {
    e.preventDefault();
    //Recuperation de la quantité
    let quantity = $(this).data("value");
    // Recuperation de l'id de montre
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
      // Permet la redirection 
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
    var res = [];
    $(".js-selected:checked").each(function (index, value) {
      $id = $(value).data("id");
      $type = $(value).data("type");
      if ($.trim(value)) {
        res[$type] = $id;
      }
    });

    var urlParam =
      "/selection/adresse?livraison=" +
      res.livraison +
      "&facturation=" +
      res.facturation;

    $.ajax({
      method: "GET",
      url: urlParam,
      success: function (data) {
        window.location.href = data["url"];
      },
      error: function (xhr, status, erreur) {
        // La fonction appelée en cas d'échec de la requête
        console.error("Erreur Ajax: " + status + " - " + erreur);
      },
    });
  });

  // ----------------------------------------------------
});

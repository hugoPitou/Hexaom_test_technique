/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';

//jQuery
import $ from 'jquery';
 
// Datatable (plugin jquery) pour bootstrap
import 'datatables.net/js/jquery.dataTables.js';
import 'datatables.net-bs5/js/dataTables.bootstrap5';
import 'datatables.net-bs5/css/dataTables.bootstrap5.css';

//mapbox token access
mapboxgl.accessToken = 'pk.eyJ1IjoiZ29nb3BpdCIsImEiOiJja3Z0YTVvem00dXEyMndxNXBqaWFlM3ZpIn0.iejYvIbU5kkaGd-u964FUg';

/*************** Creation de l'element map de mapbox ********************************************/
if (document.getElementById('map')) {
  var map = new mapboxgl.Map({
      container: 'map',
      style: 'mapbox://styles/mapbox/streets-v11', // Use your preferred Mapbox style
      center: [2.056, 47.150], // Specify the center coordinates
      zoom: 5.5, // Specify the zoom level
  });
}

$(document).ready(function () {

  /**************** Afiicher de la map avec mapbox  ************************************************/

  if (document.getElementById('map')) {

    var dataContainer = document.getElementById('data-container');
  
    var jsonData = dataContainer.getAttribute('data-my-data');

    var parsedData = JSON.parse(jsonData);
    
    for (var departement in parsedData) {
      if (parsedData.hasOwnProperty(departement)) {
        var innerObject = parsedData[departement];
        //console.log("Département : " + departement);
        
        for (var fullname in innerObject) {
          if (innerObject.hasOwnProperty(fullname)) {
            var Coordinate = innerObject[fullname];
            /*console.log("Nom : " + fullname);
            console.log("coordonnées : " + Coordinate);*/
  
            var marker = new mapboxgl.Marker()
              .setLngLat([Coordinate[0], Coordinate[1]]) // Coordinates [longitude, latitude]
              .setPopup(new mapboxgl.Popup().setHTML("<p class='mb-0 mt-2 mx-2' style='font-family: Century_Gothic;font-size: 14px;'>"+fullname+"</p>"))
              .addTo(map);
          }
        }
      }
    }
  }

  /************ creation de la table contenant les contacts grace a datatables  **********************/

  $('.table-datatable').DataTable({
    language: {
      info: "_START_ - _END_ sur _TOTAL_",
      lengthMenu: "Lignes par page : _MENU_",
    },
    dom: 'rt<"bottom d-flex align-items-center justify-content-end me-3"lip><"clear">', // Configurez l'agencement des éléments de DataTables
  });

  $('.dataTables_info').addClass('pt-0 px-4 d-flex align-items-center');

  $('#customSearch').on('keyup', function() {
    var searchValue = $(this).val();
    console.log(searchValue);
    var dataTable = $('.table-datatable').DataTable();

    dataTable.search(searchValue).search(searchValue).draw();
  });

  /******* afficher en details la ligne du contact sur lequel on click *********/ 

  loadContactDetailsAtPageLink();
  loadContactDetails();

  /******* erreur dans le formulaire de contact *********/ 
  
  $(".form-contact").find("ul").each(function(index, element) {
    // "index" est l'index de l'élément actuel dans la collection
    // "element" est l'élément DOM actuel (l'élément <ul> en l'occurrence)
    $(element).parent().find("input").addClass("is-invalid");
    $(element).remove();
  });
 

});

function loadContactDetailsAtPageLink() {
  $('.page-link').on('click', function () {
    $(document).ready(function () {
      loadContactDetailsAtPageLink();
      loadContactDetails();
    });
  });
};

function loadContactDetails() {
  $('.ligne').on('click', function () {
    // Récupérez la valeur de l'attribut "data-my-data" (contact.id)
    const contactId = $(this).data('my-data');
    $.ajax({
      url: '/contact/'+contactId, 
      type: 'GET',
      success: function (response) {
        const contactDetails = $(response).find('#show_container');
        $('#liste_contacts').removeClass('w-100');
        $('#liste_contacts').css({'width':'60%'});
        $('#contact-details-container').css({'width':'40%'});
        $('#contact-details-container').html(contactDetails);
      }
    });
  });
}
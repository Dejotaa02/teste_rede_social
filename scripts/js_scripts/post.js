//EXIBE O NOME DO ARQUIVO SELECIONADO
const input = document.getElementById("foto");
const fileName = document.getElementById("file-name");
console.log("teste teste teste")
console.log("teste teste teste")
console.log("teste teste teste")
console.log("teste teste teste")

input.addEventListener("change", function () {
  if (input.files.length > 0) {
    fileName.textContent = `Imagem selecionada: ${input.files[0].name}`;
  }
});

async function initMap(){

    let mapOptions = {
        center: new google.maps.LatLng('-23.229135232748778', '-46.95588304195056'),
        zoom: 12
    }
    let map = new google.maps.Map(document.getElementById("map"), mapOptions);

    //Objeto de opções do marcador
    // let markerObject = {
    //   position: new google.maps.LatLng('-23.229135232748778', '-46.95588304195056'),
    //   map: map,
    //   title: "Encontrei aqui",
    //   icon: '../assets/marcador_mapa_redimensionado.png',
    //   optimized: false,
    // }
    // //Instância da classe marker.
    // let marker = new google.maps.Marker(markerObject);


    let newCoordinates = ""

    let marker; 

    map.addListener('click', (event) => {
        placeMarker(event.latLng, map);
        newCoordinates = JSON.stringify(event.latLng.toJSON(), null, 2);
        console.log(newCoordinates);
    });

   
    function placeMarker (position, map) {
      if (marker == null) {
        console.log(marker)
        marker = new google.maps.Marker({
        icon: '../assets/marcador_mapa_redimensionado.png',
        map: map,
        position: position
      });
      } else {
        marker.setPosition(position)
      }
     
    }
    // marker.addListener('click', (mapsMouseEvent) => {
    //   newCoordinates = JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2)
    //   console.log(newCoordinates)
    // })
    
}

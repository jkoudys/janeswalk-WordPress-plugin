/**
 * Methods needed for JW plugin
 */

(function() {
  var forEach = Function.prototype.call.bind(Array.prototype.forEach);

  function loadMap(url, el) {
    var myOptions = {};
    var map = new google.maps.Map(el, myOptions);
    var walkLayer = new google.maps.KmlLayer({
      url: url,
      map: map,
      preserveViewport: false
    });

    // Resize viewport once KML is loaded
    google.maps.event.addListener(walkLayer, 'status_changed', function() {
      // TODO
    });
  }

  // Running function either queues the map load,
  // or loads if we're ready to do so.
  document.addEventListener('DOMContentLoaded', function() {
    // Load maps for any map elements we have
    forEach(document.querySelectorAll('.janeswalk-map'), function(map) {
      loadMap(map.getAttribute('data-url'), map);
    });
  });
})();

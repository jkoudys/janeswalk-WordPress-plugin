/**
 * Methods needed for JW plugin
 */

(function() {
  var forEach = Function.prototype.call.bind(Array.prototype.forEach);

  /**
   * Load a KML map
   * @param string url The URL for the walk page to load
   * @param DOMNode el The target element to render the map into
   */
  function loadMap(url, el) {
    var myOptions = {};
    var map = new google.maps.Map(el, myOptions);
    var walkLayer = new google.maps.KmlLayer({
      url: url,
      map: map,
      preserveViewport: false
    });
  }

  document.addEventListener('DOMContentLoaded', function() {
    // Load maps for any map elements we have
    forEach(document.querySelectorAll('.janeswalk-map'), function(map) {
      loadMap(map.getAttribute('data-url'), map);
    });
  });
})();

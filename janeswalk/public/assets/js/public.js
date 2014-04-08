document.addEventListener('DOMContentLoaded', function(){
  var walkList = document.getElementById("mas-janeswalk-walklist");
  var sortOptions = walkList.querySelectorAll("thead th");
  var walkElements = walkList.querySelectorAll("tbody tr");
  for(var i=0, len = sortOptions.length; i < len; i++) {
    sortOptions[i].index = i;
    sortOptions[i].walkElements = walkElements;
    sortOptions[i].onclick = function(ev) {
      var walkList = document.getElementById("mas-janeswalk-walklist");
      var walkBody = walkList.querySelector("tbody");
      var walkElements = this.walkElements;

      var walks = [];
      for(var i=0, len = walkElements.length; i < len; i++) {
        walks[i] = walkElements[i];
        walks[i].index = this.index;
      }
      walks.sort(function(a,b) {
        var astr = (a.querySelectorAll("td")[a.index]).innerHTML;
        var bstr = (b.querySelectorAll("td")[b.index]).innerHTML;
        return astr.localeCompare(bstr) || parseInt(a.getAttribute("data-janeswalk-sort")) - parseInt(b.getAttribute("data-janeswalk-sort"));
      });
      for(var i = 0, len = walks.length; i < len; i++) {
        walkBody.appendChild(walks[i]);
      }
    }
  }
});

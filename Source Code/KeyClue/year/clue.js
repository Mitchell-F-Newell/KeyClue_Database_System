// Set the date we're counting down to
var tester = [];
var startnow = new Date().getTime();
for (var j = 0; j < document.querySelectorAll('.timeclass').length; j++) {
    tester.push(new Date(document.getElementById("time-" + j).innerHTML).getTime());
    var distance = tester[j] - startnow;

    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    var seconds = Math.floor((distance % (1000 * 60)) / 1000);

    // Display the result in the element with id="demo"
    document.getElementById("time-" + j).innerHTML = days + "d " + hours + "h "
    + minutes + "m " + seconds + "s ";

    // If the count down is finished, write some text
    if (distance < 0) {
      document.getElementById("time-" + j).innerHTML = "EXPIRED";
    }
}

// Update the count down every 1 second
setInterval(function() {

  // Get todays date and time
  var now = new Date().getTime();

  // Find the distance between now an the count down date
  for (var i = 0; i < tester.length; i++) {
      var distance = tester[i] - now;

      // Time calculations for days, hours, minutes and seconds
      var days = Math.floor(distance / (1000 * 60 * 60 * 24));
      var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
      var seconds = Math.floor((distance % (1000 * 60)) / 1000);

      // Display the result in the element with id="demo"
      document.getElementById("time-" + i).innerHTML = days + "d " + hours + "h "
      + minutes + "m " + seconds + "s ";

      // If the count down is finished, write some text
      if (distance < 0) {
        document.getElementById("time-" + i).innerHTML = "EXPIRED";
      }
  }
}, 1000);

function sortTable(n, m) {
  var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
  table = document.getElementById("table" + m);
  switching = true;
  // Set the sorting direction to ascending:
  dir = "asc";
  /* Make a loop that will continue until
  no switching has been done: */
  while (switching) {
    // Start by saying: no switching is done:
    switching = false;
    rows = table.getElementsByTagName("TR");
    /* Loop through all table rows (except the
    first, which contains table headers): */
    for (i = 1; i < (rows.length - 1); i++) {
      // Start by saying there should be no switching:
      shouldSwitch = false;
      /* Get the two elements you want to compare,
      one from current row and one from the next: */
      x = rows[i].getElementsByTagName("TD")[n];
      y = rows[i + 1].getElementsByTagName("TD")[n];
      /* Check if the two rows should switch place,
      based on the direction, asc or desc: */
      if (dir == "asc") {
        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
          // If so, mark as a switch and break the loop:
          shouldSwitch= true;
          break;
        }
      } else if (dir == "desc") {
        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
          // If so, mark as a switch and break the loop:
          shouldSwitch= true;
          break;
        }
      }
    }
    if (shouldSwitch) {
      /* If a switch has been marked, make the switch
      and mark that a switch has been done: */
      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      switching = true;
      // Each time a switch is done, increase this count by 1:
      switchcount ++;
    } else {
      /* If no switching has been done AND the direction is "asc",
      set the direction to "desc" and run the while loop again. */
      if (switchcount == 0 && dir == "asc") {
        dir = "desc";
        switching = true;
      }
    }
  }
  for (i = 0; i < rows[0].getElementsByTagName("TH").length - 1; i++) {
      rows[0].getElementsByTagName("TH")[i].getElementsByTagName("A")[0].getElementsByTagName("IMG")[0].src="../images/updown.png";
  }
  if (dir == "asc") {
      rows[0].getElementsByTagName("TH")[n].getElementsByTagName("A")[0].getElementsByTagName("IMG")[0].src="../images/down.png";
  } else {
      rows[0].getElementsByTagName("TH")[n].getElementsByTagName("A")[0].getElementsByTagName("IMG")[0].src="../images/up.png";
  }
}

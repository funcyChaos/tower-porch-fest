// Set the date we're counting down to
var countDownDate = new Date("April 29, 2023 12:00:00").getTime();

// Update the count down every 1 second
var x = setInterval(function() {

  // Get today's date and time
  var now = new Date().getTime();
    
  // Find the distance between now and the count down date
  var distance = countDownDate - now;
    
  // Time calculations for days, hours, minutes and seconds
  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);
    
  // Output the result in an element with id="counter"
  // document.getElementById("counter").innerHTML = days + " " + hours + " "
  // + minutes + " " + seconds + " ";
  document.getElementById("days").innerHTML = days;
  document.getElementById("hours").innerHTML = hours;
  document.getElementById("minutes").innerHTML = minutes;
  document.getElementById("seconds").innerHTML = seconds;
  document.getElementById("days-desk").innerHTML = days;
  document.getElementById("hours-desk").innerHTML = hours;
  document.getElementById("minutes-desk").innerHTML = minutes;
  document.getElementById("seconds-desk").innerHTML = seconds;
    
  // If the count down is over, write some text 
  if (distance < 0) {
    clearInterval(x);
    document.getElementById("counter").innerHTML = "It was a blast!";
  }
}, 1000);

// Double checks ACF variable to JS variable
jQuery( document ).ready( function( $ ) {

  console.log( countdownData.festivalDate );

});

// Worker to set the last update 
// set the seconds value
var seconds = 0;
var minutes = 0;

// Define the incrementSeconds function
function incrementSeconds() {
    
    if(seconds < 60) {
        seconds = seconds + 1;
        setTimeout("incrementSeconds()",1000);
        postMessage(seconds + "s ago");
    } else {
        minutes = minutes + 1;
        setTimeout("incrementSeconds()", 60000);
        postMessage(minutes + "m ago");
    }

}

// Start the first iteration
incrementSeconds();
var svg = document.getElementById('svg');
var dragging = false;
var startPosition = { x: 0, y: 0 };
var svgTempPosition = { x: 0, y: 0 };
var svgPosition = { x: 0, y: 0 };
var moveSensi = 0.3;
var zoom = 2;
var screenWidth;
var screenHeight;

setScreen();

window.addEventListener('resize', function() {
    setScreen();
});

svg.addEventListener('mousedown', function(e) {
    dragging = true;
    startPosition.x = e.clientX;
    startPosition.y = e.clientY;
    svgTempPosition = svgPosition;
});

svg.addEventListener('mousemove', function(e) {
    if(dragging) {
        var diff = {
            x: e.clientX - startPosition.x,
            y: e.clientY - startPosition.y
        };
        svgTempPosition.x += diff.x * moveSensi;
        svgTempPosition.y += diff.y * moveSensi;
        svg.setAttribute('viewBox', svgTempPosition.x + ' ' + svgTempPosition.y + ' ' + screenWidth + ' ' + screenHeight);
    }
});

svg.addEventListener('mouseup', function(e) {
    dragging = false;
    svgPosition = svgTempPosition;
});

function setScreen() {
    screenWidth = window.innerWidth * zoom;
    screenHeight = window.innerHeight * zoom;
    svg.setAttribute('viewBox', svgPosition.x + ' ' + svgPosition.y + ' ' + screenWidth + ' ' + screenHeight);
}
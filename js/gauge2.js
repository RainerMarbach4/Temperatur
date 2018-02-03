let gauge;

$(document).ready(function () {
    let opts = {
        angle: 0, // The span of the gauge arc
        lineWidth: 0.44, // The line thickness
        radiusScale: 0.99, // Relative radius
        pointer: {
            length: 0.51, // // Relative to gauge radius
            strokeWidth: 0.035, // The thickness
            color: '#000000' // Fill color
        },
        limitMax: false,     // If false, max value increases automatically if value > maxValue
        limitMin: false,     // If true, the min value of the gauge will be fixed
        colorStart: '#6FADCF',   // Colors
        colorStop: '#8FC0DA',    // just experiment with them
        strokeColor: '#E0E0E0',  // to see which ones work best for you
        generateGradient: true,
        highDpiSupport: true,     // High resolution support

        // renderTicks is Optional
        renderTicks: {
            divisions: 5,
            divWidth: 1.1,
            divLength: 0.7,
            divColor: '#333333',
            subDivisions: 3,
            subLength: 0.5,
            subWidth: 0.6,
            subColor: '#666666'
        },

        staticLabels: {
            font: "10px sans-serif",
            labels: [-30, 60],
        },

        staticZones: [
            {strokeStyle: "#6495ed", min: -30, max: 10}, // Blue #6495ed
            {strokeStyle: "#30B32D", min: 10, max: 30}, // Green #30B32D
            {strokeStyle: "#F03E3E", min: 30, max: 60}, // Red #F03E3E
        ],
    };

    let target = document.getElementById('canvas-preview');
    gauge = new Gauge(target).setOptions(opts);
    gauge.maxValue = 60;
    gauge.animationSpeed = 6;
    let textRenderer = new TextRenderer(document.getElementById('preview-textfield'));
    textRenderer.render = function () {
        this.el.innerHTML = parseFloat(Math.round(gauge.displayedValue * 100) / 100).toFixed(2);
    };
    gauge.setTextField(textRenderer);
    gauge.setMinValue(-30);
    gauge.set(2.5);
});

function updateGauge() {
    $.getJSON('google/getData.php', function (json) {
        console.log(json);
    });
}
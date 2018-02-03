String.prototype.replaceAll = function(search, replacement) {
    let target = this;
    return target.replace(new RegExp(search, 'g'), replacement);
};

let gaugeStore = {
    preCfg: {
        angle: 0,
        lineWidth: 0.44,
        radiusScale: 1,
        pointer: {
            length: 0.5,
            strokeWidth: 0.035,
            color: '#000000'
        },
        limitMax: false,
        limitMin: false,
        highDpiSupport: true,

        renderTicks: {
            divisions: 9,
            divWidth: 1.1,
            divLength: 0.7,
            divColor: '#333333',
            subDivisions: 5,
            subLength: 0.5,
            subWidth: 0.6,
            subColor: '#666666'
        },

        staticLabels: {
            font: "10px 'Roboto', sans-serif",
            labels: [-30, 0, 30, 60],
        },

        // percentColors: [[0.0, "#6495ed"], [0.5, "#30B32D"], [1.0, "#F03E3E"]],

        staticZones: [
            {strokeStyle: "#6495ed", min: -30, max: 10}, // Blue #6495ed
            {strokeStyle: "#30B32D", min: 10, max: 30}, // Green #30B32D
            {strokeStyle: "#F03E3E", min: 30, max: 60}, // Red #F03E3E
        ],
    },

    preset: '' +
    '<div id="gauge-{{id}}">\n' +
    '    <div class="sensor-value"></div>\n' +
    '    <canvas width=300 height=150 class="sensor-gauge"></canvas>\n' +
    '    <div class="sensor-name">{{name}}</div>\n' +
    '</div>',

    container: null,

    gauges: [],
};

class OwnGauge {
    constructor(container, name, options=gaugeStore.preCfg, preset=gaugeStore.preset) {
        container = $(container);
        let obj = $(preset.replaceAll("{{id}}", name).replaceAll("{{name}}", name));
        container.append(obj);
        let own = this;
        own.text = obj.find('.sensor-value');
        own.canvas = obj.find('.sensor-gauge');
        own.name = obj.find('.sensor-name');
        own.name.text(name);

        own.gauge = new Gauge(own.canvas[0]).setOptions(options);
        own.gauge.maxValue = 60;
        own.gauge.animationSpeed = 6;
        own.textRenderer = new TextRenderer(own.text[0]);
        own.textRenderer.render = function () {
            this.el.innerHTML = parseFloat((Math.round(own.gauge.displayedValue * 100) / 100) + "").toFixed(2);
        };
        own.gauge.setTextField(own.textRenderer);
        own.gauge.setMinValue(-30);
        own.gauge.set(0);
    }

    set(value) {
        this.gauge.set(value);
    }
}

$(document).ready(function () {
    gaugeStore.container = $('#gauges');

    updateGauges();
    setInterval(updateGauges, 60 * 1000);
});

function updateGauges() {
    $.getJSON('google/getData.php', function (json) {
        $.each(json, function (name, value) {
            if(!(name in gaugeStore.gauges)) {
                gaugeStore.gauges[name] = new OwnGauge(gaugeStore.container, name);
            }
            gaugeStore.gauges[name].set(value);
        });
    });
}
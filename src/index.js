import $ from 'jquery';
import 'chart.js';

$(async function() {
    setNumGeracoes();
    loadLineChart();
});

const setNumGeracoes = () => {
    $.getJSON(window.location.href+"files/melhor-adaptacao.json", (data) => {
        $('#numGeracoes').append(data.length);
    });
};

const loadLineChart = async () => {
    $.getJSON(window.location.href+"files/melhor-adaptacao.json", (data) => {
        let ctx = $('#melhorAdaptacao');
        let chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.map(function(value, index) {
                    return (index + 1);
                }),
                datasets: [{
                    label: "Melhor adaptacao por geração",
                    data: data,
                    borderColor: 'rgba(0, 180, 216, 1)',
                    backgroundColor: 'rgba(72, 202, 228, 0.2)',
                }]
            },
            options: {            
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                        }
                    }]
                }
            }
        });
    });
};
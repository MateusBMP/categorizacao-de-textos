import $ from 'jquery';
import { Line } from './chart';
import { Graph } from './graph';

$(async function() {
    loadMelhorAdaptacao();
    loadUltimaGeracao();
});

const loadMelhorAdaptacao = () => {
    $.getJSON(window.location.href+"files/outputs/melhor-adaptacao.json", async (data) => {
        setNumGeracoes(data);
        setMelhorAdaptacaoPorGeracaoChart(data);
    });
};

const loadUltimaGeracao = () => {
    $.getJSON(window.location.href+"files/outputs/ultima-geracao.json", async (data) => {
        setMaisAdaptadoGraph(data);
    });
}

const setNumGeracoes = (data) => {
    $('#numGeracoes').append(data.length);
}

const setMelhorAdaptacaoPorGeracaoChart = (data) => {
    const ctx = $('#melhorAdaptacao');
    const datasetLabel = "Melhor adaptacao por geração";
    let dataLabels = data.map(function(value, index) {
        return (index + 1);
    });

    let chart = new Line(ctx, datasetLabel, dataLabels, data);
    chart.draw();
}

const setMaisAdaptadoGraph = (data) => {
    const convertGraph = (data) => {
        const createNodes = (n) => {
            let data = [];
    
            for (let i = 1; i <= n; i++) {
                data.push({
                    id: i, 
                    group: 1
                });
            }
    
            return data;
        };
    
        const createLinks = (data) => {
            let links = [];
    
            data.forEach(function(lin, i_lin) {
                lin.forEach(function(col, i_col) {
                    if (data[i_lin][i_col] === 1) {
                        links.push({
                            source: (i_lin + 1),
                            target: (i_col + 1),
                            value: 1
                        });
                    }
                });
            });
    
            return links;
        };

        let formatedData = {
            nodes: createNodes(data.length),
            links: createLinks(data)
        };
    
        return formatedData;
    }

    const formatedData = convertGraph(data[0]);
    const width = $("#maisAdaptado").attr("width");
    const height = $("#maisAdaptado").attr("height");

    let graph = new Graph(formatedData);
    graph.draw("#maisAdaptado", width, height);
}
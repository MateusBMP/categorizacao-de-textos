import { Chart, LinearScale, LineController, CategoryScale, PointElement, LineElement, Filler, Legend, Title, Tooltip } from 'chart.js';

Chart.register(LinearScale, LineController, CategoryScale, PointElement, LineElement, Filler, Legend, Title, Tooltip);

export class Line
{
    /**
     * Desenha gráfico do tipo 'line' via biblioteca Chart.js.
     * 
     * @param   {JQuery} ctx
     * @param   {String} datasetLabel
     * @param   {Array}  dataLabels
     * @param   {Array}  data
     */
    constructor(ctx, datasetLabel, dataLabels, data)
    {
        this.ctx = ctx;
        this.datasetLabel = datasetLabel;
        this.dataLabels = dataLabels;
        this.data = data;
    }

    /**
     * @returns {Chart}
     */
    draw()
    {
        return new Chart(this.ctx, {
            type: 'line',
            data: {
                labels: this.dataLabels,
                datasets: [{
                    label: this.datasetLabel,
                    borderColor:     'rgba(0, 180, 216, 1)',
                    backgroundColor: 'rgba(72, 202, 228, 0.2)',
                    data: this.data
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
    }
}
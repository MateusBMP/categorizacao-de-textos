import * as d3 from 'd3';
import 'd3-force';

export class Graph
{
    constructor(data)
    {
        console.info(data);
        this.data = data;
    }

    /**
     * Gere todas as operações de movimento e interação do cliente.
     * 
     * @param {Simulation} simulation
     * @returns {DragBehavior}
     */
    drag(simulation)
    {
        function dragstarted(event) {
            if (!event.active) simulation.alphaTarget(0.3).restart();
            event.subject.fx = event.subject.x;
            event.subject.fy = event.subject.y;
        }
        
        function dragged(event) {
            event.subject.fx = event.x;
            event.subject.fy = event.y;
        }
        
        function dragended(event) {
            if (!event.active) simulation.alphaTarget(0);
            event.subject.fx = null;
            event.subject.fy = null;
        }
        
        return d3.drag()
            .on("start", dragstarted)
            .on("drag", dragged)
            .on("end", dragended);
    }

    /**
     * Seleciona as cores que serão usadas no grafo
     * 
     * @returns {String}
     */
    color()
    {
        const scale = d3.scaleOrdinal(d3.schemeCategory10);
        return d => scale(d.group);
    };

    /**
     * Desenha o grafo. Deve receber o seletor de onde será desenhado o grafo, a largura e o
     * comprimeiro do grafo.
     * 
     * @param  {String}  selector
     * @param  {Number}  width
     * @param  {Number}  height
     * @return {d3.BaseType}
     */
    draw(selector, width, height)
    {
        const links = this.data.links.map(d => Object.create(d));
        const nodes = this.data.nodes.map(d => Object.create(d));
      
        const simulation = d3.forceSimulation(nodes)
            .force("link", d3.forceLink(links).id(d => d.id))
            .force("charge", d3.forceManyBody())
            .force("center", d3.forceCenter(width / 2, height / 2));
      
        const svg = d3.select(selector);
      
        const link = svg.append("g")
            .attr("stroke", "#999")
            .attr("stroke-opacity", 0.6)
            .selectAll("line")
            .data(links)
            .join("line")
            .attr("stroke-width", d => Math.sqrt(d.value));
      
        const node = svg.append("g")
            .attr("stroke", "#fff")
            .attr("stroke-width", 1.5)
            .selectAll("circle")
            .data(nodes)
            .join("circle")
            .attr("r", 5)
            .attr("fill", this.color())
            .call(this.drag(simulation));
      
        node.append("title")
            .text(d => d.id);
      
        simulation.on("tick", () => {
          link
              .attr("x1", d => d.source.x)
              .attr("y1", d => d.source.y)
              .attr("x2", d => d.target.x)
              .attr("y2", d => d.target.y);
      
          node
              .attr("cx", d => d.x)
              .attr("cy", d => d.y);
        });
      
        // invalidation.then(() => simulation.stop());
      
        return svg.node();
    }
}
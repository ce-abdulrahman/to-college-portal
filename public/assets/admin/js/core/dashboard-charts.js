// Needs Chart.js loaded in that page
window.DashboardCharts = (function(){
  function initSalesChart(canvasSelector = '#salesChart'){
    const el = document.querySelector(canvasSelector);
    if (!el) return null;

    const mk = (n=7) => ({
      labels: Array.from({length:n}, (_,i)=> `+${n-i}d`),
      a: Array.from({length:n}, ()=> Math.floor(2000+Math.random()*5000)),
      b: Array.from({length:n}, ()=> Math.floor(1200+Math.random()*3000)),
    });

    let r = 7;
    let d = mk(r);
    const chart = new Chart(el, {
      type: 'line',
      data: { labels: d.labels,
        datasets: [
          { label: 'Revenue', data: d.a, tension:.35, fill:true, borderWidth:2, pointRadius:0, backgroundColor:'rgba(13,110,253,.15)', borderColor:'rgba(13,110,253,1)' },
          { label: 'Orders',  data: d.b, tension:.35, fill:false,borderWidth:2, pointRadius:0, borderColor:'rgba(25,135,84,1)' }
        ]
      },
      options: { responsive:true, maintainAspectRatio:false, plugins:{ legend:{ rtl:true }, tooltip:{ rtl:true } } }
    });

    document.querySelectorAll('[data-range]').forEach(btn=>{
      btn.addEventListener('click', ()=>{
        document.querySelectorAll('[data-range]').forEach(b=>b.classList.remove('active'));
        btn.classList.add('active');
        r = Number(btn.dataset.range) || 7;
        d = mk(r);
        chart.data.labels = d.labels;
        chart.data.datasets[0].data = d.a;
        chart.data.datasets[1].data = d.b;
        chart.update();
      });
    });

    return chart;
  }
  return { initSalesChart };
})();

@extends('adminlte::page')

@section('title','Dashboard IoT')

@section('content_header') 
<h1>Dashboard IoT</h1> 
@stop


@section('css')
<style>

body {
    overflow-x: hidden;
}

.small-box {
    margin-bottom: 15px;
}

.card canvas {
    width: 100% !important;
}

/* Tinggi grafik lebih padat */
#sensorChart {
    height: 280px !important;
}

@media (max-width: 767px) {

    h1 {
        font-size: 20px;
    }

    .small-box h3 {
        font-size: 18px;
    }

    .small-box p {
        font-size: 13px;
    }

    .card-header {
        font-size: 14px;
    }

    .card-body p {
        font-size: 14px;
    }

    #sensorChart {
        height: 240px !important;
    }
}

</style>
@stop



@section('content')

<div class="row">
  <div class="col-lg-3 col-6">
    <div class="small-box bg-info">
      <div class="inner">
        <h3 id="voltage">- V</h3>
        <p>Tegangan</p>
      </div>
      <div class="icon"><i class="fas fa-bolt"></i></div>
      <span class="small-box-footer">Detail</span>
    </div>
  </div>

  <div class="col-lg-3 col-6">
    <div class="small-box bg-danger">
      <div class="inner">
        <h3 id="temperature">- °C</h3>
        <p>Suhu</p>
      </div>
      <div class="icon"><i class="fas fa-thermometer-half"></i></div>
      <span class="small-box-footer">Detail</span>
    </div>
  </div>

  <div class="col-lg-3 col-6">
    <div class="small-box bg-primary">
      <div class="inner">
        <h3 id="humidity">- %</h3>
        <p>Kelembapan</p>
      </div>
      <div class="icon"><i class="fas fa-tint"></i></div>
      <span class="small-box-footer">Detail</span>
    </div>
  </div>

  <div class="col-lg-3 col-6">
    <div class="small-box bg-warning">
      <div class="inner">
        <h3 id="relay_status">-</h3>
        <p>Status Relay</p>
      </div>
      <div class="icon"><i class="fas fa-toggle-on"></i></div>
      <a href="{{ url('/relay') }}" class="small-box-footer">Kontrol</a>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-8 col-12">
    <div class="card">
      <div class="card-header">Grafik Sensor</div>
      <div class="card-body">
        <canvas id="sensorChart"></canvas>
      </div>
    </div>
  </div>
  <div class="col-md-4 col-12">
    <div class="card">
      <div class="card-header">Ringkasan Cepat</div>
      <div class="card-body">
        <p><strong>Tegangan:</strong> <span id="v_small">-</span> V</p>
        <p><strong>Suhu:</strong> <span id="t_small">-</span> °C</p>
        <p><strong>Kelembapan:</strong> <span id="h_small">-</span> %</p>
      </div>
    </div>
  </div>
</div>

@stop



@section('js')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

if (window.innerWidth < 768) {
    document.body.classList.add('sidebar-collapse');
}

const API_KEY = "{{ env('API_KEY_SECRET') }}";

const ctx = document.getElementById('sensorChart').getContext('2d');

const chart = new Chart(ctx, {
  type:'line',
  data:{
    labels:[], 
    datasets:[
      {
        label:'Suhu (°C)',
        data:[],
        borderColor:'red',
        tension:0.3,
        pointRadius:2
      },
      {
        label:'Kelembapan (%)',
        data:[],
        borderColor:'blue',
        tension:0.3,
        pointRadius:2
      },
      {
        label:'Tegangan (V)',
        data:[],
        borderColor:'green',
        tension:0.3,
        pointRadius:2
      }
    ]
  },
  options:{
    responsive:true,
    maintainAspectRatio:false,
    layout:{
      padding:0
    },
    plugins:{
      legend:{
        labels:{
          boxWidth:20
        }
      }
    },
    scales:{
      y:{
        beginAtZero:true
      },
      x:{
        ticks:{
          autoSkip:true,
          maxTicksLimit:6,
          maxRotation:0
        }
      }
    }
  }
});

async function refreshAll(){
  try{
    const sres = await fetch('/api/sensor/latest',{headers:{'X-API-KEY':API_KEY}});
    if(sres.ok){
      const d = await sres.json();

      document.getElementById('voltage').innerText = (d.voltage ?? '-') + ' V';
      document.getElementById('temperature').innerText = (d.temperature ?? '-') + ' °C';
      document.getElementById('humidity').innerText = (d.humidity ?? '-') + ' %';

      document.getElementById('v_small').innerText = d.voltage ?? '-';
      document.getElementById('t_small').innerText = d.temperature ?? '-';
      document.getElementById('h_small').innerText = d.humidity ?? '-';

      const time = new Date().toLocaleTimeString();

      chart.data.labels.push(time);
      chart.data.datasets[0].data.push(d.temperature ?? 0);
      chart.data.datasets[1].data.push(d.humidity ?? 0);
      chart.data.datasets[2].data.push(d.voltage ?? 0);

      if(chart.data.labels.length>30){
        chart.data.labels.shift();
        chart.data.datasets.forEach(ds=>ds.data.shift());
      }

      chart.update();
    }

    const rres = await fetch('/api/relay/status',{headers:{'X-API-KEY':API_KEY}}); 
    if(rres.ok){ 
      const rd = await rres.json(); 
      document.getElementById('relay_status').innerText = rd.status==1?'ON':'OFF'; 
    }

  }catch(e){ console.error(e); }
}

refreshAll();
setInterval(refreshAll,2000);

</script>

@stop

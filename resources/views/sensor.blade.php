@extends('adminlte::page')

@section('title', 'Data Sensor')

@section('content_header')
<h1>Sensor DHT & PZEM</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">DHT11</div>
            <div class="card-body">
                <p>Suhu: <span id="tempVal">0</span> °C</p>
                <p>Kelembapan: <span id="humiVal">0</span> %</p>
                <canvas id="dhtChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">PZEM (Listrik)</div>
            <div class="card-body">
                <p>Tegangan: <span id="voltVal">0</span> V</p>
                <canvas id="pzemChart"></canvas>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const API_KEY = "{{ env('API_KEY_SECRET') }}";
const BASE_URL = "{{ url('/api') }}";

// ===== DHT Chart =====
const dhtChart = new Chart(
    document.getElementById('dhtChart').getContext('2d'),
    {
        type:'line',
        data:{
            labels:[],
            datasets:[
                {label:'Suhu °C', data:[], borderColor:'red', fill:false},
                {label:'Kelembapan %', data:[], borderColor:'blue', fill:false},
            ]
        },
        options:{responsive:true}
    }
);

// ===== PZEM Chart =====
const pzemChart = new Chart(
    document.getElementById('pzemChart').getContext('2d'),
    {
        type:'line',
        data:{
            labels:[],
            datasets:[
                {label:'Tegangan V', data:[], borderColor:'green', fill:false},
            ]
        },
        options:{responsive:true}
    }
);

// ===== UPDATE SENSOR =====
async function updateSensor(){
    try{
        const res = await fetch(`${BASE_URL}/sensor/latest`, {
            headers:{ 'X-API-KEY': API_KEY }
        });

        const s = await res.json();
        const now = new Date().toLocaleTimeString();

        // ===== CHART LABEL =====
        dhtChart.data.labels.push(now);
        pzemChart.data.labels.push(now);

        if (dhtChart.data.labels.length > 20) {
            dhtChart.data.labels.shift();
            pzemChart.data.labels.shift();
        }

        // ===== DHT =====
        dhtChart.data.datasets[0].data.push(s.temperature);
        dhtChart.data.datasets[1].data.push(s.humidity);
        if (dhtChart.data.datasets[0].data.length > 20) {
            dhtChart.data.datasets[0].data.shift();
            dhtChart.data.datasets[1].data.shift();
        }

        // ===== PZEM =====
        pzemChart.data.datasets[0].data.push(s.voltage);
        if (pzemChart.data.datasets[0].data.length > 20) {
            pzemChart.data.datasets[0].data.shift();
        }

        // ===== TEXT =====
        document.getElementById('tempVal').innerText = s.temperature;
        document.getElementById('humiVal').innerText = s.humidity;
        document.getElementById('voltVal').innerText = s.voltage;

        dhtChart.update();
        pzemChart.update();

    }catch(err){
        console.error(err);
    }
}

updateSensor();
setInterval(updateSensor, 2000);
</script>
@stop

@extends('adminlte::page')

@section('title', 'Histori Sensor')

@section('content_header')
    <h1><i class="fas fa-history"></i> Histori Sensor</h1>
@stop

@section('content')

{{-- =========================
     GRAFIK REALTIME
========================= --}}
<div class="card mb-3">
    <div class="card-body">
        <h5 class="text-center mb-3">Grafik Sensor Real-Time</h5>
        <div style="position:relative;height:350px;">
            <canvas id="realtimeChart"></canvas>
        </div>
    </div>
</div>

{{-- =========================
     TABEL HISTORI
========================= --}}
<div class="card">
    <div class="card-body table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="text-center">
                <tr>
                    <th>No</th>
                    <th>Tegangan (V)</th>
                    <th>Suhu (°C)</th>
                    <th>Kelembapan (%)</th>
                    <th>Waktu</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data as $i => $row)
                <tr class="text-center">
                    <td>{{ $i + 1 }}</td>
                    <td>{{ number_format($row->tegangan, 2) }}</td>
                    <td>{{ number_format($row->temperature, 2) }}</td>
                    <td>{{ number_format($row->humidity, 2) }}</td>
                    <td>{{ $row->created_at->format('d-m-Y H:i:s') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted">
                        Data belum tersedia
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@stop


@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const ctx = document.getElementById('realtimeChart').getContext('2d');

const chartData = {
    labels: [],
    datasets: [
        {
            label: 'Suhu (°C)',
            data: [],
            borderColor: 'red',
            borderWidth: 2,
            tension: 0.3
        },
        {
            label: 'Kelembapan (%)',
            data: [],
            borderColor: 'blue',
            borderWidth: 2,
            tension: 0.3
        },
        {
            label: 'Tegangan (V)',
            data: [],
            borderColor: 'green',
            borderWidth: 2,
            tension: 0.3
        }
    ]
};

const realtimeChart = new Chart(ctx, {
    type: 'line',
    data: chartData,
    options: {
        responsive: true,
        maintainAspectRatio: false,
        animation: false,
        interaction: {
            mode: 'index',
            intersect: false
        },
        scales: {
            y: {
                type: 'linear',
                beginAtZero: false,
                ticks: {
                    precision: 1
                }
            }
        }
    }
});

function updateChart() {
    fetch("{{ url('/api/sensor/latest') }}", {
        headers: {
            'X-API-KEY': "{{ env('API_KEY_SECRET') }}"
        }
    })
    .then(res => res.json())
    .then(data => {
        const time = new Date().toLocaleTimeString();

        chartData.labels.push(time);

        chartData.datasets[0].data.push(parseFloat(data.temperature));
        chartData.datasets[1].data.push(parseFloat(data.humidity));
        chartData.datasets[2].data.push(parseFloat(data.voltage));

        // Batasi max 20 data agar tidak berat
        if (chartData.labels.length > 20) {
            chartData.labels.shift();
            chartData.datasets.forEach(ds => ds.data.shift());
        }

        realtimeChart.update();
    })
    .catch(err => console.log("Gagal ambil data:", err));
}

// Update tiap 5 detik
setInterval(updateChart, 5000);

// Load pertama kali
updateChart();
</script>
@endpush

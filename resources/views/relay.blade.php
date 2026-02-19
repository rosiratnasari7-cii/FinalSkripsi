@extends('adminlte::page')

@section('title', 'Kontrol Relay')

@section('content_header')
<h1 class="mb-3">Kontrol Relay</h1>
@stop


@section('content')

<div class="container-fluid">

  <div class="card shadow-sm">
    <div class="card-body text-center">

      <h5 class="mb-3">
        Status Relay:
        <strong id="relayStatus" class="text-primary">-</strong>
      </h5>

      <div class="d-flex flex-column flex-md-row justify-content-center gap-2">

        <button type="button"
                id="btnOn"
                class="btn btn-success btn-lg w-100 w-md-auto">
          ON
        </button>

        <button type="button"
                id="btnOff"
                class="btn btn-danger btn-lg w-100 w-md-auto">
          OFF
        </button>

      </div>

    </div>
  </div>

</div>

@stop


@section('js')
<script>

// ===== AUTO COLLAPSE SIDEBAR DI HP =====
if (window.innerWidth < 768) {
    document.body.classList.add('sidebar-collapse');
}

document.addEventListener('DOMContentLoaded', () => {

  const API_KEY = "{{ env('API_KEY_SECRET') }}";

  async function loadStatus(){
    try {
      const res = await fetch('/api/relay/status', {
        headers: {'X-API-KEY': API_KEY}
      });

      if(!res.ok) return;

      const j = await res.json();

      const statusText = j.status == 1 ? 'ON' : 'OFF';
      document.getElementById('relayStatus').innerText = statusText;

      // Warna status dinamis
      document.getElementById('relayStatus').className =
          j.status == 1 ? 'text-success' : 'text-danger';

    } catch(e){
      console.log("Gagal load status");
    }
  }

  document.getElementById('btnOn').onclick = async () => {
    await fetch('/api/relay/update', {
      method:'POST',
      headers:{
        'Content-Type':'application/json',
        'X-API-KEY':API_KEY
      },
      body: JSON.stringify({status:1})
    });
    loadStatus();
  };

  document.getElementById('btnOff').onclick = async () => {
    await fetch('/api/relay/update', {
      method:'POST',
      headers:{
        'Content-Type':'application/json',
        'X-API-KEY':API_KEY
      },
      body: JSON.stringify({status:0})
    });
    loadStatus();
  };

  loadStatus();
  setInterval(loadStatus,2000);

});
</script>
@stop

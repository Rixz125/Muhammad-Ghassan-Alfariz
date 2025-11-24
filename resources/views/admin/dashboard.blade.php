@extends('templates.app')

@section('content')
<div class="container mt-5">
    <h5>Grafik Pembelian Tiket</h5>

    @if (Session::get('success'))
        <div class="alert alert-success">
            {{ Session::get('success') }}
            <b>Selamat Datang, {{ Auth::user()->name }}</b>
        </div>
    @endif

    <div class="row">
        <div class="col-6">
            <h5>Data Pembelian Tiket bulan {{ now()->format('F') }}</h5>
            <canvas id="chartBar"></canvas>
        </div>
        <div class="col-6">
            <h5>Data Pembelian Tiket bulan {{ now()->format('F') }}</h5>
            <canvas id="chartPie"></canvas>
        </div>
    </div>
</div>
@endsection

@push('script')
    <script>
        // ajax dipanggil ketika halaman baru selesai di refresh
        $(function () {
            let labelsBar = [];
            let dataBar = [];
            $.ajax({
                url: "{{ route('admin.tickets.chart') }}",
                method: "GET",
                success: function (response) {
                    labelsBar = response.labels; // var labelsBar dari controller json bagian labels
                    dataBar = response.data;
                    // function konfigurasi chart
                    showChartBar();
                },
                error: function (err) {
                    alert('Gagal mengambil data chart tiket!')
                }
            });

            let dataPie = [];
            $.ajax({
                url: "{{ route('admin.movies.chart') }}",
                method: "GET",
                success: function (response) {
                    dataPie = response.data;
                    showChartPie();
                },
                error: function (err) {
                    alert('Gagal mengambil data chart film!')
                }
            })

            function showChartBar() {
                const ctx = document.getElementById('chartBar');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labelsBar,
                        datasets: [{
                            label: '# of Votes',
                            data: dataBar,
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }

            function showChartPie() {
                const ctx = document.getElementById('chartPie');
                new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: [
                            'Film Aktif',
                            'FIlm Non-Aktif',
                        ],
                        datasets: [{
                            label: 'Perbandingan Data Film Aktif & Non-Aktif',
                            data: dataPie,
                            backgroundColor: [
                                'rgb(255, 99, 132)',
                                'rgb(54, 162, 235)',
                            ],
                        }]
                    }
                });
            }
        })
    </script>
@endpush

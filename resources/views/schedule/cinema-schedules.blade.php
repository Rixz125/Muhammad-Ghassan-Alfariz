@extends('templates.app')

@section('content')
    <div class="container my-5 card">
        <div class="card-body">
            <i class="fa-solid fa-location-dot me-3"></i>{{ $schedules[0]['cinema']['location'] }}
            <hr>
            @foreach ($schedules as $schedule)
                <div class="my-2">
                    <div class="d-flex">
                        <div style="width: 150px; height: 200px;">
                            <img src="{{ asset('storage/' . $schedule['movie']['poster']) }}" alt="Poster Superman"
                                class="w-100 rounded">
                        </div>
                        <div class="ms-5 mt-4">
                            <h5 class="fw-bold">{{ $schedule['movie']['title'] }}</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><b class="text-secondary">Genre</b></td>
                                    <td class="px-3">:</td>
                                    <td>{{ $schedule['movie']['genre'] }}</td>
                                </tr>
                                <tr>
                                    <td><b class="text-secondary">Durasi</b></td>
                                    <td class="px-3">:</td>
                                    <td>{{ $schedule['movie']['duration'] }}</td>
                                </tr>
                                <tr>
                                    <td><b class="text-secondary">Sutradara</b></td>
                                    <td class="px-3">:</td>
                                    <td>{{ $schedule['movie']['director'] }}</td>
                                </tr>
                                <tr>
                                    <td><b class="text-secondary">Rating usia</b></td>
                                    <td class="px-3">:</td>
                                    <td><span class="badge bg-danger">{{ $schedule['movie']['age_rating'] }}</span></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="w-100 my-3">
                        <div class="d-flex justify-content-end">
                            <div class="">
                                <b>Rp. {{ number_format($schedule['price'], 0, ',', '.') }}</b>
                            </div>
                        </div>
                          <div class="d-flex gap-3 ps-3 my-3">
                            {{--  --}}
                            @foreach ($schedule['hours'] as $index => $hours)
                                {{-- argumen pada fungsi selected hours --}}
                                {{-- 1. schedule->id mengambil detail schdule yg akan di beli --}}
                                {{-- 2. index: mengambil index dari array hours --}}
                                <div class="btn btn-outline-secondary" style="cursor: pointer"
                                    onclick="selectedHours('{{ $schedule->id }}','{{ $index }}', this)">
                                    {{ $hours }}</div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <hr>
            @endforeach
        </div>
    </div>
      <div class="w-100 p-2 bg-light text-center fixed-bottom" id="wrapBtn">
        {{-- javascript untk non aktifin href --}}
                    <a href="javascript:void(0)" id="btnOrder"><i class="fa solid fa-ticket"></i> BELI TICKET</a>
                </div>
 @endsection
    @push('script')
        <script>
            let selectedScheduleId = null;
            let selectedHourIndex = null;
            let lastClicked = null;

            function selectedHours(scheduleId, hourIndex, el) {
                selectedScheduleId = scheduleId;
                selectedHourIndex = hourIndex;

                if (lastClicked) {
                    lastClicked.style.backgroundColor = "";
                    lastClicked.style.color = "";
                    lastClicked.style.borderColor="";
                }

                // ubah waarna kotak jadi yang di klik
                // el diambil dari para meter fungsi dengan nilai argumen this di html nya

                el.style.backgroundColor = "#112646";
                el.style.color = "white";
                el.style.borderColor = "#112646";

                lastClicked = el;

                let wrapBtn = document.querySelector("#wrapBtn")
                // HAPUS CLASS CLASSLIST REMOVE
                wrapBtn.classList.remove("bg-light");
                wrapBtn.style.backgroundColor = '#112646';
                // memanggil route web,php di js
                // replace mengganti/mengisi path di namis schedules di web php
                let url ="{{ route('schedules.show_seats', ['scheduleId' => ':scheduleId', 'hourId' => ':hourId']) }}" .replace(':scheduleId',
                    scheduleId).replace(':hourId', hourIndex);
                    let btnOrder= document.querySelector('#btnOrder');
                    btnOrder.href = url;
                    btnOrder.style.color = 'white'
            } 
            </script>
            @endpush
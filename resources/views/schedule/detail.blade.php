@extends('templates.app')

@section('content')
    <div class="container pt-5">
        <div class="w-80 m-auto">
            <div class="d-flex">
                <div style="width: 200px; height: 200px;">
                    <img src="{{ asset('storage/' . $movie['poster']) }}" alt="" class="w-100">
                </div>

                <div class="ms-3 mt-0">
                    <h5>{{ $movie['title     222'] }}</h5>
                    <table>
                        <tr>
                            <td><i class="text-secondary fa-solid fa-film me-2"></i><b>Genre</b></td>
                            <td class="px-3">:</td>
                            <td>{{ $movie['genre'] }}</td>
                        </tr>
                        <tr>
                            <td><i class="text-secondary fa-solid fa-clock me-2"></i><b>Durasi</b></td>
                            <td class="px-3">:</td>
                            <td>{{ $movie['duration'] }}</td>
                        </tr>
                        <tr>
                            <td><i class="text-secondary fa-solid fa-video me-2"></i><b>Sutradara</b></td>
                            <td class="px-3">:</td>
                            <td>{{ $movie['director'] }}</td>
                        </tr>
                        <tr>
                            <td><i class="text-secondary fa-solid fa-user-shield me-2"></i><b>Rating Usia</b></td>
                            <td class="px-3">:</td>
                            <td><span class="badge bg-danger">{{ 'age_rating' }}+</span></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="w-100 row mt-5">
                <div class="col-6 pe-5">
                    <div class="d-flex flex-column justify-content-end align-items-end">
                        <div class="d-flex align-items-center">
                            <h3 class="text-warning me-2">9.5</h3>
                            <i class="fa-solid fa-star text-warning"></i>
                            <i class="fa-solid fa-star text-warning"></i>
                            <i class="fa-solid fa-star text-warning"></i>
                        </div>
                        <small>5.515 Vote</small>
                    </div>
                </div>
                <div class="col-6 ps-5" style="border-left: 2px solid #c7c7c7;">
                    <div class="d-flex align-items-center">
                        <div class="fas fa-heart text-danger me-2"></div>
                        <b>Masukkan Watchlist</b><br>
                    </div>
                    <small>10.000 orang</small>
                </div>
            </div>
            <div class="d-flex w-100 bg-light mt-3">
                <div class="dropdown">
                    <button class="btn btn-light w-100 text-start dropdown-toggle" type="button" id="dropdownMenuButton"
                        data-mdb-dropdown-init data-mdb-ripple-init aria-expanded="false">
                        Bioskop
                    </button>
                    <ul class="dropdown-menu w-100" aria-labelledby="dropdownMenuButton">
                        @foreach ($movie['schedules'] as $schedule)
                            <li><a class="dropdown-item" href="#">{{ $schedule['cinema']['name'] }}</a></li>
                        @endforeach
                    </ul>
                </div>
                <div class="dropdown">
                    <button class="btn btn-light w-100 text-start dropdown-toggle" type="button" id="dropdownMenuButton"
                        data-mdb-dropdown-init data-mdb-ripple-init aria-expanded="false">
                        SORTIR
                    </button>
                    <ul class="dropdown-menu w-100" aria-labelledby="dropdownMenuButton">
                        <li><a class="dropdown-item" href="#">Alphabet</a></li>
                        <li><a class="dropdown-item" href="#">Harga</a></li>
                    </ul>
                </div>
            </div>
            <br>
            <div class="mb-5">
                @foreach ($movie['schedules'] as $schedule)
                    <div class="w-100 my-3>
                <i class="fa solid fa-building"></i>
                        <b class="ms-2">{{ $schedule['cinema']['name'] }}</b>
                        <br>
                        <small class="ms-3">{{ $schedule['cinema']['location'] }}</small>

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
                @endforeach
                <div class="w-100 p-2 bg-light text-center fixed-bottom" id="wrapBtn">
                    {{-- javascript untk non aktifin href --}}
                    <a href="javascript:void(0)" id="btnOrder"><i class="fa solid fa-ticket"></i> BELI TICKET</a>
                </div>
            </div>
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
                    lastClicked.style.borderColor = "";
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
                let url = "{{ route('schedules.show_seats', ['scheduleId' => ':scheduleId', 'hourId' => ':hourId']) }}"
                    .replace(':scheduleId',
                        scheduleId).replace(':hourId', hourIndex);
                let btnOrder = document.querySelector('#btnOrder');
                btnOrder.href = url;
                btnOrder.style.color = 'white'
            }
        </script>
    @endpush

@extends('templates.app')

@section('content')
    <div class="container card my-5 p-4" style="margin-bottom: 10% !important">
        <div class="card-body">
            <b>{{ $schedule['cinema']['name'] }}</b>
            <br>
            <b>{{ now()->format('d F, Y') }} - {{ $hour }}</b>
            <br>
            <div class="alert alert-secondary">
                <i class="fa-solid fa-info text-danger me-3"></i> Anak usia 2 tahun ke atas wajib membeli tiket
            </div>

            {{-- Keterangan warna kursi --}}
            <div class="w-50 d-block mx-auto my-3">
                <div class="row">
                    <div class="col-4 d-flex">
                        <div style="background: #112646; width: 20px; height: 20px"></div>
                        <span class="ms-2">Kursi Tersedia</span>
                    </div>
                    <div class="col-4 d-flex">
                        <div style="background: blue; width: 20px; height: 20px"></div>
                        <span class="ms-2">Kursi Dipilih</span>
                    </div>
                    <div class="col-4 d-flex">
                        <div style="background: #eaeaea; width: 20px; height: 20px"></div>
                        <span class="ms-2">Kursi Terjual</span>
                    </div>
                </div>
            </div>

            @php
                $rows = range('A', 'H');
                $cols = range(1, 18);
            @endphp

            {{-- Tampilan kursi --}}
            @foreach ($rows as $row)
                <div class="d-flex justify-content-center">
                    @foreach ($cols as $col)
                        @if ($col == 7)
                            <div style="width: 50px"></div>
                        @endif
                        @php
                            $seat = $row . '-' . $col;
                        @endphp
                        {{-- cek aapakah aray Seatsformat adat data kursi ini  --}}
                        @if (in_array($seat, $seatsFormat))
                            <div style="background: #eaeaea; color:black; width: 48px; height: 35px; margin: 5px;
                            border-radius: 5px; text-align:center; padding-top: 3px;"
                                <small><b>{{ $row }}-{{ $col }}</b></small>
                            </div>
                        @else
                            <div style="background: #112646; color:white; width: 48px; height: 35px; margin: 5px;
                            border-radius: 5px; text-align:center; padding-top: 3px; cursor: pointer"
                                onclick="selectSeat('{{ $schedule->price }}', '{{ $row }}', '{{ $col }}', this)">
                                <small><b>{{ $row }}-{{ $col }}</b></small>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>

    <div class="fixed-bottom">
        <div class="w-100 bg-light text-center px-3" style="border: 1px solid black">
            <b>LAYAR BIOSKOP</b>
        </div>
        <div class="row bg-light">
            <div class="col-6 text-center p-3" style="border: 1px solid black">
                <b>Total Harga</b>
                <br><b id="totalPrice">Rp. -</b>
            </div>
            <div class="col-6 text-center p-3" style="border: 1px solid black">
                <b>Kursi Dipilih</b>
                <br><b id="selectedSeat">-</b>
            </div>
        </div>

        {{-- Hidden input --}}
        <input type="hidden" id="user_id" value="{{ Auth::user()->id }}">
        <input type="hidden" id="schedule_id" value="{{ $schedule->id }}">
        <input type="hidden" id="hour" value="{{ $hour }}">

        <div class="w-100 bg-light text-center py-3" style="font-weight: bold; cursor:pointer" id="btnCreateOrder">
            RINGKASAN ORDER
        </div>
    </div>
@endsection

@push('script')
    <script>
        let seats = [];
        let totalPrice = 0;

        function selectSeat(price, row, col, element) {
            let seat = row + "-" + col;
            let indexSeat = seats.indexOf(seat);

            if (indexSeat == -1) {
                seats.push(seat);
                element.style.background = 'blue';
            } else {
                seats.splice(indexSeat, 1);
                element.style.background = '#112646';
            }

            totalPrice = price * seats.length;
            document.querySelector("#totalPrice").innerText = "Rp. " + totalPrice;
            document.querySelector("#selectedSeat").innerText = seats.join(',');

            let btnCreateOrder = document.querySelector('#btnCreateOrder');
            if (seats.length > 0) {
                btnCreateOrder.style.background = '#112646';
                btnCreateOrder.style.color = 'white';
                btnCreateOrder.classList.remove('bg-light');
                btnCreateOrder.onclick = createOrder;
            } else {
                btnCreateOrder.style.background = '';
                btnCreateOrder.style.color = '';
                btnCreateOrder.onclick = null;
            }
        }

        function createOrder() {
            let data = {
                user_id: $("#user_id").val(),
                schedule_id: $("#schedule_id").val(),
                rows_of_seats: seats,
                quantity: seats.length,
                total_price: totalPrice,
                tax: 4000 * seats.length,
                hour: $("#hour").val(),
                _token: "{{ csrf_token() }}"
            };

            $.ajax({
                url: "{{ route('tickets.store') }}",
                method: "POST",
                data: data,
                success: function(response) {
                    let ticketId = response.data.id;
                    // pindah halaan window location
                    window.location.href = `/tickets/${ticketId}/order`;
                },
                error: function(message) {
                    alert('Gagal membuat data ticket')
                }
            });
        }
    </script>
@endpush

<div class="container mt-4">
    <h5>Data Tiket Terlewatkan</h5>

    <div class="row g-3 mt-2">
        @foreach ($ticketNonActive as $nonActive)
            <div class="col-md-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body">

                        <h6 class="fw-bold mb-1">
                            {{ $nonActive['schedule']['cinema']['name'] }}
                        </h6>

                        <p class="fw-semibold mb-2">
                            {{ $nonActive['schedule']['movie']['title'] }}
                        </p>

                        <p class="mb-1">
                            <strong>Tanggal :</strong>
                            {{ \Carbon\Carbon::parse($nonActive['ticketPayment']['booked_date'])->format('d, F Y') }}
                        </p>

                        <p class="mb-1">
                            <strong>Waktu :</strong>
                            {{ \Carbon\Carbon::parse($nonActive['hour'])->format('H:i') }} WIB
                        </p>

                        <p class="mb-1">
                            <strong>Kursi :</strong> {{ implode(',', $nonActive['rows_of_seats']) }}
                        </p>

                        @php
                            $price = $nonActive['total_price'] + $nonActive['tax'];
                        @endphp

                        <p class="mb-0">
                            <strong>Total Pembayaran :</strong>
                            {{ number_format($price, 0, ',', '.') }}
                        </p>

                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

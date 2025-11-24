{{-- import templates --}}
@extends('templates.app')

{{-- mengisi yield --}}
@section('content')
 @if (Session::get('success'))
     <div class="alert alert-success">{{ Session::get('success') }} <b>Selamat Datang, {{Auth::user()->name}}</b></div>
 @endif
  @if (Session::get('logout'))
     <div class="alert alert-warning">{{ Session::get('logout') }}</div>
 @endif
    <div class="dropdown">
        <button class="btn btn-light w-100 text-start dropdown-toggle" type="button" id="dropdownMenuButton"
            data-mdb-dropdown-init data-mdb-ripple-init aria-expanded="false">
            <i class="fa-solid fa-location-dot"></i> Bogor
        </button>
        <ul class="dropdown-menu w-100" aria-labelledby="dropdownMenuButton">
            <li><a class="dropdown-item" href="#">Kalimantan</a></li>
            <li><a class="dropdown-item" href="#">Bandung</a></li>
            <li><a class="dropdown-item" href="#">Jakarta</a></li>
        </ul>
    </div>

    {{-- CARD --}}
    <!-- Carousel wrapper -->
    <div id="carouselBasicExample" class="carousel slide carousel-fade" data-mdb-ride="carousel" data-mdb-carousel-init>
        <!-- Indicators -->
        <div class="carousel-indicators">
            <button type="button" data-mdb-target="#carouselBasicExample" data-mdb-slide-to="0" class="active"
                aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-mdb-target="#carouselBasicExample" data-mdb-slide-to="1"
                aria-label="Slide 2"></button>
            <button type="button" data-mdb-target="#carouselBasicExample" data-mdb-slide-to="2"
                aria-label="Slide 3"></button>
        </div>

        <!-- Inner -->
        <div class="carousel-inner">
            <!-- Single item -->
            <div class="carousel-item active">
                <img style="height: 500px"
                    src="https://asset.tix.id/microsite_v2/d2b394a8-caae-4e0b-b455-7fdb2139ec29.webp"
                    class="d-block w-100" alt="Sunset Over the City" />
                <div class="carousel-caption d-none d-md-block">
                </div>
            </div>

            <!-- Single item -->
            <div class="carousel-item">
                <img style="height: 500px"
                    src="https://asset.tix.id/microsite_v2/eecadfbb-5a01-4b2f-bf0b-2bda3883f593.webp"
                    class="d-block w-100" alt="Canyon at Nigh" />
                <div class="carousel-caption d-none d-md-block">
                </div>
            </div>

            <!-- Single item -->
            <div class="carousel-item">
                <img style="height: 500px"
                    src="https://asset.tix.id/wp-content/uploads/2025/08/6b0d4ff8-101c-41e2-aaec-cd588dd837b3-600x885.webp"
                    class="d-block w-100" alt="Cliff Above a Stormy Sea" />
                <div class="carousel-caption d-none d-md-block">
                </div>
            </div>
        </div>
        <!-- Inner -->

        <!-- Controls -->
        <button class="carousel-control-prev" type="button" data-mdb-target="#carouselBasicExample" data-mdb-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-mdb-target="#carouselBasicExample" data-mdb-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
    <!-- Carousel wrapper -->

    <div class="container my-4">
        <div class="d-flex justify-content-between align-items-center">
            {{-- konten kri  --}}
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-clapperboard"></i>
                <h5 class="ms-2 mt-2">Sedang Tayang</h5>
            </div>
            {{-- konten kanan --}}
            <div>
                <a href="{{route ('home.movies.all') }}" class="btn btn-warning rounded-pill">Semua</a>
            </div>
        </div>
    </div>

    <div class="container d-flex gap-2">
        {{-- gap2  --}}
        <button class="btn btn-outline-primary rounded-pill">Semua Film</button>
        <button class="btn btn-outline-secondary rounded-pill">XXI</button>
        <button class="btn btn-outline-secondary rounded-pill">CINEPOLIS</button>
        <button class="btn btn-outline-secondary rounded-pill">IMAX</button>
    </div>

<div class="container d-flex gap-2 mt-4 justify-content-center">
  <!-- Card 1 -->
  @foreach ( $movies as $key => $item )
  <div class="card" style="width: 18rem;">
    <img src="{{ asset('storage/' .  $item['poster']) }}" class="card-img-top" alt="Sunset Over the Sea" style="height: 350px; object-fit: cover;" />
    <div class="card-body bg-primary text-warning text-center" style="padding:0 !important;">
      <p class="card-text">
        <a href="{{ route('schedules.detail', $item['id']) }}" class="text-warning text-decoration-none">BELI TIKET</a>
      </p>
    </div>
  </div>
  @endforeach
</div>
<footer class="bg-body-tertiary text-center text-lg-start mt-5">
  <!-- Copyright -->
  <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
    © 2020 Copyright:
    <a class="text-body" href="https://mdbootstrap.com/">MDBootstrap.com</a>
  </div>
  <!-- Copyright -->
</footer>

@endsection

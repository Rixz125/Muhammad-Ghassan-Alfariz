@extends ('templates.app')
@section('content')
    <div class="container mmy-5">
        <h5 class="mb-5">Seluruh Film Sedang Tayang</h5>

        <form class="row mb-3" method="GET" action="">
            <div class="col-10">
                <input type="text" name="search_movie"
                placeholder="Cari judul film..."class="form-control">
            </div>
            <div class="col-2">
                <button type="submit" class="btn btn-primary">Cari</button>
            </div>
        </form>
        <div class="container d-flex gap-2 mt-4 justify-content-center">
        @foreach ($movies as $key => $item)
            
        
        <div class="card" style="width: 18rem">
            <img src="{{ asset('storage/'. $item['poster']) }}" class="card-img-top"
                alt="Poster Film" style="height: 350px; object-fit: cover;">
            <div class="card-body bg-primary text-warning text-center p-2">
                <a href="{{ route('schedules.detail', $item['id']) }}" class="text-warning fw-bold">BELI TIKET</a>
            </div>
        </div>
        @endforeach
    </div>
    </div>
@endsection
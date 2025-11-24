@extends('templates.app')

@section('content')
    <div class="container my-5">
        @if (Session::get('success'))
            <div class="alert alert-success">
                {{ Session::get('success') }}</div>
        @endif
        <div class="d-flex justify-content-end">
            <a href="{{ route('admin.movies.export') }}" class="btn btn-secondary me-2">Export (.xlsx)</a>
            <a href="{{ route('admin.movies.trash') }}" class="btn btn-outline-secondary me-2">Data Sampah</a>
            <a href="{{ route('admin.movies.create') }}" class="btn btn-success">Tambah Data</a>
        </div>
        <h5 class="mb-3">Data Film</h5>
        <table class="table table-bordered" id="moviesTable">
            <thead>
                <tr class="text-center">
                    <th>No</th>
                    <th>Poster</th>
                    <th>Judul Film</th>
                    <th>Status Aktif</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>

        <!-- Modal -->
        <div class="modal fade" id="modalDetail" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Detail Film</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="modalDetailBody">
                        ...
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- mengisi stack --}}
@push('script')
    <script>
        $(function() {
            $('#moviesTable').DataTable({
                processing: true,
                // data utk datatable diproses secara serverside (controller)
                serverSide: true,
                // routing menuju fungsi yang memproses data utk datatable
                ajax: "{{ route('admin.movies.datatables') }}",
                // urutan column (td), pastikan urutan sesuai th
                // data: 'nama'->  nama ambil dari rawColumns jika addColumns,
                // attau field dari model fillable
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'poster_img',
                        name: 'poster_img',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'title',
                        name: 'title',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'actived_badge',
                        name: 'actived_badge',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });
        });
    </script>

    <script>
        function showModal(item) {
            // console.log(item)
            // pengambilan gambar di public
            // asset()  fungsi php sehingga gunakan kurung kurawal 2 kali
            let image = "{{ asset('storage/') }}" + "/" + item.poster;
            // membuat konten yg akan ditambhakan
            // backtip (diatas tab) : menulis string lebih dari 1 baris
            let content = `
            <img src="${image}" width="120" class="d-block mx-auto my-3">
    <ul>
        <li>Judul : ${item.title}</li>
        <li>Durasi : ${item.duration}</li>
        <li>Genre : ${item.genre}</li>
        <li>Sutradara : ${item.director}</li>
        <li>Usia Minimal : <span class="badge badge-danger">${item.age_rating}+</span></li>
        <li>Sinopsis : ${item.description}</li>
    </ul>
       `;
            // mengambil element html  yg akan disimpan konten diatas : document.querySelector()
            let modalDetailBody = document.querySelector("#modalDetailBody")
            // isi html diatas ke id="modalDetailBody"
            modalDetailBody.innerHTML = content;
            let modalDetail = document.querySelector("#modalDetail")
            //munculkan modal bootstrap
            new bootstrap.Modal(modalDetail).show();
        }
    </script>
@endpush

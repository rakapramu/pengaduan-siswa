@extends('layouts.dashboard')
@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>{{ ucwords($title) }}</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="#">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                {{ ucwords($title) }}
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <!-- Basic Tables start -->
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h5 class="card-title mb-0">Data {{ ucwords($title) }}</h5>

                        <div class="ms-auto d-flex gap-2">
                            @if (Auth::user()->role == 'siswa')
                                <a data-bs-toggle="modal" data-bs-target="#backdrop" class="btn btn-primary ms-auto">Ajukan
                                    Konseling</a>
                            @endif
                            @if (Auth::user()->role == 'guru' || Auth::user()->role == 'admin')
                                <a href="{{ route('konseling.export') }}" class="btn btn-danger">
                                    <i class="bi bi-file-earmark-pdf-fill"></i>
                                    Export PDF
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" id="dataTable">
                            <thead>
                                <tr>
                                    <th>Guru BK</th>
                                    <th>Topik</th>
                                    <th>Deskripsi</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $item)
                                    <tr>
                                        <td>{{ ucwords($item->guru->nama) ?? '' }}</td>
                                        <td>{{ $item->topik }}</td>
                                        <td>{{ $item->deskripsi }}</td>
                                        <td>
                                            @if ($item->status == 'proses')
                                                <span class="badge bg-warning">Proses</span>
                                            @elseif ($item->status == 'batal')
                                                <span class="badge bg-danger">Ditolak</span>
                                            @else
                                                <span class="badge bg-success">Selesai</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if (Auth::user()->role == 'guru' || Auth::user()->role == 'admin')
                                                <a href="{{ route('konseling.show', $item->id) }}"
                                                    class="btn btn-primary">Detail</a>
                                            @endif
                                            @if (Auth::user()->role == 'admin')
                                                <form action="{{ route('konseling.destroy', $item->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
        <!-- Basic Tables end -->
    </div>

    {{-- modal --}}
    <div class="modal fade" id="backdrop" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Pengajuan Konseling
                    </h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" id="formKonseling">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="guru" class="form-label">Rencana Tanggal Konseling</label>
                                <input type="date" class="form-control flatpickr" placeholder="Pilih Tanggan dan Jam"
                                    name="tanggal">
                                <span class="text-danger error-tanggal"></span>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="guru" class="form-label">Topik</label>
                                <input type="text" class="form-control" name="topik" placeholder="Cth: Bullying">
                                <span class="text-danger error-topik"></span>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="guru" class="form-label">Permasalahan</label>
                                <textarea name="deskripsi" class="form-control" id="" cols="30" rows="5"
                                    placeholder="Deskripsikan apa yang terjadi"></textarea>
                                <span class="text-danger error-deskripsi"></span>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" id="btnSubmit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        $('#btnSubmit').click(function(e) {
            e.preventDefault();
            let form = $('#formKonseling');

            // Hapus error lama
            $('.text-danger').text("");

            $.ajax({
                url: "{{ route('konseling.store') }}",
                method: "POST",
                data: form.serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res) {
                    if (res.status) {
                        $('#backdrop').modal('hide');
                        form.trigger("reset");

                        Toastify({
                            text: res.message,
                            duration: 3000,
                            close: true,
                            gravity: "top",
                            position: "center",
                            backgroundColor: "#4fbe87",
                        }).showToast()
                        location.reload();
                    } else {
                        // $('#backdrop').modal('hide');
                        form.trigger("reset");

                        Toastify({
                            text: res.message,
                            duration: 3000,
                            close: true,
                            gravity: "top",
                            position: "center",
                            backgroundColor: "#4fbe87",
                        }).showToast()
                    }
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON.errors;

                    // Tampilkan error di bawah input
                    $.each(errors, function(key, value) {
                        $('.error-' + key).text(value[0]);
                    });
                }
            });

        });
    </script>
@endpush

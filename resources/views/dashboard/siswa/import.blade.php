@extends('layouts.dashboard')
@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Form Tambah {{ ucwords($title) }}</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="#">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Form Import {{ ucwords($title) }}
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <!-- // Basic multiple Column Form section start -->
        <section id="multiple-column-form">
            <div class="row match-height">
                <div class="col-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                @if (session('error'))
                                    <div class="alert alert-danger">
                                        {{ session('error') }}
                                    </div>
                                @endif
                                <form class="form" action="{{ route('siswa-import.post') }}" method="POST"
                                    enctype="multipart/form-data" accept="xls,xlsx">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-12 col-12">
                                            <div class="form-group">
                                                <label for="">Nama Lengkap</label>
                                                <input type="file" id=""
                                                    class="form-control @error('name') is-invalid @enderror"
                                                    name="file" />
                                                @error('file')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12 d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary me-1 mb-1">
                                                Submit
                                            </button>
                                            <button type="reset" class="btn btn-light-secondary me-1 mb-1">
                                                Reset
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    @if (session()->has('failures'))
        <div class="alert alert-danger mt-3">
            <strong>Beberapa baris gagal diimport:</strong>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Row</th>
                    <th>Attribute</th>
                    <th>Error</th>
                    <th>Value</th>
                </tr>
            </thead>
            <tbody>
                @foreach (session('failures') as $failure)
                    @foreach ($failure->errors() as $error)
                        <tr>
                            <td>{{ $failure->row() }}</td>
                            <td>{{ $failure->attribute() }}</td>
                            <td>{{ $error }}</td>
                            <td>{{ $failure->values()[$failure->attribute()] ?? '' }}</td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    @endif
@endsection

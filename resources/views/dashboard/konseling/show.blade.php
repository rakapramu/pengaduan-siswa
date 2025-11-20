@extends('layouts.dashboard')
@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Form Detail {{ ucwords($title) }}</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="#">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Form Detail {{ ucwords($title) }}
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
                                <form class="form" action="{{ route('konseling.update', $konseling->id) }}"
                                    method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="accordion" id="accordionPanelsStayOpenExample">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="panelsStayOpen-headingOne">
                                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                    data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true"
                                                    aria-controls="panelsStayOpen-collapseOne">
                                                    Detail Siswa dan Keluhan
                                                </button>
                                            </h2>
                                            <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show"
                                                aria-labelledby="panelsStayOpen-headingOne">
                                                <div class="accordion-body">
                                                    <div class="row">
                                                        <div class="col-md-6 col-12">
                                                            <div class="form-group">
                                                                <label for="">Nama Siswa</label>
                                                                <input type="text" id="" class="form-control"
                                                                    value="{{ $konseling->siswa->nama }}" readonly />
                                                                @error('name')
                                                                    <div class="invalid-feedback">
                                                                        {{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 col-12">
                                                            <div class="form-group">
                                                                <label for="">NIS</label>
                                                                <input type="text" id="" class="form-control"
                                                                    value="{{ $konseling->siswa->nis }}"readonly />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 col-12">
                                                            <div class="form-group">
                                                                <label for="">Keluhan dialami</label>
                                                                <textarea class="form-control" cols="10" rows="5">
                                                                    {{ $konseling->deskripsi }}
                                                                </textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingTwo">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapseTwo"
                                                    aria-expanded="false" aria-controls="collapseTwo">
                                                    Detail Konseling & Tindak Lanjut
                                                </button>
                                            </h2>
                                            <div id="collapseTwo" class="accordion-collapse collapse"
                                                aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <div class="row">
                                                        <div class="col-md-12 col-12">
                                                            <div class="form-group">
                                                                <label for="">Tindak Lanjut</label>
                                                                <textarea class="form-control" cols="10" rows="5" name="tindak_lanjut">
                                                                    {{ $konseling->tindak_lanjut }}
                                                                </textarea>
                                                            </div>
                                                            @error('tindak_lanjut')
                                                                <div class="invalid-feedback">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-12 col-12">
                                                            <div class="form-group">
                                                                <label for="">Status Konseling</label>
                                                                <select name="status" class="form-control" id="">
                                                                    <option disabled selected>--Pilih Status--</option>
                                                                    <option value="batal"
                                                                        {{ $konseling->status == 'batal' ? 'selected' : '' }}>
                                                                        Batal</option>
                                                                    <option value="selesai"
                                                                        {{ $konseling->status == 'selesai' ? 'selected' : '' }}>
                                                                        Selesai</option>
                                                                </select>
                                                            </div>
                                                            @error('status')
                                                                <div class="invalid-feedback">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
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
        <!-- // Basic multiple Column Form section end -->
    </div>
@endsection

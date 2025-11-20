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
                    <div class="d-flex">
                        <h5 class="card-title">Data {{ ucwords($title) }}</h5>
                        <a href="{{ route('siswa.create') }}" class="btn btn-primary ms-auto">Tambah Data</a>
                        {{-- upload with excel --}}
                        <a href="{{ route('siswa.import') }}" class="btn btn-primary ms-auto">
                            <i class="bi bi-file-earmark-excel-fill"></i>Import Data</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" id="dataTable">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>NIS</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $item)
                                    <tr>
                                        <td>{{ ucwords($item->name) }}</td>
                                        <td>{{ $item->email }}</td>
                                        <td>{{ $item->siswa->nis ?? '' }}</td>
                                        <td>
                                            <div class="d-flex">
                                                <a href="{{ route('siswa.edit', $item->id) }}"
                                                    class="btn btn-warning">Edit</a>
                                                <form action="{{ route('siswa.destroy', $item->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </div>
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
@endsection

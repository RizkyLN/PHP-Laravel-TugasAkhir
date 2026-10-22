@extends('layouts.app')

@section('title', 'Daftar Mahasiswa')

@section('content')
    <h1>Daftar Mahasiswa</h1>

    @if (count($mahasiswa) === 0)
        <p class="text-danger">Data tidak ditemukan.</p>
    @else
        <table border="1" cellpadding="6" cellspacing="0">
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>IPK</th>
                <th>Status</th>
            </tr>
            @foreach ($mahasiswa as $mhs)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $mhs['nama'] }}</td>
                    <td>{{ $mhs['ipk'] }}</td>
                    <td>@if ($mhs['ipk'] >= 3.5 ) Cumlaude 
                        @else Reguler @endif</td>
                </tr>
            @endforeach
        </table>
    @endif
@endsection
@extends('layouts.app')

@section('content')
    <h1>Dashboard Guru</h1>

    <ul>
        <a href="{{route('guru.evidence.index')}}"><li>Upload Evidence</li></a>
        <li>History Evidence</li>
        <li>Lihat Hasil Penilaian</li>
    </ul>
@endsection
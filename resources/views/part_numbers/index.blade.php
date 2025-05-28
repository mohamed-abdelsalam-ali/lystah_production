@extends('layouts.master')

@section('content')
    <div class="main-content ">
        <div class="page-content">
            <h1>Import Numbers</h1>

            @if (session('success'))
                <div style="color: green;">{{ session('success') }}</div>
            @endif

            <h2>Import Excel</h2>
            <form action="{{ route('part_numbers.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="file" required>
                <button type="submit">Import</button>
            </form>

            <h2>Parts and Numbers</h2>
            <table cellpadding="5" cellspacing="0">
                <thead>
                    <tr>
                        <th>Part Name</th>
                        <th>Number</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($partNumbers as $pn)
                        <tr>
                            <td>{{ $pn->part->name ?? 'Unknown' }}</td>
                            <td>{{ $pn->number }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
@endsection
@section('js')
    <script></script>
@endsection

@extends('layouts.master')
@section('css')
@endsection
@section('title')
    الشجرة المحاسبية
@endsection

@section('content')
    <main role="main" class="main-content ">
        <div class="page-content">
            <div class="card px-2">    
                <h2>
                    {{ $journal->name }}
                </h2>

                <span>
                    {{ $journal->created_at }}
                </span>

                @if( isset($groupedJournalDetails))

                    @foreach($groupedJournalDetails as $header => $details)
                        <h3>{{ $header }}</h3> <!-- Display the details_header as the section title -->
                        <div class="row px-2">
                            <table class="table table-striped table-sm ">
                                <thead class="bg-info">
                                    <tr>
                                        <td> Name</td>
                                        <td>Note</td>
                                        <td>Account Name</td>
                                        <td>Account Number</td>
                                        <td>Payment methods</td>
                                    </tr>
                                </thead>
                                <tbody>
                            @foreach($details as $detail)
                                {{-- <div class="card col mb-3 mx-2">
                                    <div class="card-header">
                                        <h3>{{ $detail->name }}</h3>
                                    </div>
                                    <div class="card-body">
                                        
                                        <p><strong>Name:</strong> {{ $detail->name }}</p>
                                        <p><strong>Note:</strong> {{ $detail->note }}</p>
                                        <p><strong>COA :</strong> {{ $detail->get_coa->name_ar }}</p>
                                        <p><strong>COA Account:</strong> {{ $detail->get_coa->ac_number }}</p>
                                        
                                        <p><strong>Default:</strong> {{ $detail->is_default ? 'Yes' : 'No' }}</p>
                                    </div>
                                </div> --}}

                                <tr>
                                    <td> {{ $detail->name }}</td>
                                    <td>{{ $detail->note }}</td>
                                    <td>{{ $detail->get_coa->name_ar }}</td>
                                    <td>{{ $detail->get_coa->ac_number }}</td>
                                    @if ($detail->is_default==1)
                                        <td>كاش</td>
                                    @elseif ($detail->is_default==2)
                                        <td>شيك</td>
                                    @else
                                        <td>-</td>
                                    @endif
                                    
                                </tr>
                            @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endforeach
                
                    
                @else
                    <h3> No Details </h3>
                @endif
            </div>    
        </div>
    </main>
@endsection




@section('js')
    <script>
        
    </script>
@endsection

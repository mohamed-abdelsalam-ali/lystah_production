@extends('layouts.master')
@section('css')

    <style>

    </style>

@endsection
@section('title')
    Calender
@stop


@section('content')

    @if ($message = Session::get('success'))
        <div class="alert alert-primary " style="z-index: 88888 !important;" role="alert">
            <button type="button" class="btn-close bg-black text-white" data-bs-dismiss="alert" aria-label="Close"></button>
            {{ $message }}
        </div>
    @endif

    <div class="main-content">
        <div class="page-content">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                        <h4 class="mb-sm-0">Calender</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item active">Calender</li>
                                <li class="breadcrumb-item"><a href="home">Dashboards</a></li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div id='calendar' style="direction: ltr !important" ></div>
                </div>
            </div>

            <div class="modal fade" id="event-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span
                                    aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                            <h4 class="modal-title">Modal title</h4>
                        </div>
                        <div class="modal-body">
                            <form name="save-event" method="post">
                                <div class="form-group">
                                    <label>Title</label>
                                    <input type="text" name="title" class="form-control" />
                                </div>
                                <div class="form-group">
                                    <label>Event start</label>
                                    <input type="date" name="evtStart" class="form-control col-xs-3" />
                                </div>
                                <div class="form-group">
                                    <label>Event end</label>
                                    <input type="date" name="evtEnd" class="form-control col-xs-3" />
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->

        </div>
    </div>



@endsection

@section('js')
    <script src="{{ URL::asset('fullcalendar/dist/index.global.min.js') }}"></script>
    <script src="{{ URL::asset('fullcalendar/packages/core/locales/ar.global.min.js') }}"></script>

    <script>
        var buybillDueDate = {!! $buybillDueDate !!};
        var buybillDueDateList = [];
        buybillDueDate.forEach(element => {
            buybillDueDateList.push({
                'title': element.supplier.name,
                'start': element.due_date,
                'color': '#257e4a',
                'data': element
            })
        });
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                locale: 'ar',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
                },
                initialDate: Date.now(),
                height: 'auto',
                navLinks: true, // can click day/week names to navigate views
                // businessHours: true, // display business hours
                editable: true,
                // droppable: true,
                selectable: true,
                events: buybillDueDateList,
                eventClick: function(info) {
                    console.log(info.event.extendedProps.data);
                },
                eventDrop: function(event, dayDelta, minuteDelta, allDay, revertFunc, jsEvent, ui, view) {
                    console.log(event.event.start);
                    console.log(event.event.extendedProps.data);
                },

                select: function( start, end, jsEvent, view ) {
        // set values in inputs
        $('#event-modal').find('input[name=evtStart]').val(
            start.startStr
        );
        $('#event-modal').find('input[name=evtEnd]').val(
            start.endStr
        );

        // show modal dialog
        $('#event-modal').modal('show');

        /*
        bind event submit. Will perform a ajax call in order to save the event to the database.
        When save is successful, close modal dialog and refresh fullcalendar.
        */
        /*
        $("#event-modal").find('form').on('submit', function() {
            $.ajax({
                url: 'yourFileUrl.php',
                data: $("#event-modal").serialize(),
                type: 'post',
                dataType: 'json',
                success: function(response) {
                    // if saved, close modal
                    $("#event-modal").modal('hide');

                    // refetch event source, so event will be showen in calendar
                    $("#calendar").fullCalendar( 'refetchEvents' );
                }
            });
        });*/
    },
    selectHelper: true,
    selectable: true,
    snapDuration: '00:10:00'

            });


            calendar.render();

            $('.fc-icon-chevron-right').addClass('bx bx-pre').removeClass('fc-icon-chevron-right')
            $('.fc-icon-chevron-left').addClass('bx bx-next').removeClass('fc-icon-chevron-left')
        });
    </script>



@endsection

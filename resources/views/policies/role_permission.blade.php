@extends('layouts.master')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ URL::asset('assets/dataTables/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/dataTables/buttons.bootstrap5.min.css') }}">


    <style>
        th {
            text-align: center !important;
        }

        #allUser_perm_filter label {
            float: left;
            padding-top: 5px;
        }

        #allUser_perm_filter input {
            border: 0px !important;
            border-bottom: 1px rgb(85, 4, 4) solid !important;

        }

        .itemimg:hover {
            width: 200px;
            height: 200px;
        }

        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked+.slider {
            background-color: #2196F3;
        }

        input:focus+.slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked+.slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }
    </style>
@endsection
@section('title')
    Role Permition
@stop


@section('content')

    @if ($message = Session::get('success'))
        <div class="alert alert-primary " style="z-index: 88888 !important;" role="alert">
            <button type="button" class="btn-close bg-black text-white" data-bs-dismiss="alert"
                aria-label="Close"></button>
            {{ $message }}
        </div>
    @endif

    <div class="main-content">
        <div class="page-content">
                      <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                        <h4 class="mb-sm-0">Role Permition</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item active">Role</li>
                                <li class="breadcrumb-item"><a href="home">Dashboards</a></li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                       
                        <div class="card-body table-responsive">
                            <table   id="allUser_perm" class="table table-striped table-bordered cell-border " style="width:100%" >
                                <thead style="background:#5fcee78a">
                                    <tr class="text-center">

                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>


@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    {{-- <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script> --}}

    {{-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
     --}}
    <script src="{{ URL::asset('assets/js/select2.min.js') }}"></script>

    <script src="{{ URL::asset('assets/dataTables/dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/dataTables/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ URL::asset('assets/dataTables/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('assets/dataTables/buttons.bootstrap5.min.js') }}"></script>
    <script src="{{ URL::asset('assets/dataTables/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('assets/dataTables/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('assets/dataTables/vfs_fonts.js') }}"></script>
    <script src="{{ URL::asset('assets/dataTables/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('assets/dataTables/buttons.print.min.js') }}"></script>


    <script>
        var allUser_perm = "";
        var dtable_api = '';
        $(document).ready(function() {
            var permissions = {!! $permissions !!};
            console.log(permissions);
            var roles = {!! $roles !!};
            console.log(roles[0].name);

            var role_perms = {!! $role_perms !!};
            console.log(role_perms);
            drawPartTbl(permissions, roles, role_perms);


        });

        function drawPartTbl(permissions, roles, role_perms) {
            var arr = {};
            var JSONResult = [];
            arr[0] = "صلاحيات/وظائف"
            for (let i = 0; i < roles.length; i++) {
                // var col="column"+i;
                arr[roles[i].id] = roles[i].name

            }
            JSONResult.push(arr)
            // console.log(JSONResult)
            // var JSONResult = [{ "column1": "data1", "column2": "data2", "column3": "data3", "columnN": "dataN" },
            // { "column1": "data1", "column2": "data2", "column3": "data3", "columnN": "dataN" }];

            $.each(JSONResult[0], function(key, value) {
                $('#allUser_perm thead tr:first-child').append($('<th>', {
                    text: value
                }));
            });


            dtable_api = $('#allUser_perm').dataTable();
            // dtable_api.empty()
            for (let i = 0; i < permissions.length; i++) {
                var row_dtable = new Array();
                row_dtable.push(`${permissions[i]['name']} / (${permissions[i]['perm_desc_ar']})`);
                for (let j = 1; j < Object.keys(JSONResult[0]).length + 1; j++) {
                    //    console.log(Object.keys(JSONResult[0])[j])
                    var checker = ""
                    for (let k = 0; k < role_perms.length; k++) {
                        // console.log(Object.keys(JSONResult[0])[j])
                        // console.log(resArr[2].role_perm[k].role_id)
                        // console.log("------------------------------")
                        if (Object.keys(JSONResult[0])[j] == role_perms[k].role_id && permissions[i].id == role_perms[k]
                            .permission_id) {
                            checker = 1;
                        } else {

                        }
                    }
                    if (checker) {
                        row_dtable.push(`<label class="switch">
               <input class="roles" type="checkbox" checked  data-role="${Object.keys(JSONResult[0])[j]}" data-perm="${permissions[i].id}">
               <span class="slider round"></span>
             </label>`);
                    } else {
                        row_dtable.push(`<label class="switch"  >
               <input class="roles"  type="checkbox" data-role="${Object.keys(JSONResult[0])[j]}" data-perm="${permissions[i].id}">
               <span class="slider round"></span>
             </label>`);
                    }


                }

                dtable_api.api().row.add(row_dtable).draw(false);

            }



        }
    </script>
    <script>
        var flag_checkbox = "";
        $(document.body).on("click", 'input[type="checkbox"]', function() {

            var roleId = $(this).attr('data-role');
            var perm_id = $(this).attr('data-perm');
            if ($(this).prop("checked") == true) {
                console.log("Checkbox is checked.");
                flag_checkbox = 1;
            } else if ($(this).prop("checked") == false) {
                flag_checkbox = 0;
                console.log("Checkbox is unchecked.");
            }
            change_perm(roleId, perm_id)
        });

        function change_perm(role_id, user_id) {



            // alert(role_id + "----" + user_id + "----" + flag_checkbox);
            //  $("#preloader").css('visibility', 'visible');
            // $("#preloader").css('opacity', '1')
            $.ajax({

                url: "{{ URL::to('save_role_perm') }}/",
                type: "get",
                data: {
                    processName: "update_user_role",
                    roleID: role_id,
                    perm_id: user_id,
                    flagCheckbox: flag_checkbox

                },
                dataType: "json",
                success: function(data) {
                    $("#preloader").css('visibility', 'hidden');
                    $("#preloader").css('opacity', '0')
                },complete: function(data) {
                    // $("#preloader").css('visibility', 'hidden');
                    // $("#preloader").css('opacity', '0')
                    //  location.reload();
                }
            });

        }
    </script>



@endsection

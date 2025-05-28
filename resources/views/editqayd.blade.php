@extends('layouts.master')
@section('css')

@section('content')

    <div class="main-content ">
        <div class="page-content">
            <div class="bg-white mx-2 row">

                <div class="col-lg-12">

                    <form action="{{ url('save_editqayd') }}" method="POST" class="bg-white p-4">
                        @csrf
                        <input type="hidden" name="qaydId" id="qaydId" value="{{ $qayd->id }}">
                        <div class="row">
                            <div class="col-lg-12">
                              <input type="hidden" name="invoice_id" value="{{ $qayd->invoice_id }}">
                              <input type="hidden" name="invoice_flag" value="{{ $qayd->invoice_flag }}">

                            </div>
                        </div>
                        <div class="row form-group   mb-2">
                            <label class="col-lg-2 col-form-label font1"> التاريخ
                            </label>
                            <div class="col-lg-4">
                                <input id="dateQayd" name="dateQayd" type="date"
                                    value="{{ isset($qayd->date) ? $qayd->date : '' }}" class="form-control">

                            </div>
                            <label class="col-lg-2 col-form-label font1">نوع القيد
                            </label>
                            <div class="col-lg-4">
                                <select id="qaydType" class="form-control" name="qaydType">
                                    @foreach ($qayd_type as $key => $value)
                                        @if ($value->id == $qayd->qaydtypeid)
                                            <option selected value="{{ $value->id }}">{{ $value->name }}</option>
                                        @else
                                            <option value="{{ $value->id }}">{{ $value->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group  mt-3  font1">
                            <button type="button" onclick="addrowitems()"class="btn btn-success">اضافة</button>
                        </div>
                        <div class=" row form-group mb-2">
                            <div class="table-responsive">
                                <table
                                    class="table table-striped  table-bordered border-light  text-center justify-content-center">
                                    <thead class="border-light text-center">
                                        <tr class="h">


                                            <th class="text-center">اسم الحساب</th>
                                            <th class="text-center">رقم الحساب</th>
                                            <th class="text-center">البيان </th>
                                            <th class="text-center">مدين الأموال الواردة إلى الحساب </th>
                                            <th class="text-center">دائن الأموال الخارجة من الحساب </th>
                                            <th class="text-center">حذف </th>
                                        </tr>

                                    </thead>

                                    <tbody id="qayditems">



                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="form-group  mt-3 text-center">
                            <button type="submit" class="btn btn-primary w-25">حفظ</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>





@endsection

@section('js')


    <script>
        var qayditems = {!! $qayd->qayditems !!};
        let count=0;
        function addrowitems() {

            $('#qayditems').append(`
                    <tr>
                        <td class="text-center col-sm-3" >
                            <select class="form-control" id="branch${count}" name="branch[]" onchange='dothis(this)' required>

                            </select>
                        </td>
                        <td class="text-center " id="number${count}">
                            <input type="number" class="form-control" disabled name="accountNum[]" id="accountNum${count}">
                        </td>
                        <td class="text-center "><textarea class="form-control" name="note[]" style="resize:none"></textarea> </td>
                        <td class="text-center "><input type="number" step="any" min="0"class="form-control" name="maden[]"> </td>
                        <td class="text-center "><input type="number" step="any" min="0"class="form-control" name="dayn[]"> </td>
                        <td class="text-center "><button type="button" onclick="removeRow(this)" class="btn btn-danger">حذف</button> </td>


                    </tr>

            `);
            getAllFinalChild();
        }

        function getAllFinalChild() {

            $.ajax({
                method: "GET",
                url: "/getallfinalchild",
                async: false,

                datatype: 'JSON',
                statusCode: {
                    404: function() {
                        alert("page not found");
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    alert("some error");
                    console.log(errorThrown);
                },
                success: function(data) {
                    console.log(data);
                    if (data) {
                        // var resArr = $.parseJSON(data);

                        $('#branch' + count).append(
                            `<option  value=""  selected>أختيــــار نوع الحساب </option>`);
                        for (let i = 0; i < data.length; i++) {

                            $('#branch' + count).append(`<option value='${data[i].id}' data-number='${data[i].accountant_number}'>
                                ${data[i].name}-${data[i].accountant_number}
                                </option>`);
                        }
                    }
                    $('#branch' + count).select2({
                        theme: "bootstrap4"
                    });

                    count++;

                    $('#branchs').append(`<option  value=""  selected>أختيــــار نوع الحساب </option>`);
                    for (let i = 0; i < data.length; i++) {

                        $('#branchs').append(`<option value='${data[i].id}' data-number='${data[i].accountant_number}'>
                        ${data[i].name}-${data[i].accountant_number}
                        </option>`);
                    }

                    $('#branchs').select2({
                        theme: "bootstrap4"
                    });




                }
            });

        }

        function dothis(el){
            let co=el.getAttribute("id");
            let n=co.substring(6)
            document.getElementById("accountNum"+n).value=el.options[el.selectedIndex].getAttribute("data-number");
        }

        $(document).ready(function() {

            qayditems.forEach(element => {

                $('#qayditems').append(`
                        <tr>
                            <td class="text-center col-sm-3" >
                                <select class="form-control" id="branch${count}" name="branch[]" onchange='dothis(this)' required>

                                </select>
                            </td>
                            <td class="text-center " id="number${count}">
                                <input type="number" class="form-control accountNum" disabled name="accountNum[]" id="accountNum${count}">
                            </td>
                            <td class="text-center "><textarea class="form-control" name="note[]" style="resize:none"></textarea> </td>
                            <td class="text-center "><input type="number"  step="any" min="0"class="form-control maden" name="maden[]"> </td>
                            <td class="text-center "><input type="number"  step="any" min="0"class="form-control dayn" name="dayn[]"> </td>
                            <td class="text-center "><button type="button" onclick="removeRow(this)" class="btn btn-danger">حذف</button> </td>


                        </tr>

                `);
                getAllFinalChild();
                $('#qayditems tr:last').find('select').val(element.branchid).trigger('change');
                $('#qayditems tr:last').find('textarea').val(element.topic);
                $('#qayditems tr:last').find('input.dayn').val(element.dayin);
                $('#qayditems tr:last').find('input.maden').val(element.madin);
                $('#qayditems tr:last').find('input.accountNum').val(element.branchid);

                // $.when(addrowitems()).then(function(){
                //     $('#qayditems tr:last').find('select').val(element.branchid).trigger('change')
                // })
            });
        })
        function removeRow(el){
            $(el).closest('tr').remove();
        }


    </script>

@endsection

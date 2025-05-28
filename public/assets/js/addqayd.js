$( document ).ready(function() {
    getAllFinalChild();
    getQaydType();
});
let count=0;
function getQaydType(){

    $.ajax({
    method: "GET",
    url: "/getqaydtype",
    async : false,

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
        // console.log(data);
      if (data) {
        // var resArr = $.parseJSON(data);

          $('#qaydType').append(`<option  value=""  selected>أختيــــار نوع القيد</option>`);
          for (let i = 0; i < data.length; i++) {

              $('#qaydType').append(`<option value='${data[i].id}'>
              ${data[i].name}
              </option>`);}
        }
        $('#qaydType').select2({
            theme: "bootstrap4"
        });





    }
    });

}

function addrowitems(){

    $('#qayditems').append(`
            <tr>
                <td class="text-center col-sm-3" >
                    <select class="form-control" id="branch${count}" name="branch[]" onchange='dothis(this)'>

                    </select>
                </td>
                <td class="text-center " id="number${count}">
                    <input type="number" class="form-control" disabled name="accountNum[]" id="accountNum${count}">
                </td>
                <td class="text-center "><textarea class="form-control" name="note[]" style="resize:none"></textarea> </td>
                <td class="text-center "><input type="number" min="0"class="form-control" name="maden[]"> </td>
                <td class="text-center "><input type="number" min="0"class="form-control" name="dayn[]"> </td>
                <td class="text-center "><button type="button" onclick="removeRow(this)" class="btn btn-danger">حذف</button> </td>


            </tr>

    `);
    getAllFinalChild();
}

function getAllFinalChild(){

    $.ajax({
    method: "GET",
    url: "/getallfinalchild",
    async : false,

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

              $('#branch'+count).append(`<option  value=""  selected>أختيــــار نوع الحساب </option>`);
              for (let i = 0; i < data.length; i++) {

                  $('#branch'+count).append(`<option value='${data[i].id}' data-number='${data[i].accountant_number}'>
                  ${data[i].name}-${data[i].accountant_number}
                  </option>`);}
            }
            $('#branch'+count).select2({
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
    console.log(el.options[el.selectedIndex]);
    console.log(el.options[el.selectedIndex].getAttribute("data-number"));
    console.log(el.getAttribute("id"));

    let co=el.getAttribute("id");
    let n=co.substring(6)
    document.getElementById("accountNum"+n).value=el.options[el.selectedIndex].getAttribute("data-number");


}
function removeRow(el){
    $(el).closest('tr').remove();
}

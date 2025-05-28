var partStatusOption="";
var partSourceOption="";
var partQualtyOption="";
var storeOption = "";
var storeSectionOption="";
$(".newInvModalBtn").click(function (e) {
    $.ajax({
        type: "get",
        url: "lastInvId",
        success: function (response) {
            $("#newInvId").empty();
            $("#newInvId").text(parseInt(response)+1);

        }
    });

    $(window).keydown(function(event){
    if(event.keyCode == 13) {
      event.preventDefault();
      return false;
    }
  });

});

$(document).ready(function () {


    $("#invDate").val(new Date().toJSON().slice(0,10)).trigger('chage');

    var table = $('#partsDT').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 50,
        deferRender: true,
        destroy: true,
        ajax: "buyInvData",
        columns: [
            {data: 'id', name: 'id' ,className : 'text-center' },
            {data: 'name', name: 'name'},
            {data: 'efrag', name: 'efrag'},
            {data: 'date', name: 'date' },
            {data: 'creation_date', name: 'creation_date' ,visible : false },
            {data: 'companyName', name: 'companyName'  ,visible : false},
            {data: 'supplierName', name: 'supplierName' },
            {data: 'userName', name: 'UserName'},
             {data: 'qaydNo', name: 'qaydNo'},
            {data: 'companyid', name: 'companyid' ,visible : false },
            {data: 'action', name: 'action' },
            {data: 'upload', name: 'upload' },
            
        ],

    });
    // GET SOURCE - STATUS - QUALITY
    $.ajax({
        type: "get",
        url: "source",
        success: function (response) {
            $("#global_sourceslct").empty();
            $("#global_sourceslct").append(`<option value="0">Select Source</option>`);
            partSourceOption+=`<option value="0">Select Source</option>`;
            response.forEach(element => {
                partSourceOption+=`<option value="${element.id}">${element.name_arabic}</option>`;
                $("#global_sourceslct").append(`<option value="${element.id}">${element.name_arabic}</option>`);
            });
        }
    });

    $.ajax({
        type: "get",
        url: "status",
        success: function (response) {
            $("#global_statuslct").empty();
            $("#global_statuslct").append(`<option value="0">Select Status</option>`);
            partStatusOption+=`<option value="0">Select Status</option>`;
            response.forEach(element => {
                $("#global_statuslct").append(`<option value="${element.id}">${element.name}</option>`);
                partStatusOption+=`<option value="${element.id}">${element.name}</option>`;
            });
        }
    });

    $.ajax({
        type: "get",
        url: "quality",
        success: function (response) {
            $("#global_qualityslct").empty();
            $("#global_qualityslct").append(`<option value="0">Select Quality</option>`);
            partQualtyOption+=`<option value="0">Select Quality</option>`;
            response.forEach(element => {
                $("#global_qualityslct").append(`<option value="${element.id}">${element.name}</option>`);
                partQualtyOption+=`<option value="${element.id}">${element.name}</option>`;
            });
        }
    });
    $.ajax({
        type: "get",
        url: "GetAllstores",
        success: function (response) {
            $("#global_storeslct").empty();
            $("#global_storeslct").append(`<option value="">Select Store</option>`);
            storeOption +=`<option selected disabled value="">Select Store</option>`;
            response.forEach(element => {
                // $(".Stores").append(`<option value="${element.id}">${element.name}</option>`);
                $("#global_storeslct").append(`<option value="${element.id}">${element.name}</option>`);
                storeOption+=`<option value="${element.id}">${element.name}</option>`;
            });
        }
    });

    $.ajax({
        type: "get",
        url: "Selectindex", // Supplier
        success: function (response) {
            $(".supp").empty();
            $(".supp").append(`<option selected disabled value="">-------------</option>`);

            response.forEach(element => {
                $(".supp").append(`<option value="${element.id}">${element.name} - ${element.tel01} - ${element.tel02}</option>`);

            });
        }
    });
    $(".supp").select2({ dropdownParent: $('#staticBackdrop')})

    $.ajax({
        type: "get",
        url: "GetAllCurrency",
        success: function (response) {
            $("#currencySlct").empty();
            $("#currencySlct").append(`<option value="" selected disabled>Select Currency</option>`);

            response.forEach(element => {
                $("#currencySlct").append(`<option value="${element.id}">${element.name}</option>`);

            });
        }
    });
    $("#currencySlct").select2({ dropdownParent: $('#staticBackdrop')})


});



$("#partSlct").select2({
    dropdownParent: $('#staticBackdrop'),

    ajax: {
      url: "partsSearch",
    //   dataType: 'json',
    async : false,
      delay: 250,
      data: function (params) {
        return {
          q: params.term.toLowerCase(), // search term
          page: params.page,
          type: $('#slected_type').val()
        };
      },
      processResults: function (data, params) {
        // parse the results into the format expected by Select2
        // since we are using custom formatting functions we do not need to
        // alter the remote JSON data, except to indicate that infinite
        // scrolling can be used
        // $("#select2-partSlct-results").empty();
        // data.forEach(element => {
        //     $("#select2-partSlct-results").append(`<li>${element.name}</li>`);
        // });

        params.page = params.page || 1 ;
        return {
          results: data,
        //   pagination: {
        //     more: (params.page * 30) < data.total_count
        //   }
        };
      },
      cache: true
    },
    placeholder: 'Search ',
    minimumInputLength: 3,
    templateResult: formatRepo,
    templateSelection: formatRepoSelection

  });

  function formatRepo (repo) {
    //     $("#select2-partSlct-results").append(repo);
    //    return repo;
    if (repo.loading) {
        return repo.text;
    }

    var $container = $(
      "<div class='select2-result-repository clearfix'>" +
        //"<div class='select2-result-repository__avatar d-none'><img src='" + repo.name + "' /></div>" +
        "<div class='select2-result-repository__meta'>" +
          "<div class='select2-result-repository__title'></div>" +
          "<div class='select2-result-repository__description'></div>" +
          "<div class='select2-result-repository__statistics'>" +
            "<div class='select2-result-repository__forks'><i class='fa fa-flash'></i> </div>" +
            "<div class='select2-result-repository__stargazers'><i class='fa fa-star'></i> </div>" +
            "<div class='select2-result-repository__watchers'><i class='fa fa-eye'></i> </div>" +
          "</div>" +
        "</div>" +
      "</div>"
    );


    $container.find(".select2-result-repository__title").text(repo.name);
    // $container.find(".select2-result-repository__description").text(repo.description);
    // $container.find(".select2-result-repository__forks").append(repo.name + " Forks");
    // $container.find(".select2-result-repository__stargazers").append(repo.name + " Stars");
    // $container.find(".select2-result-repository__watchers").append(repo.name + " Watchers");

    return $container;
  }

  function formatRepoSelection (repo) {
    return repo.name || repo.text || repo.type_id;
  }
$("#partSlct").on('change',function(e){
    var partunitx='';
    var selectedText = $("#select2-partSlct-container" ).text();
    var selectedType = $(this).select2('data')[0].type_id
    var selectedPartID = $( this ).val();
    var smallUnit = 1;

    partunitx +=`<option selected disabled value="">Select Unit</option>`;
    if(($(this).select2('data').length > 0)){
        if($(this).select2('data')[0].getsmallunit.length > 0){
            smallUnit = ($(this).select2('data').length > 0) ? $(this).select2('data')[0].small_unit : 0;

            $(this).select2('data')[0].getsmallunit.forEach(unit => {
                    partunitx +=`<option value="${unit.unit.id}">${unit.unit.name}</option>`;
            });

        }else{
            partunitx +=`<option value="1">وحدة</option>`;
        }
    }else{

    }
    $('#newinvtbl').append(`<tr>
        <td>
            <input type="hidden" name="partId[]" id="inputpartId" class="form-control" value="${selectedPartID}">
            <input type="hidden" name="types[]" id="inputpartId" class="form-control" value="${selectedType}">
             <input type="hidden" name="smallUnit[]"  class="form-control" value="${smallUnit}">
            <label class="text-nowrap">${selectedText}</label>
            <input type="text" name="" readonly="" class="form-control d-none" value="${selectedText}" id="${selectedPartID}">
        </td>
        <td>
            <select class="form-select partSource" name="partSource[]" id="" required>
                ${partSourceOption}
            </select>

        </td>
        <td>
            <select class="form-select partStatus" name="partStatus[]" id="" required>
            ${partStatusOption}
            </select>
        </td>
        <td>
            <select class="form-select partQualty" name="partQualty[]" id="" required>
            ${partQualtyOption}
            </select>
        </td>
        <td>
            <select class="form-select partQualty text-left" name="unit[]" id="" required>
            ${partunitx}
            </select>
        </td>
        <td><input onkeyup="calc_table_price()" type="text" name="amount[]" class="form-control row_amount" value="0"  id="" required></td>
        <td><input type="text" name="price[]" onkeyup="calc_table_price()" class="form-control row_price" value="0" id="" required></td>
        <td><input type="text" name="tot[]" class="form-control row_tot" readonly value="0" id="" ></td>


        <td class="btn btn-ghost-danger" onclick='invTotal -=$(this).closest("tr").find("input.row_tot").val(); $("#invAllTotal").val(invTotal);$("#invTotLbl1").val(invTotal);$("#invTotLbl").text(invTotal);$(this).closest("tr").remove();' >Remove</td>
    </tr>`)
    var lastrow = $("#newinvtbl tr").last();
    if($("#global_sourceslct").val() > 0){
        lastrow.find('select.partSource').val($("#global_sourceslct").val())
    }
    if($("#global_statuslct").val() > 0){
        lastrow.find('select.partStatus').val($("#global_statuslct").val())
    }
    if($("#global_qualityslct").val() > 0){
        lastrow.find('select.partQualty').val($("#global_qualityslct").val())
    }

    if($("#global_storeslct").val() > 0){
        lastrow.find('select.Stores').val($("#global_storeslct").val()).trigger('change');
    }

    $(this).val(null);

})




 // Method to upload a valid excel file
 function upload() {
	var files = document.getElementById('file_upload').files;
	if(files.length==0){
	  alert("Please choose any file...");
	  return;
	}
	var filename = files[0].name;
	var extension = filename.substring(filename.lastIndexOf(".")).toUpperCase();
	if (extension == '.XLS' || extension == '.XLSX') {
		excelFileToJSON(files[0]);
	}else{
		alert("Please select a valid excel file.");
	}
  }

  //Method to read excel file and convert it into JSON
  function excelFileToJSON(file){
	  try {
		var reader = new FileReader();
		reader.readAsBinaryString(file);
		reader.onload = function(e) {

			var data = e.target.result;
			var workbook = XLSX.read(data, {
				type : 'binary'
			});
			var result = {};
			workbook.SheetNames.forEach(function(sheetName) {
				var roa = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[sheetName]);
				if (roa.length > 0) {
					result[sheetName] = roa;
                    displayJsonToHtmlTable(roa);
				}
			});
			//displaying the json result
			var resultEle=document.getElementById("json-result");
			resultEle.value=JSON.stringify(result, null, 4);
			// resultEle.style.display='block';
			}
		}catch(e){
		    console.error(e);
		}
  }

  function displayJsonToHtmlTable(jsonData){
    console.log(jsonData);
    var table=document.getElementById("newinvtbl");
    if(jsonData.length>0){
        var htmlData='';
        for(var i=0;i<jsonData.length;i++){
            var row=jsonData[i];

            // var $select = $($(this).data('target'));
            select2_search($('#partSlct'), row["partName"]);
            // let partName = row["partName"];
            // let $element = $('#partSlct')
            // let val = $element.find("option:contains('"+partName+"')").val()
            // $element.val(val).trigger('change.select2');
            // var xxa=$("#partSlct").find("option:contains("+row["partName"]+")").val()
            // $("#partSlct").val(xxa).trigger('change');



            htmlData+=`<tr><td>${row["partName"]}</td>
                            <td>${row["source"]}</td>
                            <td>${row["status"]}</td>
                            <td>${row["quality"]}</td>
                            <td>${row["amount"]}</td>
                            <td>${row["price"]}</td>
                            <td>${row["total"]}</td>
                            <td class="btn btn-ghost-danger" onclick="$(this).closest('tr').remove();" >Remove</td>
                        </tr>`;
        }
        // table.innerHTML=htmlData;
        // $(table).append(htmlData);
    }else{
        table.innerHTML='There is no data in Excel';
    }
}

function select2_search ($el, term) {
    $el.select2('open');

    // Get the search box within the dropdown or the selection
    // Dropdown = single, Selection = multiple
    var $search = $el.data('select2').dropdown.$search || $el.data('select2').selection.$search;
    // This is undocumented and may change in the future

    $search.val(term);
    $search.trigger('keyup').click();
    $search.trigger('change');
  }
var invTotal=0;
function calc_table_price(){

    var tableRow=$("#newinvtbl tbody tr");
    invTotal = 0;
    tableRow.each(function(index, tr) {
        var amount = $(tr).find("input.row_amount").val();
        var price = $(tr).find("input.row_price").val();
        amount = parseInt(amount);
        price = parseFloat(price);
        var total = amount*price;
        invTotal +=total;
        $(tr).find("input.row_tot").val(total)
     })

     $("#invTotLbl").text(invTotal);
     $("#invTotLbl1").val(invTotal);
     $("#invAllTotal").val(invTotal)

}
function calc_coast(){
   var transCoast= parseFloat($("#transCoast").val());
   var insuranceCoast= parseFloat($("#insuranceCoast").val());
   var customs= parseFloat($("#customs").val());
   var commition= parseFloat($("#commition").val());
   var otherCoast= parseFloat($("#otherCoast").val());
   var Tot=(transCoast?transCoast:0) +(insuranceCoast ? insuranceCoast:0)+(customs ? customs : 0)+(commition ? commition : 0)+(otherCoast ? otherCoast : 0);
   $('#InvCoastinglbl').text(Tot);
     $('#InvCoasting').val(Tot);

}

// $("#global_sourceslct").change(function (e) {
//     e.preventDefault();
//     var selectedSource = $(this).val();
//     var allpartSource = $("#newinvtbl tbody").find(".partSource");

//     for (let i = 0; i < allpartSource.length; i++) {
//         const element = allpartSource[i];
//         $(element).val(selectedSource).trigger('change');

//     }

// });

// $("#global_statuslct").change(function (e) {
//     e.preventDefault();
//     var selectedSource = $(this).val();
//     var allpartSource = $("#newinvtbl tbody").find(".partStatus");

//     for (let i = 0; i < allpartSource.length; i++) {
//         const element = allpartSource[i];
//         $(element).val(selectedSource).trigger('change');

//     }

// });

// $("#global_qualityslct").change(function (e) {
//     e.preventDefault();
//     var selectedSource = $(this).val();
//     var allpartSource = $("#newinvtbl tbody").find(".partQualty");

//     for (let i = 0; i < allpartSource.length; i++) {
//         const element = allpartSource[i];
//         $(element).val(selectedSource).trigger('change');

//     }

// });

// $("#global_storeslct").change(function (e) {
//     e.preventDefault();
//     var selectedStore = $(this).val();
//     var allpartStore = $("#newinvtbl tbody").find(".Stores");

//     for (let i = 0; i < allpartStore.length; i++) {
//         const element = allpartStore[i];
//         $(element).val(selectedStore).trigger('change');

//     }

// });

$("#global_sourceslct").change(function (e) {
    e.preventDefault();
    var selectedSource = $(this).val();
    var allpartSource = $("#newinvtbl tbody").find(".partSource");

    Swal.fire({
        text: "Are you sure you want to change all sources",
        icon: "warning",
        showCancelButton: true,
        buttonsStyling: false,
        confirmButtonText: "Yes, change!",
        cancelButtonText: "No, cancel",
        customClass: {
            confirmButton: "btn fw-bold btn-danger",
            cancelButton: "btn fw-bold btn-active-light-primary"
        }
    }).then(function (result) {
        if (result.value) {
            for (let i = 0; i < allpartSource.length; i++) {
                const element = allpartSource[i];
                $(element).val(selectedSource).trigger('change');

            }
            Swal.fire({

                text: "You have change " ,
                icon: "success",
                buttonsStyling: false,
                confirmButtonText: "Ok, got it!",
                customClass: {
                    confirmButton: "btn fw-bold btn-primary",
                }
            }).then(function () {
                // Remove current row

            });
        } else if (result.dismiss === 'cancel') {
            $("#global_sourceslct").val('0');
            Swal.fire({
                text:  " was not change.",
                icon: "error",
                buttonsStyling: false,
                confirmButtonText: "Ok, got it!",
                customClass: {
                    confirmButton: "btn fw-bold btn-primary",
                }
            });
        }
    });


});

$("#global_statuslct").change(function (e) {
    e.preventDefault();
    var selectedSource = $(this).val();
    var allpartSource = $("#newinvtbl tbody").find(".partStatus");
    Swal.fire({
        text: "Are you sure you want to change all sources",
        icon: "warning",
        showCancelButton: true,
        buttonsStyling: false,
        confirmButtonText: "Yes, change!",
        cancelButtonText: "No, cancel",
        customClass: {
            confirmButton: "btn fw-bold btn-danger",
            cancelButton: "btn fw-bold btn-active-light-primary"
        }
    }).then(function (result) {
        if (result.value) {
            for (let i = 0; i < allpartSource.length; i++) {
                const element = allpartSource[i];
                $(element).val(selectedSource).trigger('change');

            }

            Swal.fire({
                text: "You have change them ",
                icon: "success",
                buttonsStyling: false,
                confirmButtonText: "Ok, got it!",
                customClass: {
                    confirmButton: "btn fw-bold btn-primary",
                }
            }).then(function () {
                // Remove current row

            });
        } else if (result.dismiss === 'cancel') {
            $("#global_statuslct").val('0');
            Swal.fire({
                text:  " was not change.",
                icon: "error",
                buttonsStyling: false,
                confirmButtonText: "Ok, got it!",
                customClass: {
                    confirmButton: "btn fw-bold btn-primary",
                }
            });
        }
    });

});

$("#global_qualityslct").change(function (e) {
    e.preventDefault();
    var selectedSource = $(this).val();
    var allpartSource = $("#newinvtbl tbody").find(".partQualty");
    Swal.fire({
        text: "Are you sure you want to change all sources",
        icon: "warning",
        showCancelButton: true,
        buttonsStyling: false,
        confirmButtonText: "Yes, change!",
        cancelButtonText: "No, cancel",
        customClass: {
            confirmButton: "btn fw-bold btn-danger",
            cancelButton: "btn fw-bold btn-active-light-primary"
        }
    }).then(function (result) {
        if (result.value) {
            for (let i = 0; i < allpartSource.length; i++) {
                const element = allpartSource[i];
                $(element).val(selectedSource).trigger('change');

            }

            Swal.fire({
                text: "You have change them ",
                icon: "success",
                buttonsStyling: false,
                confirmButtonText: "Ok, got it!",
                customClass: {
                    confirmButton: "btn fw-bold btn-primary",
                }
            }).then(function () {
                // Remove current row

            });
        } else if (result.dismiss === 'cancel') {
            $("#global_qualityslct").val('0');
            Swal.fire({
                text:  " was not change.",
                icon: "error",
                buttonsStyling: false,
                confirmButtonText: "Ok, got it!",
                customClass: {
                    confirmButton: "btn fw-bold btn-primary",
                }
            });
        }
    });



});

$("#global_storeslct").change(function (e) {
    e.preventDefault();
    var selectedStore = $(this).val();
    var allpartStore = $("#newinvtbl tbody").find(".Stores");
    Swal.fire({
        text: "Are you sure you want to change all sources",
        icon: "warning",
        showCancelButton: true,
        buttonsStyling: false,
        confirmButtonText: "Yes, change!",
        cancelButtonText: "No, cancel",
        customClass: {
            confirmButton: "btn fw-bold btn-danger",
            cancelButton: "btn fw-bold btn-active-light-primary"
        }
         }).then(function (result) {
         if (result.value) {
            for (let i = 0; i < allpartStore.length; i++) {
                const element = allpartStore[i];
                $(element).val(selectedStore).trigger('change');

            }

            Swal.fire({
                text: "You have change them ",
                icon: "success",
                buttonsStyling: false,
                confirmButtonText: "Ok, got it!",
                customClass: {
                    confirmButton: "btn fw-bold btn-primary",
                }
            }).then(function () {
                // Remove current row

            });
        } else if (result.dismiss === 'cancel') {
            $("#global_storeslct").val('0');
            Swal.fire({
                text:  " was not change.",
                icon: "error",
                buttonsStyling: false,
                confirmButtonText: "Ok, got it!",
                customClass: {
                    confirmButton: "btn fw-bold btn-primary",
                }
            });
        }
    });


});

$("#invTax").keyup(function (e) {
    var tax = $(this).val();
    var invTot = $("#invTotLbl1").val();
    var Tot = $("#invAllTotal").val();
    var invtotaltax = (parseFloat(invTot) * parseFloat(tax) / 100 ).toFixed(2);

    var kasmTax = $('input[type=radio][name=taxkasmInvolved]:checked').attr('data-val');
    var kasminvtotaltax = (parseFloat(invTot) * parseFloat(kasmTax) / 100 ).toFixed(2);

    $("#invAllTotal").val((parseFloat(invtotaltax)+parseFloat(invTot)+parseFloat(kasminvtotaltax)).toFixed(1));
    // $("#invTotLbl").text(invtotaltax);

});

$('input[type=radio][name=taxkasmInvolved]').change(function(e) {
    // var tax = $(this).attr('data-val');
    // var invTot = $("#invTotLbl").text();
    // var Tot = $("#invAllTotal").val();
    // var invtotaltax = (parseFloat(invTot) * parseFloat(tax) / 100 ).toFixed(2);
    // $("#invAllTotal").val((parseFloat(invtotaltax)+parseFloat(Tot)).toFixed(1));
    // $("#invTotLbl").text(invtotaltax);

     var tax = $('#invTax').val();
    var invTot = $("#invTotLbl1").val();
    var Tot = $("#invAllTotal").val();
    var invtotaltax = (parseFloat(invTot) * parseFloat(tax) / 100 ).toFixed(2);
    var kasmTax2 = $('input[type=radio][name=taxInvolved]:checked').attr('data-val');
    var kasminvtotaltax2 = (parseFloat(invTot) * parseFloat(kasmTax2) / 100 ).toFixed(2);
    var kasmTax = $(this).attr('data-val');
    var kasminvtotaltax = (parseFloat(invTot) * parseFloat(kasmTax) / 100 ).toFixed(2);

    $("#invAllTotal").val((parseFloat(invtotaltax)+parseFloat(invTot)+parseFloat(kasminvtotaltax)+parseFloat(kasminvtotaltax2)).toFixed(1));

});

$('input[type=radio][name=taxInvolved]').change(function(e) {
    // var tax = $(this).attr('data-val');
    // var invTot = $("#invTotLbl").text();
    // var Tot = $("#invAllTotal").val();
    // var invtotaltax = (parseFloat(invTot) * parseFloat(tax) / 100 ).toFixed(2);
    // $("#invAllTotal").val((parseFloat(invtotaltax)+parseFloat(Tot)).toFixed(1));
    // $("#invTotLbl").text(invtotaltax);

     var tax = $('#invTax').val();
    var invTot = $("#invTotLbl1").val();
    var Tot = $("#invAllTotal").val();
    var invtotaltax = (parseFloat(invTot) * parseFloat(tax) / 100 ).toFixed(2);
    var kasmTax2 = $('input[type=radio][name=taxkasmInvolved]:checked').attr('data-val');
    var kasminvtotaltax2 = (parseFloat(invTot) * parseFloat(kasmTax2) / 100 ).toFixed(2);

    var kasmTax = $(this).attr('data-val');
    var kasminvtotaltax = (parseFloat(invTot) * parseFloat(kasmTax) / 100 ).toFixed(2);

    $("#invAllTotal").val((parseFloat(invtotaltax)+parseFloat(invTot)+parseFloat(kasminvtotaltax)+parseFloat(kasminvtotaltax2)).toFixed(1));

});

// madyonea

$(document).on('click',".sendStore",function () {
    var inv_id = $(this).data('id');
    location.href ="storeManageItems/"+inv_id;
})

function changeStore(el){
     var storeid = $(el).val() ;


    $.ajax({
        type: "get",
        url: "GetSections/"+storeid,
        success: function (response) {
            if(response.length > 0){
                storeSectionOption +=`<option selected disabled value="">Select Section</option>`;
                $(el).closest('tr').find('.Sections').empty();
                response.forEach(element => {
                    $(el).closest('tr').find('.Sections').append(`<option value="${element.id}">${element.name}</option>`);
                    storeSectionOption+=`<option value="${element.id}">${element.name}</option>`;
                });
            }else{
                $(el).closest('tr').find('.Sections').empty();
                $(el).closest('tr').find('.Sections').append(`<option value="0">No Sections</option>`);
            }

        }
    });
    $(el).closest('tr').find('.Sections').select2({ dropdownParent: $('#staticBackdrop')});

}


function DeleteInv(invoiceId){
    let text = "هل تريد الحذف ؟";
    if (confirm(text) == true) {
        location.href="deleteInv/"+invoiceId;
    } else {

    }
}

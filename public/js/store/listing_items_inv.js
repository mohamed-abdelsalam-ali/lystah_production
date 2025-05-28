
var itemInvdt='';
$(document).ready(function () {
console.log(itemes);

    itemInvdt = $("#itemInvdt").DataTable({
    dom: "Bfrtip",
    processing: true,
    pageLength: 10,
    deferRender: true,
    responsive: true,
    destroy: true,


    buttons: [

        "print",
    ],
});
});

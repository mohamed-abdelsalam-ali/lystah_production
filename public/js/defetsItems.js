var table;
$(document).ready(function () {

    pdfMake.fonts = {
        Roboto: {
            normal: 'Roboto-Italic.ttf',
            bold: 'Roboto-Italic.ttf',

        }
    }
    table = $('#defectsItemsDT').DataTable({
        processing: true,
        serverSide: false,
        dom: 'Bfrtip',
        buttons: {
            buttons: [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'print' ,
                {
                    extend: 'pdfHtml5',
                    orientation: 'landscape',
                    exportOptions: {
                    },customize : function(doc){
                        doc.pageMargins = [10,10,10,10];
                        doc.defaultStyle.fontSize = 7;
                        doc.defaultStyle.font= 'Roboto';
                    }
                },
                {
                    text: 'Reset',
                    action: function ( e, dt, node, config ) {
                        table.columns().search('').draw();
                    }
                }

            ]
        },
        language: {
            searchPlaceholder: "بحث"
        },
        pageLength: 50,
        deferRender: true,
        destroy: true,
        ajax: '/defectsItemsData',
        async: false,
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex' , "orderable" : false},
            {data: 'ArabicName', name: 'Arabic Name', "orderable" : false},
            {data: 'EnglishName', name: 'English Name', "orderable" : false},
            {data: 'PartNumber', name: 'Part Number', "orderable" : false},
            {data: 'LimitOrder', name: 'Limit Order', "orderable" : false},
            {data: 'RemainingAmount', name: 'Remaining Amount', "orderable" : false},
            {data: 'Source', name: 'Source', "orderable" : false},
            {data: 'Quality', name: 'Quality', "orderable" : false},
            {data: 'Status', name: 'Status', "orderable" : false},
        ],

    });


});

$( document ).ajaxComplete(function() {
    table.columns().flatten().each( function ( colIdx ) {
        // Create the select list and search operation
        if (colIdx == 1 || colIdx == 2 ) {
            var select
                select = $('<select /><option value="" >Select</option>')
                .appendTo(
                    table.column(colIdx).header()
                )
                .on( 'change', function () {
                    table
                        .column( colIdx )
                        .search( $(this).val() )
                        .draw();
                } );



            // Get the search data for the first column and add to the select list
            table
                .column( colIdx )
                .cache( 'search' )
                .sort()
                .unique()
                .each( function ( d ) {
                    select.append( $('<option value="'+d+'">'+d+'</option>') );
                } );

        }

    } );
    $("#defectsItemsDT thead select").select2()
});

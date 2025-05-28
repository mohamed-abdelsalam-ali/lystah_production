var table;
$(document).ready(function () {

    pdfMake.fonts = {
        Roboto: {
            normal: 'Roboto-Italic.ttf',
            bold: 'Roboto-Italic.ttf',

        }
    }
    table = $('#partNumbersDT').DataTable({
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
                        // columns: ':visible'
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
        ajax: "partNumberData",
        async: false,
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex' , "orderable" : false},
            {data: 'PartNumber', name: 'Part Number', "orderable" : false},
            {data: 'ArabicName', name: 'Arabic Name', "orderable" : false},
            {data: 'EnglishName', name: 'Englis hName', "orderable" : false},
            {data: 'supplier', name: 'Supplier', "orderable" : false},
            {data: 'allPart', name: 'allPart', "orderable" : false},

        ],

        "initComplete":function( settings, json){
            table.columns().flatten().each( function ( colIdx ) {
                // Create the select list and search operation
                if (colIdx != 0 && colIdx != 5 ) {

                    var select = $('<select /><option value="" >Select</option>')
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
            $("#partNumbersDT thead select").select2()
        }

    });


});

$( document ).ajaxComplete(function() {

});

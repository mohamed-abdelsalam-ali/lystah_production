<html lang="en">

<head>
    <meta charset="utf-8">
    <title>BarCode Print</title>
    <style>
        body {
            width: 8.5in;
            margin: 0.5in .1875in;
            background: #e8e8e8;
        }

        /* Avery 5960 labels */
        .label {
            width: 2.25in;
            height: .75in;
            padding: .125in 0.1875in;
            margin-right: .12in;
            /* the gutter */
            float: left;
            text-align: left;
            overflow: hidden;
            background: #fff;
            outline: 1px dotted #999;
        }

        .page-break {
            clear: left;
            display: block;
            page-break-after: always;
        }
        svg{
            width: 100% ;
            height: 60px ;
        }
    </style>
</head>

<body>
    {{-- <label for="">sheet per page</label>
    <input type="number" name="" id=""> --}}
    @for ($i =0 ; $i <240 ; $i++)
        <div class="label" style="text-align: center">
            <svg class="barcode">

            </svg>
            {{-- <br> --}}
            <span class="barcodeTxt" style="display: none">{{ $barcodeTxt }}</span><br>
            {{ $name }}
        </div>
    @endfor

       <div class="page-break"></div>

       <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
        <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jsbarcode/3.11.5/JsBarcode.all.min.js"></script>

        <script>
            $(document).ready(function () {
                $('.label').each(function(){
                    JsBarcode($(this).find('.barcode')[0],$(this).find('.barcodeTxt').text());
                })
            });
        </script>

</body>


</html>

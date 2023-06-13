<!DOCTYPE html>
<html>
<head>
    <title>Generate Pdf</title>
    <style>
        @page {
            header: page-header;
            footer: page-footer;
        }

        body {
            font-family: "Calibri", sans-serif;
        }

        @page {
            size: 21cm 29.7cm;
            /* change the margins as you want them to be. */
        }

        th,td {font-size:14px;border:1px solid;padding:5px; }
        table{ width:100%;border-collapse:collapse;border:0px;}
                
        #account-details tr td, #account-details th{
            border:0px;
            padding:3px;
        }

        #header{
            box-sizing:border-box;
            height:280px;
            margin-top:30px;
        }

        #left{
            box-sizing:border-box;
            float:left;
            width:48%;
        }

        #right{
            position:relative;
            width:350px;
            float:right;
        }
    </style>
</head>
<body>
    <h2 align="center"> {{ ucfirst($slug) }} </h2>
    <table width="100%">
        <thead style="background-color: #8ac6ff;">
            <tr>
                <th>No.</th>
                @for ($i = 0; $i < count($column_name); $i++)
                    <th> {{ $column_name[$i] }} </th>
                @endfor
            </tr>
        </thead>
        @foreach ($filter_data as $key => $row) 
            <tr>
                <td>{{ $key + 1 }}</td>
                @for($j = 0; $j < count($fields); $j++)
                    @php $value = (isset($row->{$fields[$j]}) && $row->{$fields[$j]} !== '') ? $row->{$fields[$j]} : 'N/A' ; @endphp
                    <td>{{ $value }}</td>
                @endfor
            </tr>
        @endforeach
    </table>
    <htmlpagefooter name="page-footer">
        <table style='margin-top:10px'>
            <tr style='border:unset;'>
                <th style='border:unset;text-align:left;font-weight: 300;font-size:12px;'><p>{{ env('APP_NAME') }}</p></th>
                <th style='border:unset;text-align:right;font-weight: 300;font-size:12px;'><p>Page: {PAGENO}/{nb}</p></th>
            </tr>
        </table>
    </htmlpagefooter>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Document</title>
  <style>
        body {
            font-family: sans-serif;
            width: 99%;
            margin: 2px auto;
            font-size: 12px;
            padding: .2%;
            padding-bottom: 0px;
            padding-left: 0px;
        }
        header {
            width: 100%;
        }

        .table_export {
            width: 100%;
            margin: 0 auto;
            padding: 5px;
            border-bottom: 0.5px solid;
        }

        .table_export th{
            font-weight: normal;
            text-align: left;
            padding-left: 10px;
            width: 20%;
        }

        .table_export tbody td {
            border: 1px solid;
        }

        table.table_export {
            border-collapse:collapse;
        }

         .avoid-pagebreak {
            page-break-inside: avoid;
        }

        .text-right {
            float: right;
        }

        .text-left {
            float: left;
        }

    </style>
</head>
<body>
  <header>
    <table class="table_export">
      <thead>
        <tr>
          <th>
            <b>Style:</b> {{ $sample->name }}
          </th>
          <th>
            <b>Style Code:</b> {{ $sample->styleCode }}
          </th>
          <th>
            <b>Customer:</b> {{ $sample->customerName }}
          </th>
          <th>
            <img
              src="https://sourceeasycdn.s3.amazonaws.com/www.v2/imgs/selogo.png"
              height="38"
            >
          </th>
        </tr>
      </thead>
    </table>

  </header>

</body>
</html>

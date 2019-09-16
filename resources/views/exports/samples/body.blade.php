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

        .table_export {
            width: 100%;
            margin: 0 auto;
            margin-bottom: 10px;
            border-collapse: collapse;
        }

        .table_export th {
          line-height: 30px;
          background: #0090C6;
          color: #ffffff;
        }

        .table_export td {
          border: solid thin #bfbfbf;
        }

        .inner-table td {
          border: none;
        }

        .avoid-pagebreak {
            page-break-inside: avoid;
        }

        .page-breaker {
          display: block;
          page-break-after: always;
        }

        .text-right {
            float: right;
        }

        .text-left {
            float: left;
        }

        thead { display: table-header-group; }
        tfoot { display: table-row-group; }
        tr { page-break-inside: avoid; }

    </style>
</head>
<body>
  <header>
    @include ('exports.samples.partials._sample_header')
  </header>

  <header>
    @include ('exports.samples.partials._pom')
  </header>

  <header>
    @include ('exports.samples.partials._action_forward')
  </header>

  <header>
    @include ('exports.samples.partials._criterias')
  </header>
</body>
</html>

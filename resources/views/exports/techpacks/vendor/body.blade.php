<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Document</title>
  <style>
        body {
            font-family: sans-serif;
            width: 100%;
            font-size: 12px;
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
          background: #222460;
          color: #ffffff;
        }

        .table_export td {
          border: .1px solid #bfbfbf;
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
    @include ('exports.techpacks.vendor.partials._cover')

    @if ($selectedFields['billOfMaterials'] == true)
      @include ('exports.techpacks.vendor.partials._bom')
    @endif

    @if ($selectedFields['poms'] == true)
      @include ('exports.techpacks.vendor.partials._pom')
    @endif

    @if ($selectedFields['gradedSpecSheet'] == true)
      @include ('exports.techpacks.vendor.partials._graded_specs')
      @include ('exports.techpacks.vendor.partials._graded_specs_corrections')
    @endif

    {{-- @if ($selectedFields['cutTicket'] == true)
      @include ('exports.techpacks.vendor.partials._cut_ticket')
    @endif --}}
    @if ( $selectedFields['documents']['photo'] == true ||
      $selectedFields['documents']['construction'] == true ||
      $selectedFields['documents']['cad'] == true ||
      $selectedFields['documents']['howToMeasure'] == true ||
      $selectedFields['documents']['others'] == true
    )
      @include ('exports.techpacks.vendor.partials._documents')
    @endif

  </header>

</body>
</html>

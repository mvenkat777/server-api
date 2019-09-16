<style type="text/css">
  .table_spec {
      width: 100%;
      margin: 0 auto;
      margin-bottom: 10px;
      border-collapse: collapse;
  }

  .table_spec th {
    line-height: 30px;
    background: #222460;
    color: #ffffff;
  }

  .table_spec td {
    line-height: 20px;
    border-bottom: solid thin #bfbfbf;
  }

</style>

@if (!empty($techpack['graded_specs']->header) && isset($techpack['graded_specs']->header[0]))
  <div class="page-breaker"></div>

  <table class="table_spec avoid-pagebreak">
    <thead>
      <tr>
        <th colspan=42>Graded Specs (Measurements)</th>
      </tr>
      <tr>
        @for ($i = 0; $i < 3 ; $i++)
          <td style="text-align: left;">
            <b>{{ $techpack['graded_specs']->header[$i] }}</b>
          </td>
        @endfor
        @for ($i = 3; $i < count($techpack['graded_specs']->header) ; $i++)
          <td style="text-align: center;">
            <b>{{ $techpack['graded_specs']->header[$i] }}</b>
          </td>
        @endfor
      </tr>
    </thead>
    <tbody>
      @foreach ($techpack['graded_specs']->values as $values)
      <tr>
        @for ($i = 0; $i < 3 ; $i++)
          <td style="text-align: left;">{{ $values[$i] ? $values[$i] : '-' }}</td>
        @endfor
        @for ($i = 3; $i < count($values) ; $i++)
          <td style="text-align: center;">{{ $values[$i] ? $values[$i] : '-' }}</td>
        @endfor
      </tr>
      @endforeach
    </tbody>
  </table>
@endif

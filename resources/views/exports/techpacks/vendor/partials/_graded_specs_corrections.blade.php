@if (!empty($techpack['graded_specs']->header) && isset($techpack['graded_specs']->header[0]))
  <table class="table_spec">
  </table>

  <table class="table_spec avoid-pagebreak">
    <thead>
      <tr>
        <th colspan=42>Graded Specs Corrections (Variations)</th>
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
      @foreach ($techpack['graded_specs_corrections']->values as $values)
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

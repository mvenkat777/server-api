<style type="text/css">
  .table_pom {
      width: 100%;
      margin: 0 auto;
      margin-bottom: 10px;
      border-collapse: collapse;
  }

  .table_pom th {
    line-height: 30px;
    background: #222460;
    color: #ffffff;
  }

  .table_pom td {
    line-height: 20px;
    border-bottom: solid thin #bfbfbf;
  }

</style>

@if (!empty($techpack['poms']->values))
  <div class="page-breaker"></div>
  <div class="avoid-pagebreak">

  <table class="table_pom avoid-pagebreak">
    <thead>
      <tr>
        <th colspan=42>Points of Measurement</th>
	  </tr>
	  <tr>
    @for ($i = 0; $i < 3 ; $i++)
      <td style="text-align: left;">
        <b>{{ $techpack['poms']->header[$i] }}</b>
      </td>
    @endfor
    @for ($i = 3; $i < count($techpack['poms']->header) ; $i++)
      <td style="text-align: center;">
        <b>{{ $techpack['poms']->header[$i] }}</b>
      </td>
    @endfor
	  </tr>
    </thead>
    <tbody>
		<?php $row = 0; ?>
        @foreach ($techpack['poms']->values as $values)
        <tr>
          @for ($i = 0; $i < 3 ; $i++)
            <td style="text-align: left;">{{ $values[$i] ? $values[$i] : '-' }}</td>
          @endfor
          @for ($i = 3; $i < count($values) ; $i++)
            <td style="text-align: center;">{{ $values[$i] ? $values[$i] : '-' }}</td>
          @endfor
		  @if ($row == 0)
			  <td rowspan=42 style="text-align: center;">
				@if (!empty ($techpack['image']))
				  <img src="{{ $techpack['image']->selfLink }}" width="100">
				@else
				  <img alt="NA">
				@endif
			  </td>
		  @endif
        </tr>
		<?php $row++; ?>
        @endforeach
    </tbody>
  </table>
</div>
@endif

<style type="text/css">
  .table_pom {
      width: 100%;
      margin: 0 auto;
      margin-bottom: 10px;
      border-collapse: collapse;
  }

  .table_pom th {
    line-height: 30px;
    background: #0090C6;
    color: #ffffff;
  }

  .table_pom td {
    line-height: 20px;
    border-bottom: solid thin #bfbfbf;
  }
  
  .highlight {
    background: yellow;
  }

</style>

@if (isset($sample->pom->pom) && !empty($sample->pom->pom))
  <div class="page-breaker"></div>
  <div class="avoid-pagebreak">

  <table class="table_pom avoid-pagebreak">
    <thead>
      <tr>
        <th colspan=42>Points of Measurement</th>
	  </tr>
	  <tr>
		  <td style="text-align: left;"> <b>POM Code</b>
		  </td>
		  <td style="text-align: left;"> <b>Description</b>
		  </td>
		  <td style="text-align: center;"> <b>Tol</b>
		  </td>
		  <td style="text-align: center;"> <b>Requested</b>
		  </td>
		  <td style="text-align: center;"> <b>Actual</b>
		  </td>
		  <td style="text-align: center;"> <b>Deviance</b>
		  </td>
		  <td style="text-align: center;"> <b>Comment</b>
		  </td>
		  <td style="text-align: center;"> <b>Revisions</b>
		  </td>
	  </tr>
    </thead>
    <tbody>
        @foreach ($sample->pom->pom as $pom)
        <tr>
            <td style="text-align: left;">{{ $pom->pomCode ? $pom->pomCode : '-' }}</td>
            <td style="text-align: left;">{{ $pom->description ? $pom->description : '-' }}</td>
            <td style="text-align: center;">{{ $pom->tol ? $pom->tol : '-' }}</td>
            <td style="text-align: center;">{{ $pom->requested ? $pom->requested : '-' }}</td>
            <td style="text-align: center;">{{ $pom->actual ? $pom->actual : '-' }}</td>
            <td style="text-align: center;" class={{ $pom->isHighlighted ? 'highlight' : 'no-highlight' }}>
                {{ $pom->deviance ? $pom->deviance : '-' }}
            </td>
            <td style="text-align: center;">{{ $pom->comment ? $pom->comment : '-' }}</td>
            <td style="text-align: center;">{{ $pom->revisions ? $pom->revisions : '-' }}</td>
        </tr>
        @endforeach
    </tbody>
  </table>
</div>
@endif

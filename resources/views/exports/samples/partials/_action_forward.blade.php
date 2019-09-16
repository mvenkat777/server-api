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

</style>

@if (isset($sample->action_forward) && !empty($sample->action_forward))
  <div class="avoid-pagebreak">

  <table class="table_pom avoid-pagebreak">
    <thead>
      <tr>
        <th colspan=42>Action Forward</th>
	  </tr>
    </thead>
    <tbody>
	  <tr>
		  <td> 
            {!! $sample->action_forward !!}
		  </td>
	  </tr>
    </tbody>
  </table>
</div>
@endif

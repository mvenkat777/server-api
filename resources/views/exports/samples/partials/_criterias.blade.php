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

  .doc_image {
    max-height: 500px;
	  max-width: 500px;
	  width: auto;
     height: auto;
  }
  
  .no-border {
    border: none !important;   
  }
  thead { display: table-header-group; }
  tfoot { display: table-row-group; }
  tr { page-break-inside: avoid; }
</style>
@if (isset($sample->criterias))
    <div class="page-breaker"></div>

    @foreach ($sample->criterias as $criteria)
        <table class="table_pom">
            <thead style="display:table-header-group;">
              <th>
                {{ strtoupper($criteria['criteria']) . ' (Description)' }}
              </th>
            </thead>
            <tbody>
              <tr style="page-break-inside: avoid;">
               <td>
                {!! $criteria['description'] !!}
               </td>
              </tr>
           </tbody>
        <table>
        <div class="page-breaker"></div>
        <table class="table_pom">
            <thead>
                <th>
                    {{ strtoupper($criteria['criteria']) . ' (Images)' }}
                </th>
            </thead>
            <tbody>
                <td class="no-border">
                  <table class="inner-table" style="width:100%">
                    @foreach ($criteria['attachments'] as $attachment)
                      <tr>
                          <td class="avoid-pagebreak no-border" style="text-align: center; width: 100%; padding: 2%; padding-top: 5%;">
                            <img class="doc_image" src="{{ $attachment['file']->selfLink }}">
                          </td>
                      </tr>
                    @endforeach
                    </table>
                </td>
              </tr>
            </tbody>
        </table>
{{--
    <div class="page-breaker"></div>
        <table class="table_pom">
            <thead style="display:table-row-group;">
              <th>
                {{ strtoupper($criteria['criteria']) . ' (Comments)' }}
              </th>
            </thead>
            <tbody>
              <tr style="page-break-inside: avoid;">
               <td>
                {!! $criteria['note'] !!}
               </td>
              </tr>
              <tr>
           </tbody>
        <table>
--}}
    @endforeach
@endif

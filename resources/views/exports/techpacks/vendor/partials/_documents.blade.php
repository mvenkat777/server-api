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
    border-bottom: none;
  }

  .doc_image {
    margin: auto;
    display: block;
    max-width:960px;
    max-height:520px;
    width: auto;
    height: auto;
  }
</style>

@if (!empty($techpack['sketches']))
  <div class="page-breaker"></div>
  <table class="table_pom">
    <thead>
      <tr>
        <th>Documents</th>
      </tr>
    </thead>
  </table>

  @foreach ($techpack['sketches'] as $sketch)

    @if(
      ($sketch->label == 'Photo' && $selectedFields['documents']['photo'] == true) ||
      ($sketch->label == 'Construction' && $selectedFields['documents']['construction'] == true) ||
      ($sketch->label == 'CAD' && $selectedFields['documents']['cad'] == true) ||
      ($sketch->label == 'How to measure' && $selectedFields['documents']['howToMeasure'] == true) ||
      ($sketch->label == 'Others' && $selectedFields['documents']['others'] == true)
    )
      @if (count($sketch->images) > 0)
        <table class="table_pom">
          <thead>
            <th>
              {{ $sketch->label }}
            </th>
          </thead>
          <tbody>
            <tr>
              <td>
                <table class="inner-table" style="width:100%">
                  @for ($i = 0, $images = array_slice((array)$sketch->images, 0, 1); $i
                  < count((array)$sketch->
                    images); $i += 1, $images = array_slice((array)$sketch->images, $i, 1))
                    <tr>
                      @foreach ($images as $image)
                        @if (is_object($image))
                        <td class="avoid-pagebreak" style="text-align: center; width: 100%; padding: 2%; padding-top: 5%;">
                          <img class="doc_image" src="{{ $image->selfLink }}">
                        </td>
                        @endif
                      @endforeach
                    </tr>
                    @endfor
                  </table>
              </td>
            </tr>
          </tbody>
        </table>
      @endif
    @endif
    <div class="page-breaker"></div>
  @endforeach
@endif

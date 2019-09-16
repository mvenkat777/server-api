
<table class="table_export avoid-pagebreak">
  <tbody>
    <tr>
      <td class="label" colspan=42> <b>{{ $bom->label }}</b>
      </td>
    </tr>
    <tr>
      <td style="width: 25%">Details</td>
      <td style="width: 15%">Production</td>
      <td style="width: 50%">Colors</td>
      <td style="width: 10%">Image</td>
    </tr>
    @foreach ($bom->rows as $row)
    <tr>
      <td>
        <table class="inner-table">
          <tr>
            <td><b>Class:</b> {{ $row->classification or '' }}</td>
          </tr>
          <tr>
            <td><b>Description:</b> {{ $row->description or '' }}</td>
          </tr>
          <tr>
            <td><b>Content:</b> {{ $row->content or '' }}</td>
          </tr>
          <tr>
            <td><b>Notes:</b> {{ $row->notes or '' }}</td>
          </tr>
          <tr>
            <td><b>Placement:</b> {{ $row->placement or '' }}</td>
          </tr>
        </table>
      </td>

      <td>
        <table class="inner-table">
          <tr>
            <td><b>Supplier:</b> {{ is_object($row->supplier) ? $row->supplier->name : $row->supplier }}</td>
          </tr>
          <tr>
            <td><b>Artwork code:</b> {{ $row->code or '' }}</td>
          </tr>
        </table>
      </td>

      <td>
        @include('exports.techpacks.vendor.partials._colorways')
      </td>

        <td style="text-align: center;">
          @if (!empty ($row->upload))
            <img
			  class="bom_image"
              src="{{ $row->upload->selfLink }}"
              alt="NA"
            >
          @else
            <img alt="NA">
          @endif
        </td>

      </tr>
      @endforeach
    </tbody>
  </table>

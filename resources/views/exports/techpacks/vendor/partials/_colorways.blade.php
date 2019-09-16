
@if (isset($row->colorway['colorway']))
<table class="inner-table">
  @for ($i = 0, $colors = array_slice((array)$row->colorway['colorway'], 0, 5); $i
  < count((array)$row->
    colorway['colorway']); $i += 5, $colors = array_slice((array)$row->colorway['colorway'], $i, 5))
    <tr>
      @foreach ($colors as $color)
        @if (is_object($color))
          @if (property_exists($color, 'color_name'))
            <td style="text-align: center; border: 1px dashed #e5e5e5; width: 19%;">
              <span class="colorbox-top-text">{{ $color->color_name or ''}}</span>
              <figure class="colorbox" style="background-color:{{ $color->hex_code or '#ffffff' }};"></figure>
              <span class="colorbox-bottom-text">{{ $color->tpx_code or '' }}</span>
            </td>
          @elseif (property_exists($color, 'color') && is_object($color->color) && property_exists($color->color, 'color_name'))
            <td style="text-align: center; border: 1px dashed #e5e5e5; width: 19%;">
              <span class="colorbox-top-text">{{ $color->color->color_name or ''}}</span>
              <figure class="colorbox" style="background-color:{{ $color->color->hex_code or '#ffffff' }};"></figure>
              <span class="colorbox-bottom-text">{{ $color->color->tpx_code or '' }}</span>
            </td>
          @elseif (property_exists($color, 'color'))
            <td style="text-align: center; border: 1px dashed #e5e5e5; width: 19%;">
              <span class="colorbox-top-text">{{ $color->color }}</span>
            </td>
          @endif
        @else
          <td style="text-align: center; border: 1px dashed #e5e5e5; width: 19%;">
            <span class="colorbox-top-text">{{ $color or '' }}</span>
          </td>
        @endif
      @endforeach
    </tr>
    @endfor
  </table>
  @endif

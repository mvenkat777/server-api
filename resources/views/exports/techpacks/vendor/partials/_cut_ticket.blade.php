<style type="text/css">
  .colorbox{
    height: 15px;
    width: 15px;

  }

  .colorbox-top-text{
    font-size: 10px;
    white-space: normal;
  }

  .colorbox-bottom-text{
    font-size: 10px;
  }

  .left-aligned{
    margin-left: 0;
    margin-right: auto;
  }

  .cut_image {
  max-height: 150px;
  max-width: 200px;
  width: auto;
    height: auto;
  }

  .cut_mainimage {
  max-height: 400px;
  max-width: 400px;
  width: auto;
    height: auto;
  }

</style>

<div class="page-breaker"></div>

<table class="table_export">
  <thead>
    <tr>
      <th>Cut Ticket</th>
    </tr>
  </thead>
</table>

    <table class="table_export avoid-pagebreak">
    <tbody>
      <tr>
        <td colspan=42 style="text-align: center;">
          @if (!empty(json_decode($techpack['cut_ticket_note']['image'])) &&
                is_array(json_decode($techpack['cut_ticket_note']['image']))
          )
            <img class="cut_mainimage"
              src="{{ json_decode($techpack['cut_ticket_note']['image'])[0]->selfLink }}"
              alt=""
            >
          @else
            <img alt="">
          @endif
        </td>

      </tr>
      <tr>
        <td style="width: 50%">Details</td>
        <td style="width: 50%">Piece Image</td>

      </tr>
      @foreach ($techpack['cut_tickets'] as $cutTicket)
      <tr>
        <td>
          <table class="inner-table">
            <tr>
              <td><b>Piece Name: </b>{{ $cutTicket['name'] }}</td>
            </tr>

            <tr>
              <td><b>Cut Amount: </b>{{ $cutTicket['amount'] }}</td>
            </tr>

            <tr>
              <td><b>Fabric: </b>{{ $cutTicket['fabric'] }}</td>
            </tr>

            <tr>
              <td><b>Non-Flip: </b>{{ $cutTicket['non_flip'] }}</td>
            </tr>

            <tr>
              <td><b>X: </b>{{ $cutTicket['x'] }}</td>
            </tr>

            <tr>
              <td><b>Y: </b>{{ $cutTicket['y'] }}</td>
            </tr>

            <tr>
              <td><b>XY: </b>{{ $cutTicket['xy'] }}</td>
            </tr>
          </table>
        </td>
        <td style="text-align: center;">
          @if (!empty ($cutTicket['image']) && is_object(json_decode($cutTicket['image'])))
            <img class="cut_image"
              src="{{ json_decode($cutTicket['image'])->selfLink }}"
              alt=""
            >
          @else
            <img alt="">
          @endif
        </td>

      </tr>
      @endforeach

      <tr>
        <td colspan=42>Cut Ticket Note</td>

      </tr>

      <tr>
        <td colspan=42> {!! $techpack['cut_ticket_note']['note'] !!}</td>
      </tr>

    </tbody>
  </table>




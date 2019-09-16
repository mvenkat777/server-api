<style>
    .techpack-flat {
        margin: auto;	
        display: block;
        max-width:960px;
        max-height:550px;
        width: auto;
        height: auto;
    }

    table.cover {
        width: 1024px;
        background: #f1f1f1;
        padding: 2px;
    }

    td.cover_image {
        text-align: center; 
        v-align: center; 
        padding: 10px; 
        height: 600px;       
    }
</style>

<table class="cover">
    <tr>
      <td>
        <b>Size Range:</b> {{ $techpack['poms']->sizeRange }}
      </td>
      <td>
        <b>Description:</b> {{ $techpack['meta']->product }}
      </td>
      <td>
        <b>Sample Size:</b> NA
      </td>
      <td>
        <b>Author:</b> {{ $techpack['owner']['display_name']}}
      </td>
    </tr>
    <tr>
      <td>
        <b>Category:</b> {{ $techpack['meta']->category }}
      </td>
      <td>
        <b>Season:</b> {{ $techpack['meta']->season }}
      </td>
      <td>
        <b>Delivery Date:</b> NA
      </td>
    </tr>
    <tr>
        @if (isset($techpack['image']->selfLink))
            <td colspan=4 class="cover_image">
                <img class="techpack-flat" src="{{ $techpack['image']->selfLink }}"></img>
            </td>
        @else
            <td colspan=4 class="cover_image">
            </td>
        @endif
        
    </tr>
</table>

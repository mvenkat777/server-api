<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Document</title>
  <style>
    .sample_image {
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
</head>
<body>
  <header>
    <table class="cover">
      <thead>
        <tr>
          <td>
            <b>Type:</b> {{ $sample->type }}
          </td>
          <td>
            <b>Fabric/Content:</b> {{ $sample->fabric_or_content }}
          </td>
          <td>
            <b>Weight/Quality:</b> {{ $sample->weight_or_quality }}
          </td>
          <td>
            <b>Author:</b> {{ $sample->author }}
          </td>
        </tr>
        <tr>
          <td>
            <b>Sent Date:</b> {{ $sample->sent_date }}
          </td>
          <td>
            <b>Received Date:</b> {{ $sample->received_date }}
          </td>
          <td>
            <b>Created Date:</b> {{ $sample->created_at }}
          </td>
          <td>
            <b>Updated Date:</b> {{ $sample->updated_at }}
          </td>
        </tr>
        <tr>
            @if (!empty($sample->image))
              <td colspan=4 class="cover_image">
                  <img class="sample_image" src="{{ $sample->image->selfLink }}"></img>
              </td>
            @else
              <td colspan=4 class="cover_image">
                <img alt=""></img>
              </td>
            @endif
          </td>
        </tr>
      </thead>
    </table>

  </header>

</body>
</html>

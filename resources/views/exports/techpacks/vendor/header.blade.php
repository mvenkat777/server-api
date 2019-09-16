<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Document</title>
  <style>
        body {
            font-family: sans-serif;
            width: 100%;
            font-size: 12px;
        }
        header {
            width: 100%;
            padding-top: 5px;
        }
        
        table {
            width: 100%;
            border-bottom: 1px solid;
        }
    </style>
</head>
<body>
  <header>
    <table>
      <thead>
        <tr>
          <td>
            <b>Name:</b> {{ $techpack['meta']->name }}
          </td>
          <td>
            <b>Status:</b> {{ $techpack['meta']->stage }}
          </td>
          <td rowspan=2 style="text-align: right; padding-right: 10px;">
            <img
              src="https://sourceeasycdn.s3.amazonaws.com/www.v2/imgs/selogo.png"
              height="30"
            >
          </td>
        </tr>
        <tr>
          <td>
            <b>Style Code:</b> {{ $techpack['meta']->styleCode }}
          </td>
          <td>
            <b>Customer:</b> {{ $techpack['meta']->customer->name }}
          </td>
        </tr>
      </thead>
    </table>

  </header>

</body>
</html>

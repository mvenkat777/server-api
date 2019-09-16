<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <script>
        function subst() {
          var vars={};
          var x=document.location.search.substring(1).split('&');
          for(var i in x) {var z=x[i].split('=',2);vars[z[0]] = unescape(z[1]);}
          var x=['frompage','topage','page','webpage','section','subsection','subsubsection'];
          for(var i in x) {
            var y = document.getElementsByClassName(x[i]);
            for(var j=0; j<y.length; ++j) y[j].textContent = vars[x[i]];
          }
        }

        function getParameterByName(name) {
            name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
            var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
                results = regex.exec(location.search);
            return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
        }

    </script>
  <style>
        body {
            font-family: sans-serif;
            width: 100%;
            font-size: 12px;
        }
        header {
            width: 100%;
            padding-bottom: 5px;
        }
        
        table {
            width: 100%;
            border-top: 1px solid;
        }

        .left {
            text-align: left;
        }

        .right {
            text-align: right;
        }

        .center {
          text-align: center;
        }
    </style>
</head>
<body onload="subst()">
    <header>
      <table>
        <tr>
          <td class="left">
            Date : {{ date("Y-m-d") }}
          </td>
          <td class="center">
            Copyright © 2016 by Sourceeasy, Inc. All rights reserved.
          </td>
          <td class="right">
            Page <span class="page"></span> of <span class="topage"></span>
          </td>
        </tr>
      </table>
    </header>

</body>
</html>

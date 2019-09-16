<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
    <table>
        <tr>
            <th>Customer</th>
            <th>Line</th>
            <th>Style</th>
            <th>PO Date</th>
            <th>Delivery Date</th>
            <th>VLP Approval</th>
            <th>Fabric Approval</th>
            <th>Fit Approval</th>
            <th>Lab Dip Approval</th>
            <th>Print Approval</th>
            <th>PP Approval</th>
        </tr>
        @foreach($data as $style)
        <tr>
            <td>{{ $style['customer']['name'] }}</td>
            <td>{{ $style['line']['name'] }}</td>
            <td>{{ $style['style']['name'] }}</td>
            <td>{{ $style['line']['soTargetDate'] }}</td>
            <td>{{ $style['line']['deliveryTargetDate'] }}</td>
            <td>@if($style['printApprDate']){{ \Carbon\Carbon::parse($style['printApprDate'])->format('m-d-Y') }}@else -NA- @endif</td>
            <td>@if($style['fabricApprDate']){{ \Carbon\Carbon::parse($style['fabricApprDate'])->format('m-d-Y') }}@else -NA- @endif</td>
            <td>@if($style['fitApprDate']){{ \Carbon\Carbon::parse($style['fitApprDate'])->format('m-d-Y') }}@else -NA- @endif</td>
            <td>@if($style['labDipApprDate']){{ \Carbon\Carbon::parse($style['labDipApprDate'])->format('m-d-Y') }}@else -NA- @endif</td>
            <td>@if($style['printApprDate']){{ \Carbon\Carbon::parse($style['printApprDate'])->format('m-d-Y') }}@else -NA- @endif</td>
            <td>@if($style['ppApprDate']){{ \Carbon\Carbon::parse($style['ppApprDate'])->format('m-d-Y') }}@else -NA- @endif</td>
        </tr>
        @endforeach
    </table>
</body>
</html>

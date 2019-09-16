<style type="text/css">
    .label {
        background: #bfbfbf;
        border: none !important;
    }

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

  .bom_image {
	max-height: 100px;
	max-width: 100px;
	width: auto;
    height: auto;
  }

</style>

<div class="page-breaker"></div>

<table class="table_export">
  <thead>
    <tr>
      <th>BILL OF MATERIALS</th>
    </tr>
  </thead>
</table>
@foreach ($techpack['bill_of_materials'] as $bom)
    @if ($bom->label == 'FABRIC')
     @include('exports.techpacks.vendor.partials._fabric_bom')
    @elseif ($bom->label == 'TRIMS')
     @include('exports.techpacks.vendor.partials._trims_bom')
    @elseif ($bom->label == 'ARTWORK')
     @include('exports.techpacks.vendor.partials._artwork_bom')
    @elseif ($bom->label == 'LABELS')
     @include('exports.techpacks.vendor.partials._labels_bom')
    @elseif ($bom->label == 'WASH FINISHING')
     @include('exports.techpacks.vendor.partials._wash_finishing_bom')
    @elseif ($bom->label == 'PACKAGING')
     @include('exports.techpacks.vendor.partials._packaging_bom')
    @endif

@endforeach

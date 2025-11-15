<div class="d-flex justify-content-center gap-2">
    <button class="btn btn-sm btn-warning text-white edt-btn" data-values="{{$d}}" data-edit-url="{{route('custom.fields.update',$d)}}"><i class="fa fa-pencil"></i> Edit</button>
    <button class="btn btn-sm btn-danger text-white dlt-btn" data-url="{{route('custom.fields.destroy',$d->id)}}"><i class="fa fa-trash"></i> Delete</button>
</div>
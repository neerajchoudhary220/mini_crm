@extends('layouts.main')
@section('title',"Merged Contacts")
@section('contents')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold">
            <i class="bi bi-journal-text me-2"></i> Merge Log Details
        </h4>

        <a href="{{ route('contacts') }}" class="btn btn-info text-white btn-sm">
            <i class="bi bi-arrow-left"></i> Back to Contacts
        </a>
    </div>

    <!-- SUMMARY CARD -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">

            <h5 class="fw-bold mb-3">Merge Summary</h5>

            <div class="row">
                <div class="col-md-6">
                    <p><strong>Master Contact:</strong><br>
                        <span class="text-primary">{{ $log->masterContact->name }}</span>
                        <br><small>{{ $log->masterContact->email }}</small>
                    </p>
                </div>

                <div class="col-md-6">
                    <p><strong>Merged (Secondary) Contact:</strong><br>
                        <span class="text-danger">{{ $log->mergedContact->name }}</span>
                        <br><small>{{ $log->mergedContact->email }}</small>
                    </p>
                </div>
            </div>

            <p class="text-muted small mb-0">
                <strong>Merged At:</strong> {{ $log->created_at->format('d M Y, h:i A') }}
            </p>

        </div>
    </div>


    <!-- BEFORE / AFTER PANEL -->
    <div class="row">

        <!-- BEFORE -->
        <div class="col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-danger text-white">
                    <strong>Secondary Contact (Before Merge)</strong>
                </div>
                <div class="card-body">

                    <h6 class="fw-bold mb-2">Standard Fields</h6>
                    <ul class="list-group mb-3">
                        @foreach($data['secondary'] as $key => $val)
                        @if(in_array($key, ['name','email','phone','gender']))
                        <li class="list-group-item d-flex justify-content-between">
                            <span>{{ ucfirst($key) }}</span>
                            <strong>{{ $val }}</strong>
                        </li>
                        @endif
                        @endforeach
                    </ul>

                    <h6 class="fw-bold mb-2">Custom Fields</h6>
                    <ul class="list-group">
                        @foreach($data['secondary_custom_values'] as $cf)
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Field ID: {{ $cf['custom_field_id'] }}</span>
                            <strong>{{ $cf['value'] }}</strong>
                        </li>
                        @endforeach
                    </ul>

                </div>
            </div>
        </div>

        <!-- AFTER -->
        <div class="col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <strong>Master Contact (After Merge)</strong>
                </div>
                <div class="card-body">

                    <h6 class="fw-bold mb-2">Standard Fields</h6>
                    <ul class="list-group mb-3">
                        @foreach($data['master'] as $key => $val)
                        @if(in_array($key, ['name','email','phone','gender']))
                        <li class="list-group-item d-flex justify-content-between">
                            <span>{{ ucfirst($key) }}</span>
                            <strong>{{ $val }}</strong>
                        </li>
                        @endif
                        @endforeach
                    </ul>

                    <h6 class="fw-bold mb-2">Merged Custom Fields</h6>
                    <ul class="list-group">
                        @foreach($data['merged_fields'] as $item)
                        <li class="list-group-item">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            {{ $item }}
                        </li>
                        @endforeach
                    </ul>

                </div>
            </div>
        </div>
    </div>



    <!-- CONFLICTS -->
    @if(!empty($data['conflicts']))
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-warning">
            <strong>Merge Conflicts</strong>
        </div>
        <div class="card-body">
            <ul class="list-group">
                @foreach($data['conflicts'] as $c)
                <li class="list-group-item">
                    <i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>
                    {{ $c }}
                </li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif



    <!-- MEDIA LIST -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-info text-white">
            <strong>Copied Media Files</strong>
        </div>

        <div class="card-body">
            @if(!empty($data['media_copied']))
            <div class="row">
                @foreach($data['media_copied'] as $mediaId)
                @php
                $media = \App\Models\Media::find($mediaId);
                @endphp

                @if($media)
                <div class="col-md-3 mb-3">
                    <div class="card h-100">
                        @if(Str::contains($media->mime_type, 'image'))
                        <img src="{{ Storage::url($media->file_path) }}"
                            class="card-img-top"
                            style="height:150px;object-fit:cover;">
                        @else
                        <div class="p-4 text-center">
                            <i class="bi bi-file-earmark-text fs-1 text-primary"></i>
                        </div>
                        @endif

                        <div class="card-body p-2">
                            <p class="small text-muted mb-1">{{ $media->file_name }}</p>
                            <span class="badge bg-secondary">{{ $media->collection_name }}</span>
                        </div>
                    </div>
                </div>
                @endif

                @endforeach
            </div>
            @else
            <p class="text-muted">No media files copied.</p>
            @endif
        </div>
    </div>


</div>
@endsection
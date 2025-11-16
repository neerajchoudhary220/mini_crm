@props([
'id' => 'data-table',
'headers' => [],
'class' => '',
])



<div class="row">
    <div class="col-lg-12">
        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-body p-3">
                {{ $slot }}
                <div class="row">
                    <div class="table-responsive">
                        <table id="{{ $id }}" class="table table-hover align-middle mb-0 w-100 dt-tbl {{ $class }}">
                            <thead class="table-info">
                                <tr>
                                    @foreach($headers as $header)
                                    <th class="{{ $header['class'] ?? '' }}">
                                        {{ $header['label'] }}
                                    </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
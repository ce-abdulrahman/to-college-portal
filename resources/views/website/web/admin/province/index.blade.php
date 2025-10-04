@extends('website.web.admin.layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-none d-lg-block text-center flex-grow-1">
            <div class="navbar-page-title">{{ __('پارێزگا') }}</div>
        </div>
        <a href="{{ route('admin.provinces.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus me-1"></i> {{ __('دروستکردنی پارێزگای نوێ') }}
        </a>
    </div>

    <div class="card glass fade-in">
        <div class="card-body">
            <h4 class="card-title mb-3">
                <i class="fa-solid fa-location-dot me-2"></i> {{ __('لیستی پارێزگاكان') }}
            </h4>

            {{-- Toolbar بالا بۆ فلتەر/زانیاری خێرا --}}
            <div class="table-toolbar mb-3 d-flex flex-column flex-md-row gap-2 gap-md-3 justify-content-md-between align-items-md-center">
                <span class="chip">
                    <i class="fa-solid fa-database"></i> {{ __('کۆی گشتی:') }} {{ count($provinces) }}
                </span>
            </div>

            <div class="table-wrap">
                <div class="table-responsive">
                    <table id="datatable" class="table dt-responsive nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th style="width:60px">#</th>
                                <th>ناو</th>
                                <th style="width:120px">دۆخ</th>
                                <th style="width:180px">کردار</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($provinces as $index => $province)
                                <tr>
                                    <td>{{ ++$index }}</td>
                                    <td class="fw-semibold">
                                        <i class="fa-solid fa-map-pin me-1 text-muted"></i>
                                        {{ $province->name }}
                                    </td>
                                    <td>
                                        @if ($province->status)
                                            <span class="badge bg-success">چاڵاک</span>
                                        @else
                                            <span class="badge bg-danger">ناچاڵاک</span>
                                        @endif
                                    </td>
                                    <td class="actions">
                                        <a href="{{ route('admin.provinces.show', $province->id) }}"
                                            class="btn btn-sm btn-info">
                                            <i class="fa-solid fa-eye me-1"></i>
                                        </a>
                                        <a href="{{ route('admin.provinces.edit', $province->id) }}"
                                            class="btn btn-sm btn-primary">
                                            <i class="fa-solid fa-pen-to-square me-1"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // DataTable init (ئەگەر پێشتر دانکردویت هەمان ID بەجێبهێڵە)
            const table = new DataTable('#datatable', {
                autoWidth: false,
                ordering: true,
                pageLength: 10,
                lengthMenu: [10, 25, 50, 100],
                language: {
                    search: 'گەڕان:',
                    lengthMenu: 'هەر پێژوو: _MENU_',
                    info: 'پیشاندان _START_ تا _END_ لە _TOTAL_',
                    paginate: {
                        previous: 'پێشتر',
                        next: 'دواتر'
                    },
                    zeroRecords: 'هیچ داتا نییە',
                    infoEmpty: 'هیچ تۆمار نییە',
                }
            });

            // فلتەری دۆخ
            const statusColIndex = 2; // "دۆخ" سێیەمە ستونە
            document.getElementById('filter-active').addEventListener('click', () => {
                table.column(statusColIndex).search('کارا').draw();
            });
            document.getElementById('filter-inactive').addEventListener('click', () => {
                table.column(statusColIndex).search('نەکارا').draw();
            });
            document.getElementById('filter-reset').addEventListener('click', () => {
                table.column(statusColIndex).search('').draw();
            });
        });
    </script>
@endpush

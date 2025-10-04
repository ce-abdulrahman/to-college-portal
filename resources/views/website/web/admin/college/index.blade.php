@extends('website.web.admin.layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-none d-lg-block text-center flex-grow-1">
            <div class="navbar-page-title">{{ __('لیستی کۆلێژەکان') }}</div>
        </div>
        <a href="{{ route('admin.colleges.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus me-1"></i> {{ __('زیادکردنی کۆلێژ') }}
        </a>
    </div>

    <div class="card glass fade-in">
        <div class="card-body">
            <h4 class="card-title mb-3">
                <i class="fa-solid fa-building-columns me-2"></i> {{ __('کۆلێژەکان') }}
            </h4>

            <div class="table-wrap">
                <div class="table-responsive">
                    <table id="datatable" class="table dt-responsive nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th style="width:60px">#</th>
                                <th>زانکۆ</th>
                                <th>ناوی کۆلێژ</th>
                                <th style="width:120px">دۆخ</th>
                                <th style="width:220px">کردار</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($colleges as $index => $college)
                                <tr>
                                    <td>{{ ++$index }}</td>
                                    <td>
                                        <i class="fa-solid fa-school me-1 text-muted"></i> {{ $college->university->name }}
                                    </td>
                                    <td class="fw-semibold">
                                        <i class="fa-solid fa-building me-1 text-muted"></i> {{ $college->name }}
                                    </td>
                                    <td>
                                        @if ($college->status)
                                            <span class="badge bg-success">چاڵاک</span>
                                        @else
                                            <span class="badge bg-danger">ناچاڵاک</span>
                                        @endif
                                    </td>
                                    <td class="actions">
                                        <a href="{{ route('admin.colleges.show', $college->id) }}"
                                            class="btn btn-sm btn-info">
                                            <i class="fa-solid fa-eye me-1"></i>
                                        </a>
                                        <a href="{{ route('admin.colleges.edit', $college->id) }}"
                                            class="btn btn-sm btn-primary">
                                            <i class="fa-solid fa-pen-to-square me-1"></i>
                                        </a>
                                        <form action="{{ route('admin.colleges.destroy', $college->id) }}" method="POST"
                                            class="d-inline" onsubmit="return confirm('دڵنیایت دەتەوێت کۆلێژ بسڕیتەوە؟');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fa-solid fa-trash-can me-1"></i>
                                            </button>
                                        </form>
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
            new DataTable('#datatable', {
                autoWidth: false,
                ordering: true,
                pageLength: 10,
                lengthMenu: [10, 25, 50, 100],
                language: {
                    search: 'گەڕان:',
                    lengthMenu: 'پیشاندانی _MENU_',
                    info: 'پیشاندانی _START_ تا _END_ لە _TOTAL_',
                    paginate: {
                        previous: 'پێشتر',
                        next: 'دواتر'
                    },
                    zeroRecords: 'هیچ داتا نییە',
                    infoEmpty: 'هیچ تۆمار نییە',
                }
            });
        });
    </script>
@endpush

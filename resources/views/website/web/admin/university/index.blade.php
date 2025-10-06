@extends('website.web.admin.layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-none d-lg-block text-center flex-grow-1">
            <div class="navbar-page-title">{{ __('لیستی زانکۆکان') }}</div>
        </div>
        <a href="{{ route('admin.universities.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus me-1"></i> {{ __('زیادکردنی زانکۆی نوێ') }}
        </a>
    </div>

    <div class="card glass fade-in">
        <div class="card-body">
            <h4 class="card-title mb-3">
                <i class="fa-solid fa-building-columns me-2"></i> {{ __('زانکۆکان') }}
            </h4>

            <div class="table-wrap">
                <div class="table-responsive">
                    <table id="datatable" class="table dt-responsive nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th style="width:60px">#</th>
                                <th>پارێزگا</th>
                                <th>ناوی زانکۆ</th>
                                <th style="width:120px">دۆخ</th>
                                <th style="width:180px">کردار</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($universities as $index => $university)
                                <tr>
                                    <td>{{ ++$index }}</td>
                                    <td><i class="fa-solid fa-map-pin me-1 text-muted"></i>
                                        {{ $university->province->name }}</td>
                                    <td class="fw-semibold">
                                        <i class="fa-solid fa-school me-1 text-muted"></i> {{ $university->name }}
                                    </td>
                                    <td>
                                        @if ($university->status)
                                            <span class="badge bg-success">چاڵاک</span>
                                        @else
                                            <span class="badge bg-danger">ناچاڵاک</span>
                                        @endif
                                    </td>
                                    <td class="actions">
                                        <a href="{{ route('admin.universities.show', $university->id) }}"
                                            class="btn btn-sm btn-info">
                                            <i class="fa-solid fa-eye me-1"></i>
                                        </a>
                                        <a href="{{ route('admin.universities.edit', $university->id) }}"
                                            class="btn btn-sm btn-primary">
                                            <i class="fa-solid fa-pen-to-square me-1"></i>
                                        </a>
                                        <form action="{{ route('admin.universities.destroy', $university->id) }}"
                                            method="POST" class="d-inline"
                                            onsubmit="return confirm('دڵنیایت دەتەوێت زانکۆ بسڕیتەوە؟');">
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
            TableKit.initDataTable({
                table: '#datatable',
                externalSearch: '#custom-search', // ئەگەر هەیە
                pageLengthSel: '#page-length', // ئەگەر هەیە
                infoBox: '#dt-info', // ئەگەر هەیە
                pagerBox: '#dt-pager' // ئەگەر هەیە
            });
        });
    </script>
@endpush

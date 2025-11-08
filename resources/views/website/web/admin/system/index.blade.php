@extends('website.web.admin.layouts.app')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class=" d-lg-block text-center flex-grow-1">
            <div class="navbar-page-title" style="font-size: 32px">
                <i class="fa-solid fa-building-columns me-2"></i> سیستەمەکانی خوێندن
            </div>
        </div>
    </div>


    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <a href="{{ route('admin.systems.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus me-1"></i> زیادکردنی
        </a>
        <span class="chip"><i class="fa-solid fa-database"></i> کۆی گشتی: {{ count($systems) }}</span>
    </div>

    <div class="table-wrap fade-in">
        <div class="table-responsive table-scroll-x">
            <table  id="simpleTable" class="table table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>ناو</th>
                        <th>دۆخ</th>
                        <th>کردار</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($systems as $index => $system)
                        <tr>
                            <td>{{ ++$index }}</td>
                            <td>{{ $system->name }}</td>
                            <td>
                                @if ($system->status)
                                    <span class="badge bg-success">چاڵاک</span>
                                @else
                                    <span class="badge bg-danger">ناچاڵاک</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.systems.edit', $system->id) }}"
                                    class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('admin.systems.destroy', $system->id) }}" method="POST"
                                    style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('ئیشکراوی ئەم سیستەمە دڵنیایت؟');"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

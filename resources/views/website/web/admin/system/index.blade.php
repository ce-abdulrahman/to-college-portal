@extends('website.web.admin.layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">سیستەمەکانی خوێندن</h4>
                    <a href="{{ route('admin.systems.create') }}" class="btn btn-primary mb-4">زیادکردنی نوێ</a>
                </div>
            </div>
        </div>
    </div>

    <div class="table-wrap fade-in">
        <div class="table-responsive">
            <table id="datatable" class="table  nowrap" style="width:100%">
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

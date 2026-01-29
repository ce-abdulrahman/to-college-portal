{{-- [file name]: index.blade.php --}}
@extends('website.web.admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">
                    <i class="fa-solid fa-database me-1"></i>
                    Backup & Restore
                </h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <h5 class="card-title">Backupەکان</h5>
                        <a href="{{ route('admin.backups.create') }}" class="btn btn-primary">
                            <i class="fa-solid fa-plus me-1"></i> Backup نوێ
                        </a>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ناو</th>
                                    <th>فایل</th>
                                    <th>جۆری داتابەیس</th>
                                    <th>ژ. تابل</th>
                                    <th>ژ. تۆمار</th>
                                    <th>قەبارە</th>
                                    <th>کات</th>
                                    <th>دۆخ</th>
                                    <th>کردار</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($backups as $backup)
                                <tr>
                                    <td>{{ $backup->name }}</td>
                                    {{-- لە index.blade.php --}}
                                    <td>
                                        <code>{{ $backup->file_path }}</code>
                                        @if($backup->public_path)
                                            <br>
                                            <small>
                                                <a href="{{ $backup->public_path }}" target="_blank">
                                                    <i class="fa-solid fa-external-link"></i> بینین
                                                </a>
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ strtoupper($backup->target_db) }}</span>
                                    </td>
                                    <td>{{ $backup->tables_count }}</td>
                                    <td>{{ number_format($backup->records_count) }}</td>
                                    <td>{{ number_format($backup->file_size, 2) }} KB</td>
                                    <td>{{ $backup->created_at->format('Y-m-d H:i') }}</td>
                                    <td>
                                        @if($backup->status == 'completed')
                                            <span class="badge bg-success">تەواو</span>
                                        @elseif($backup->status == 'pending')
                                            <span class="badge bg-warning">چاوەڕوان</span>
                                        @else
                                            <span class="badge bg-danger">شکست</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.backups.download', $backup->id) }}" 
                                               class="btn btn-outline-info" title="داگرتن">
                                                <i class="fa-solid fa-download"></i>
                                            </a>
                                            <a href="{{ route('admin.backups.restore', $backup->id) }}" 
                                               class="btn btn-outline-warning" title="Restore">
                                                <i class="fa-solid fa-rotate-left"></i>
                                            </a>
                                            <form action="{{ route('admin.backups.destroy', $backup->id) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger"
                                                        onclick="return confirm('دڵنیایت لە سڕینەوەی ئەم Backupە؟')">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{ $backups->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
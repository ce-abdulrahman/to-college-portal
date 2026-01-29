{{-- [file name]: restore.blade.php --}}
@extends('website.web.admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">
                    <i class="fa-solid fa-rotate-left me-1"></i>
                    Restore داتابەیس
                </h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-body">
                    <div class="alert alert-warning">
                        <i class="fa-solid fa-triangle-exclamation me-2"></i>
                        <strong>ئاگاداری!</strong> Restore هەموو داتاکانی ئێستا دەسڕێتەوە و داتای کۆن جێگەی دەگرێتەوە!
                    </div>

                    <div class="mb-4">
                        <h5>زانیاری Backup</h5>
                        <table class="table table-bordered">
                            <tr>
                                <th width="30%">ناو:</th>
                                <td>{{ $backup->name }}</td>
                            </tr>
                            <tr>
                                <th>فایل:</th>
                                <td><code>{{ basename($backup->file_path) }}</code></td>
                            </tr>
                            <tr>
                                <th>جۆری داتابەیس:</th>
                                <td>{{ strtoupper($backup->target_db) }}</td>
                            </tr>
                            <tr>
                                <th>ژ. تابل:</th>
                                <td>{{ $backup->tables_count }}</td>
                            </tr>
                            <tr>
                                <th>ژ. تۆمار:</th>
                                <td>{{ number_format($backup->records_count) }}</td>
                            </tr>
                            <tr>
                                <th>کات:</th>
                                <td>{{ $backup->created_at->format('Y-m-d H:i:s') }}</td>
                            </tr>
                        </table>
                    </div>

                    <form action="{{ route('admin.backups.perform-restore', $backup->id) }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="target_database" class="form-label">داتابەیسی ئامانج</label>
                            <select class="form-select" id="target_database" name="target_database" required>
                                <option value="mysql">MySQL</option>
                                <option value="sqlite">SQLite</option>
                            </select>
                            <small class="text-muted">
                                @if($backup->target_db == 'mysql' && $backup->target_db == 'sqlite')
                                    (دەتوانیت MySQL → SQLite یان SQLite → MySQL بکەیت)
                                @endif
                            </small>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="confirm" name="confirm" required>
                                <label class="form-check-label" for="confirm">
                                    <strong>دڵنیام کە هەموو داتاکانی ئێستا دەسڕێنرێنەوە!</strong>
                                </label>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.backups.index') }}" class="btn btn-secondary">
                                <i class="fa-solid fa-times me-1"></i> هەڵوەشاندنەوە
                            </a>
                            <button type="submit" class="btn btn-danger">
                                <i class="fa-solid fa-rotate-left me-1"></i> Restore
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
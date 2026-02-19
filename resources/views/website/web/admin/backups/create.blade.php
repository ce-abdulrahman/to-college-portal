{{-- [file name]: create.blade.php --}}
@extends('website.web.admin.layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">
                        <i class="fa-solid fa-database me-1"></i>
                        دروستکردنی Backup
                    </h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.backups.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label">ناوی Backup</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="Backup_{{ now()->format('Y-m-d_H-i') }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="target_db" class="form-label">جۆری داتابەیس</label>
                                <select class="form-select" id="target_db" name="target_db" required>
                                    <option value="mysql">MySQL (فایلەکانی .sql)</option>
                                    <option value="sqlite" selected>SQLite (فایلەکانی .sqlite)</option>
                                </select>
                                <small class="text-muted">SQLite بۆ بەکارهێنانی لەسەر مۆبایل یان دەسکتۆپ</small>
                            </div>

                            <div class="mb-3">
                                <label for="notes" class="form-label">تێبینی</label>
                                <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                            </div>

                            <div class="alert alert-info">
                                <i class="fa-solid fa-circle-info me-2"></i>
                                Backupەکە لەم شوێنانە پاشەکەوت دەکرێت: <code>storage/app/backups/</code>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.backups.index') }}" class="btn btn-secondary">
                                    <i class="fa-solid fa-times me-1"></i> هەڵوەشاندنەوە
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa-solid fa-database me-1"></i> دروستکردنی Backup
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

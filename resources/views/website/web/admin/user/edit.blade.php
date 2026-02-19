@extends('website.web.admin.layouts.app')

@section('page_name', 'users')
@section('view_name', 'edit')

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <div class="page-title-right d-flex align-items-center gap-2">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">داشبۆرد</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">بەکارهێنەران</a></li>
                            <li class="breadcrumb-item active">{{ $user->name }}</li>
                        </ol>
                        <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-outline-secondary ms-2">
                            <i class="fa-solid fa-eye me-1"></i> بینین
                        </a>
                    </div>
                    <h4 class="page-title">
                        <i class="fa-solid fa-user-pen me-2"></i>
                        دەستکاریکردنی بەکارهێنەر
                    </h4>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (in_array($user->role, ['admin', 'center', 'teacher'], true))
            @php
                $referralLink = route('register', ['ref' => $user->rand_code]);
            @endphp
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <h6 class="mb-2"><i class="fa-solid fa-link me-2 text-primary"></i>Referral Link بۆ بڵاوکردنەوە</h6>
                    <div class="input-group">
                        <input type="text" id="admin-referral-link" class="form-control" value="{{ $referralLink }}"
                            readonly>
                        <button class="btn btn-outline-primary" type="button" id="copy-admin-referral-link">
                            کۆپی
                        </button>
                    </div>
                    <small id="admin-referral-link-feedback" class="text-muted mt-2 d-inline-block">
                        ئەم لینکە بنێرە بۆ ئەو کەسانەی دەتەوێت لەژێر کۆدی ئەم بەکارهێنەرە تۆماربن.
                    </small>
                </div>
            </div>
        @endif

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    @include('website.web.admin.user.partials.form', [
                        'submitLabel' => 'پاشەکەوتی گۆڕانکاری',
                    ])
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const copyBtn = document.getElementById('copy-admin-referral-link');
            const input = document.getElementById('admin-referral-link');
            const feedback = document.getElementById('admin-referral-link-feedback');

            if (!copyBtn || !input || !feedback) {
                return;
            }

            copyBtn.addEventListener('click', async function() {
                try {
                    await navigator.clipboard.writeText(input.value);
                    feedback.textContent = 'لینکەکە کۆپی کرا.';
                    feedback.classList.remove('text-muted', 'text-danger');
                    feedback.classList.add('text-success');
                } catch (e) {
                    input.select();
                    document.execCommand('copy');
                    feedback.textContent = 'لینکەکە کۆپی کرا.';
                    feedback.classList.remove('text-muted', 'text-danger');
                    feedback.classList.add('text-success');
                }
            });
        });
    </script>
@endpush

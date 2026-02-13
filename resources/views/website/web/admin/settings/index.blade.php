@extends('website.web.admin.layouts.app')

@section('title', 'ڕێکخستنەکان')

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">داشبۆرد</a></li>
                            <li class="breadcrumb-item active">ڕێکخستنەکان</li>
                        </ol>
                    </div>
                    <h4 class="page-title">
                        <i class="fas fa-cog me-2"></i>
                        ڕێکخستنەکان
                    </h4>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check me-1"></i>{{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle me-1"></i>هەڵەیەک هەیە
                <ul class="mb-0 mt-2 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
            @csrf

            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="fas fa-pen-nib me-2"></i>هەڵبژاردنەکانی براند</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="site_name" class="form-label fw-bold">ناوی سیستەم</label>
                                <input type="text" class="form-control" id="site_name" name="site_name"
                                    value="{{ $settings['site_name'] ?? '' }}" placeholder="بۆ کۆلێژ">
                            </div>

                            <div class="mb-3">
                                <label for="site_logo" class="form-label fw-bold">لۆگۆ (Logo)</label>
                                @if (!empty($settings['site_logo']))
                                    <div class="mb-2">
                                        <img src="{{ asset($settings['site_logo']) }}" alt="Logo"
                                            style="max-height: 70px;" class="rounded border p-1">
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" value="1" id="remove_logo"
                                            name="remove_logo">
                                        <label class="form-check-label" for="remove_logo">سڕینەوەی لۆگۆ</label>
                                    </div>
                                @endif
                                <input type="file" class="form-control" id="site_logo" name="site_logo"
                                    accept="image/*">
                            </div>

                            <div class="mb-3">
                                <label for="copyright" class="form-label fw-bold">Copyright</label>
                                <input type="text" class="form-control" id="copyright" name="copyright"
                                    value="{{ $settings['copyright'] ?? '' }}"
                                    placeholder="مافی ئەم سیستەمە پارێزاوە...">
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm border-0 mt-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="fas fa-share-alt me-2"></i>سۆشیاڵ ئەکاونتەکان</h6>
                        </div>
                        <div class="card-body">
                            <div id="socialAccountsContainer">
                                @php
                                    $socialAccounts = $socialAccounts ?? [];
                                @endphp

                                @forelse ($socialAccounts as $index => $social)
                                    <div class="row g-2 align-items-end social-row mb-3">
                                        <div class="col-md-3">
                                            <label class="form-label small">Name</label>
                                            <input type="text" name="social_name[]" class="form-control"
                                                value="{{ $social['name'] ?? '' }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label small">Icon (FontAwesome)</label>
                                            <input type="text" name="social_icon[]" class="form-control"
                                                value="{{ $social['icon'] ?? '' }}" placeholder="fab fa-facebook-f">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label small">URL</label>
                                            <input type="text" name="social_url[]" class="form-control"
                                                value="{{ $social['url'] ?? '' }}">
                                        </div>
                                        <div class="col-md-1 text-center">
                                            <button type="button" class="btn btn-outline-danger btn-sm remove-social">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                @empty
                                    <div class="row g-2 align-items-end social-row mb-3">
                                        <div class="col-md-3">
                                            <label class="form-label small">Name</label>
                                            <input type="text" name="social_name[]" class="form-control">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label small">Icon (FontAwesome)</label>
                                            <input type="text" name="social_icon[]" class="form-control"
                                                placeholder="fab fa-facebook-f">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label small">URL</label>
                                            <input type="text" name="social_url[]" class="form-control">
                                        </div>
                                        <div class="col-md-1 text-center">
                                            <button type="button" class="btn btn-outline-danger btn-sm remove-social">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforelse
                            </div>

                            <button type="button" class="btn btn-outline-primary btn-sm" id="addSocialBtn">
                                <i class="fas fa-plus me-1"></i>زیادکردنی ئەکاونت
                            </button>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="fas fa-font me-2"></i>فۆنتەکان</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-bold">فۆنتی کوردی (ku)</label>
                                <select name="font_ku" class="form-select">
                                    <option value="">Default</option>
                                    @foreach ($fontOptions['ku'] as $fontPath)
                                        <option value="{{ $fontPath }}"
                                            @selected(($settings['font_ku'] ?? '') === $fontPath)>
                                            {{ basename($fontPath) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">فۆنتی عەرەبی (ar)</label>
                                <select name="font_ar" class="form-select">
                                    <option value="">Default</option>
                                    @foreach ($fontOptions['ar'] as $fontPath)
                                        <option value="{{ $fontPath }}"
                                            @selected(($settings['font_ar'] ?? '') === $fontPath)>
                                            {{ basename($fontPath) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">فۆنتی ئینگلیزی (en)</label>
                                <select name="font_en" class="form-select">
                                    <option value="">Default</option>
                                    @foreach ($fontOptions['en'] as $fontPath)
                                        <option value="{{ $fontPath }}"
                                            @selected(($settings['font_en'] ?? '') === $fontPath)>
                                            {{ basename($fontPath) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="alert alert-light small mb-0">
                                ئەم فۆنتانە لە فولدەری <code>public/fonts</code> وەردەگیرێن.
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm border-0 mt-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="fas fa-layer-group me-2"></i>سیستەمەکان</h6>
                        </div>
                        <div class="card-body">
                            <label class="form-label fw-bold">سیستەمی بنچینەیی</label>
                            <select name="default_system_id" class="form-select">
                                <option value="">Default</option>
                                @foreach ($systems as $system)
                                    <option value="{{ $system->id }}"
                                        @selected(($settings['default_system_id'] ?? '') == $system->id)>
                                        {{ $system->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="small text-muted mt-2">
                                بۆ گۆڕینی سیستەمەکان بە تەواوی، پەڕەی
                                <a href="{{ route('admin.systems.index') }}">سیستەمەکان</a> بەکاربهێنە.
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm border-0 mt-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="fas fa-tags me-2"></i>نرخەکانی داواکاری تایبەتمەندی</h6>
                        </div>
                        <div class="card-body">
                            @php
                                $price1 = $featurePrices['1'] ?? 3000;
                                $price2 = $featurePrices['2'] ?? 5000;
                                $price3 = $featurePrices['3'] ?? 6000;
                            @endphp
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">١ تایبەتمەندی</label>
                                    <input type="number" min="0" class="form-control" name="price_1"
                                        value="{{ $price1 }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">٢ تایبەتمەندی</label>
                                    <input type="number" min="0" class="form-control" name="price_2"
                                        value="{{ $price2 }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">٣ تایبەتمەندی</label>
                                    <input type="number" min="0" class="form-control" name="price_3"
                                        value="{{ $price3 }}">
                                </div>
                            </div>
                            <div class="small text-muted mt-2">
                                نرخی داواکاری بە ژمارەی تایبەتمەندییەکان پێوەندیدارە.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-4">
                <button type="submit" class="btn btn-primary px-5">
                    <i class="fas fa-save me-2"></i>پاشەکەوتکردن
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        (function() {
            const container = document.getElementById('socialAccountsContainer');
            const addBtn = document.getElementById('addSocialBtn');

            function attachRemove(btn) {
                btn.addEventListener('click', function() {
                    const row = btn.closest('.social-row');
                    if (row) row.remove();
                });
            }

            if (container) {
                container.querySelectorAll('.remove-social').forEach(attachRemove);
            }

            if (addBtn) {
                addBtn.addEventListener('click', function() {
                    const row = document.createElement('div');
                    row.className = 'row g-2 align-items-end social-row mb-3';
                    row.innerHTML = `
                        <div class="col-md-3">
                            <label class="form-label small">Name</label>
                            <input type="text" name="social_name[]" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small">Icon (FontAwesome)</label>
                            <input type="text" name="social_icon[]" class="form-control" placeholder="fab fa-facebook-f">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small">URL</label>
                            <input type="text" name="social_url[]" class="form-control">
                        </div>
                        <div class="col-md-1 text-center">
                            <button type="button" class="btn btn-outline-danger btn-sm remove-social">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    `;
                    container.appendChild(row);
                    attachRemove(row.querySelector('.remove-social'));
                });
            }
        })();
    </script>
@endpush


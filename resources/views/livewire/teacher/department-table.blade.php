<div>
    {{-- Filters --}}
    <div class="card glass mb-4 border-0 shadow-sm">
        <div class="card-body p-4">
            <div class="row g-3 align-items-end">
                {{-- System Filter --}}
                <div class="col-md-3">
                    <label class="form-label text-primary fw-bold"><i class="fa-solid fa-cube me-1"></i> سیستەم</label>
                    <select wire:model.live="system_id" wire:loading.attr="disabled" class="form-select border-soft">
                        <option value="">هەموu سیستەمەکان</option>
                        @foreach ($systems as $sys)
                            <option value="{{ $sys->id }}" wire:key="sys-{{ $sys->id }}">{{ $sys->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Province Filter --}}
                <div class="col-md-3">
                    <label class="form-label text-primary fw-bold"><i class="fa-solid fa-map-pin me-1"></i>
                        پارێزگا</label>
                    <select wire:model.live="province_id" wire:loading.attr="disabled" class="form-select border-soft">
                        <option value="">هەموو پارێزگاكان</option>
                        @foreach ($provinces as $prov)
                            <option value="{{ $prov->id }}" wire:key="prov-{{ $prov->id }}">{{ $prov->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- University Filter --}}
                <div class="col-md-3">
                    <label class="form-label text-primary fw-bold"><i class="fa-solid fa-school me-1"></i> زانکۆ</label>
                    <select wire:model.live="university_id" wire:loading.attr="disabled" class="form-select border-soft"
                        {{ empty($universities) ? 'disabled' : '' }}>
                        <option value="">هەموو زانکۆكان</option>
                        @foreach ($universities as $uni)
                            <option value="{{ $uni->id }}" wire:key="uni-{{ $uni->id }}">{{ $uni->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- College Filter --}}
                <div class="col-md-3">
                    <label class="form-label text-primary fw-bold"><i class="fa-solid fa-building-columns me-1"></i>
                        کۆلێژ</label>
                    <select wire:model.live="college_id" wire:loading.attr="disabled" class="form-select border-soft"
                        {{ empty($colleges) ? 'disabled' : '' }}>
                        <option value="">هەموو کۆلێژەکان</option>
                        @foreach ($colleges as $coll)
                            <option value="{{ $coll->id }}" wire:key="coll-{{ $coll->id }}">
                                {{ $coll->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Search --}}
                <div class="col-md-6">
                    <label class="form-label text-primary fw-bold"><i class="fa-solid fa-magnifying-glass me-1"></i>
                        گەڕان</label>
                    <div class="input-group">
                        <input wire:model.live.debounce.150ms="search" type="text" class="form-control border-soft"
                            placeholder="گەڕان لە ناو، زانکۆ، یان کۆلێژ...">
                        <span class="input-group-text bg-light border-soft"><i
                                class="fa-solid fa-search text-muted"></i></span>
                    </div>
                </div>

                {{-- Limit --}}
                <div class="col-md-3">
                    <label class="form-label text-primary fw-bold">ژمارەی ڕیز</label>
                    <select wire:model.live="limit" class="form-select border-soft">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>

                {{-- Reset --}}
                <div class="col-md-3">
                    <button wire:click="resetFilters" class="btn btn-soft-secondary w-100 py-2">
                        <i class="fa-solid fa-rotate-left me-1"></i> پاککردنەوە
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Loading Indicator --}}
    <div wire:loading class="w-100 mb-3">
        <div class="alert alert-soft-primary py-2 px-3 small shadow-sm d-flex align-items-center">
            <div class="spinner-border spinner-border-sm me-2" role="status"></div>
            <span>خەریکی بارکردنی داتایە... تکایە چاوەڕێ بکە</span>
        </div>
    </div>

    {{-- Table --}}
    <div class="card glass border-0 shadow-sm fade-in">
        <div class="card-body p-0">
            <div class="p-4 border-bottom d-flex justify-content-between align-items-center bg-light">
                <h4 class="card-title mb-0 text-dark fw-bold"><i class="fa-solid fa-table-list me-2 text-primary"></i>
                    بەشەکان</h4>
                <div class="text-muted small">
                    نیشاندانی {{ $departments->firstItem() }} تا {{ $departments->lastItem() }} لە کۆی
                    {{ $departments->total() }} بەش
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-soft-light text-primary">
                        <tr>
                            <th width="60" class="ps-4">#</th>
                            <th width="80">وێنە</th>
                            <th>ناوی بەش و زانیارییەکان</th>
                            <th width="120">ن. ناوەندی</th>
                            <th width="120">ن. دەرەوە</th>
                            <th width="120" class="text-center pe-4">کردار</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($departments as $department)
                            @php
                                $systemName = $department->system->name ?? '-';
                                $badge = match ($systemName) {
                                    'زانکۆلاین' => 'bg-soft-primary text-primary',
                                    'پاراڵیل' => 'bg-soft-success text-success',
                                    'ئێواران' => 'bg-soft-warning text-warning',
                                    default => 'bg-soft-secondary text-secondary',
                                };
                            @endphp
                            <tr wire:key="dept-{{ $department->id }}">
                                <td class="ps-4 text-muted">
                                    {{ ($departments->currentPage() - 1) * $departments->perPage() + $loop->iteration }}
                                </td>
                                <td>
                                    @if ($department->image)
                                        <img src="{{ $department->image }}" alt="{{ $department->name }}"
                                            class="rounded shadow-sm"
                                            style="width: 50px; height: 40px; object-fit: cover;">
                                    @else
                                        <div class="rounded bg-soft-secondary d-flex align-items-center justify-content-center shadow-sm"
                                            style="width: 50px; height: 40px;">
                                            <i class="fa-solid fa-image text-muted opacity-50"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-bold text-dark fs-15">{{ $department->name }}</div>
                                    <div class="text-muted small mt-1 d-flex flex-wrap gap-1 align-items-center">
                                        <span>{{ $department->province->name ?? '-' }}</span>
                                        <i class="fa-solid fa-chevron-left x-small opacity-50"></i>
                                        <span>{{ $department->university->name ?? '-' }}</span>
                                        <i class="fa-solid fa-chevron-left x-small opacity-50"></i>
                                        <span>{{ $department->college->name ?? '-' }}</span>
                                    </div>
                                    <div class="mt-2 text-primary">
                                        <span class="badge {{ $badge }} border-0 px-2 py-1 fs-12">
                                            <i class="fa-solid fa-cube me-1"></i>{{ $systemName }}
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    @if ($department->local_score)
                                        <span
                                            class="badge bg-soft-info text-info border-info-soft px-3 py-2 fs-13">{{ number_format($department->local_score, 3) }}</span>
                                    @else
                                        <span class="text-muted opacity-50">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($department->external_score)
                                        <span
                                            class="badge bg-soft-warning text-warning border-warning-soft px-3 py-2 fs-13">{{ number_format($department->external_score, 3) }}</span>
                                    @else
                                        <span class="text-muted opacity-50">—</span>
                                    @endif
                                </td>
                                <td class="text-center pe-4">
                                    <a href="{{ route('teacher.departments.show', $department->id) }}"
                                        class="btn btn-sm btn-soft-primary px-3" title="نیشاندان">
                                        <i class="fa-solid fa-eye me-1"></i> نیشاندان
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="py-4">
                                        <i class="fa-solid fa-folder-open fs-1 mb-3 text-muted opacity-25 d-block"></i>
                                        <h5 class="text-muted">هیچ بەشێک نەدۆزرایەوە</h5>
                                        <p class="text-muted small">تکایە گەڕانەکەت یان فلتەرەکانت بگۆڕە</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($departments->hasPages())
                <div class="p-4 border-top bg-light">
                    {{ $departments->links() }}
                </div>
            @endif
        </div>
    </div>
    <style>
        .glass {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
        }

        .border-soft {
            border-color: #e9ebec !important;
        }

        .bg-soft-primary {
            background-color: rgba(64, 81, 137, 0.1) !important;
            color: #405189 !important;
        }

        .bg-soft-success {
            background-color: rgba(10, 179, 156, 0.1) !important;
            color: #0ab39c !important;
        }

        .bg-soft-warning {
            background-color: rgba(247, 184, 75, 0.1) !important;
            color: #f7b84b !important;
        }

        .bg-soft-info {
            background-color: rgba(41, 156, 219, 0.1) !important;
            color: #299cdb !important;
        }

        .bg-soft-secondary {
            background-color: rgba(53, 52, 57, 0.1) !important;
            color: #353439 !important;
        }

        .bg-soft-light {
            background-color: #f3f6f9 !important;
        }

        .text-primary {
            color: #405189 !important;
        }

        .btn-soft-primary {
            background-color: rgba(64, 81, 137, 0.1);
            color: #405189;
            border: none;
        }

        .btn-soft-primary:hover {
            background-color: #405189;
            color: #fff;
        }

        .btn-soft-secondary {
            background-color: rgba(53, 52, 57, 0.1);
            color: #353439;
            border: none;
        }

        .btn-soft-secondary:hover {
            background-color: #353439;
            color: #fff;
        }

        .x-small {
            font-size: 0.7rem;
        }

        .fs-15 {
            font-size: 15px;
        }

        .fs-13 {
            font-size: 13px;
        }

        .fs-12 {
            font-size: 12px;
        }

        .fade-in {
            animation: fadeIn 0.3s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</div>

<div>
    <div class="row g-4">
        <!-- Filters & Available Departments (Right Side in RTL) -->
        <div class="col-xl-8">
            <div class="card glass border-0 shadow-sm mb-4 fade-in">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4">
                        <div class="avatar-sm flex-shrink-0 me-3">
                            <span class="avatar-title bg-soft-primary text-primary rounded-circle fs-4">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold"> گەڕان و فلتەرکردنی بەشەکان</h5>
                            <p class="text-muted small mb-0"> بەشە گونجاوەکان ڕەچاوکراون بەپێی نمرە و ڕەگەز و لقی خوێندنت
                            </p>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-muted">ناوی بەش</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-soft"><i
                                        class="fa-solid fa-search"></i></span>
                                <input wire:model.live.debounce.150ms="search" type="text"
                                    class="form-control border-soft" placeholder="بگەڕێ بۆ ناوی بەش...">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-muted">سیستەم</label>
                            <select wire:model.live="selectedSystem" class="form-select border-soft">
                                <option value="">هەموو سیستەمەکان</option>
                                @foreach ($systems as $system)
                                    <option value="{{ $system->id }}">{{ $system->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-muted">پارێزگا</label>
                            <select wire:model.live="selectedProvince" class="form-select border-soft">
                                <option value="">هەموو پارێزگاکان</option>
                                @foreach ($provinces as $province)
                                    <option value="{{ $province->id }}">{{ $province->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">زانکۆ</label>
                            <select wire:model.live="selectedUniversity" class="form-select border-soft"
                                {{ empty($universities) ? 'disabled' : '' }}>
                                <option value="">هەموو زانکۆکان</option>
                                @foreach ($universities as $uni)
                                    <option value="{{ $uni->id }}">{{ $uni->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">کۆلێژ</label>
                            <select wire:model.live="selectedCollege" class="form-select border-soft"
                                {{ empty($colleges) ? 'disabled' : '' }}>
                                <option value="">هەموو کۆلێژەکان</option>
                                @foreach ($colleges as $college)
                                    <option value="{{ $college->id }}">{{ $college->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Available Departments List -->
            <div class="card glass border-0 shadow-sm overflow-hidden">
                <div class="card-header bg-soft-light border-0 py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold text-primary"><i class="fa-solid fa-list-ul me-2"></i> بەشە بەردەستەکان</h6>
                    <div wire:loading
                        wire:target="search, selectedSystem, selectedProvince, selectedUniversity, selectedCollege">
                        <span class="spinner-border spinner-border-sm text-primary" role="status"></span>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-soft-secondary text-muted small">
                            <tr>
                                <th class="ps-4">بەش و زانیاری</th>
                                <th class="text-center">نمرەی وەرگرتن</th>
                                <th class="text-center">سیستەم</th>
                                <th class="text-center pe-4" style="width: 140px;">کردار</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($availableDepartments as $dept)
                                <tr wire:key="available-{{ $dept->id }}">
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark">{{ $dept->name }}</div>
                                        <div class="text-muted smaller">
                                            {{ $dept->province->name }} • {{ $dept->university->name }} •
                                            {{ $dept->college->name }}

                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span
                                            class="badge bg-soft-primary text-primary px-3 py-2">{{ $dept->local_score }}</span>
                                    </td>
                                    <td class="text-center small">
                                        <span
                                            class="text-muted border-{{ $dept->system->id === 1 ? 'success bg-light' : ($dept->system->id === 2 ? 'danger bg-light' : 'dark bg-light') }}">{{ $dept->system->name }}</span>
                                    </td>
                                    <td class="text-center pe-4">
                                        @php $isSelected = in_array($dept->id, $sessionSelectedIds); @endphp
                                        <button wire:click="addDepartment({{ $dept->id }})"
                                            wire:loading.attr="disabled"
                                            class="btn btn-sm w-100 {{ $isSelected ? 'btn-soft-success' : 'btn-primary shadow-sm' }}"
                                            {{ $isSelected ? 'disabled' : '' }}>
                                            @if ($isSelected)
                                                <i class="fa-solid fa-check me-1"></i> هەڵبژێردرا
                                            @else
                                                <i class="fa-solid fa-plus me-1"></i> زیادکردن
                                            @endif
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr wire:key="available-empty">
                                    <td colspan="4" class="text-center py-5">
                                        <div class="py-4">
                                            <i class="fa-solid fa-inbox fa-3x text-muted opacity-25 mb-3"></i>
                                            <p class="text-muted">هیچ بەشێک نەدۆزرایەوە بەم مەرجانە.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($availableDepartments->hasPages())
                    <div class="card-footer bg-transparent border-0 py-3">
                        {{ $availableDepartments->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Ranked Selection List (Left Side in RTL) -->
        <div class="col-xl-4">
            <div class="card glass border-0 shadow-lg sticky-top" style="top: 2rem; z-index: 100;">
                <div
                    class="card-header bg-primary text-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold text-white"><i class="fa-solid fa-ranking-star me-2"></i> ڕێزبەندی
                        هەڵبژاردنەکان</h6>
                    <span class="badge bg-white text-primary px-3">{{ count($sessionSelectedIds) }} /
                        {{ $maxSelections }}</span>
                </div>
                <div class="card-body p-0" style="max-height: 70vh; overflow-y: auto;">
                    @if (count($sessionSelectedIds) > 0)
                        <div class="list-group list-group-flush" id="selected-list" wire:key="selected-list-full">
                            @foreach ($selectedDepartments as $index => $dept)
                                <div wire:key="selected-row-{{ $dept->id }}"
                                    class="list-group-item bg-transparent border-soft border-start-0 border-end-0 py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3 text-muted fw-bold" style="width: 25px;">{{ $index + 1 }}
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="fw-bold text-dark small">{{ $dept->name }}</div>
                                            <div class="text-muted smaller">{{ $dept->university->name }}</div>
                                        </div>
                                        <div class="d-flex flex-column gap-1 me-2">
                                            <button wire:click="moveUp({{ $index }})"
                                                class="btn btn-xs btn-soft-secondary"
                                                {{ $index === 0 ? 'disabled' : '' }}>
                                                <i class="fa-solid fa-chevron-up"></i>
                                            </button>
                                            <button wire:click="moveDown({{ $index }})"
                                                class="btn btn-xs btn-soft-secondary"
                                                {{ $index === count($sessionSelectedIds) - 1 ? 'disabled' : '' }}>
                                                <i class="fa-solid fa-chevron-down"></i>
                                            </button>
                                        </div>
                                        <button wire:click="removeDepartment({{ $dept->id }})"
                                            class="btn btn-sm btn-soft-danger rounded-circle p-0"
                                            style="width: 30px; height: 30px;">
                                            <i class="fa-solid fa-xmark"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-5 text-center" wire:key="selected-list-empty">
                            <i class="fa-solid fa-list-ol fa-3x text-muted opacity-25 mb-3 d-block"></i>
                            <p class="text-muted small">هێشتا چ بەشێکت هەڵنەبژاردووە بۆ ڕێزبەندکردن.</p>
                        </div>
                    @endif
                </div>
                <div class="card-footer bg-soft-light border-0 p-3">
                    @if ($hasUnsavedChanges)
                        <div class="alert alert-warning border-0 small py-2 px-3 mb-3">
                            <i class="fa-solid fa-circle-exclamation me-1"></i> گۆڕانکارییەکان هێشتا پاشەکەوت نەکراون!
                        </div>
                    @endif
                    <button wire:click="saveChanges" wire:loading.attr="disabled"
                        class="btn btn-success w-100 py-2 fw-bold shadow-sm"
                        {{ count($sessionSelectedIds) === 0 ? 'disabled' : '' }}>
                        <span wire:loading wire:target="saveChanges"
                            class="spinner-border spinner-border-sm me-1"></span>
                        <i class="fa-solid fa-floppy-disk me-1"></i> پاشەکەوتکردنی ڕێزبەندی
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .glass {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        .bg-soft-primary {
            background-color: rgba(var(--vz-primary-rgb), 0.1);
        }

        .bg-soft-secondary {
            background-color: rgba(var(--vz-secondary-rgb), 0.05);
        }

        .bg-soft-success {
            background-color: rgba(var(--vz-success-rgb), 0.1);
        }

        .bg-soft-danger {
            background-color: rgba(var(--vz-danger-rgb), 0.1);
        }

        .bg-soft-light {
            background-color: rgba(243, 246, 249, 0.8);
        }

        .border-soft {
            border-color: rgba(0, 0, 0, 0.05) !important;
        }

        .smaller {
            font-size: 0.75rem;
        }

        .btn-xs {
            padding: 0.1rem 0.4rem;
            font-size: 0.7rem;
        }

        .fade-in {
            animation: fadeIn 0.5s ease-in;
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

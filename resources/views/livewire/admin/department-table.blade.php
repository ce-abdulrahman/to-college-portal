<div>
    {{-- Filters --}}
    <div class="card glass mb-4">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                {{-- System Filter --}}
                <div class="col-md-3">
                    <label class="form-label"><i class="fa-solid fa-cube me-1 text-muted"></i> سیستەم</label>
                    <select wire:model.live="system_id" class="form-select">
                        <option value="">هەموو سیستەمەکان</option>
                        @foreach ($systems as $sys)
                            <option value="{{ $sys->id }}">{{ $sys->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Province Filter --}}
                <div class="col-md-3">
                    <label class="form-label"><i class="fa-solid fa-map-pin me-1 text-muted"></i> پارێزگا</label>
                    <select wire:model.live="province_id" class="form-select">
                        <option value="">هەموو پارێزگاكان</option>
                        @foreach ($provinces as $prov)
                            <option value="{{ $prov->id }}">{{ $prov->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- University Filter --}}
                <div class="col-md-3">
                    <label class="form-label"><i class="fa-solid fa-school me-1 text-muted"></i> زانکۆ</label>
                    <select wire:model.live="university_id" class="form-select">
                        <option value="">هەموو زانکۆكان</option>
                        @foreach ($universities as $uni)
                            <option value="{{ $uni->id }}">{{ $uni->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- College Filter --}}
                <div class="col-md-3">
                    <label class="form-label"><i class="fa-solid fa-building-columns me-1 text-muted"></i> کۆلێژ</label>
                    <select wire:model.live="college_id" class="form-select">
                        <option value="">هەموو کۆلێژەکان</option>
                        @foreach ($colleges as $coll)
                            <option value="{{ $coll->id }}">{{ $coll->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Search --}}
                <div class="col-md-6">
                    <label class="form-label"><i class="fa-solid fa-magnifying-glass me-1 text-muted"></i> گەڕان</label>
                    <div class="input-group">
                        <input wire:model.live.debounce.300ms="search" type="text" class="form-control"
                            placeholder="گەڕان...">
                        <span class="input-group-text"><i class="fa-solid fa-search"></i></span>
                    </div>
                </div>

                {{-- Limit --}}
                <div class="col-md-3">
                    <label class="form-label">ژمارەی ڕیز</label>
                    <select wire:model.live="limit" class="form-select">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>

                {{-- Reset --}}
                <div class="col-md-3">
                    <button wire:click="resetFilters" class="btn btn-outline-secondary w-100">
                        <i class="fa-solid fa-rotate-left me-1"></i> پاککردنەوە
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Loading Indicator --}}
    <div wire:loading class="w-100 mb-2">
        <div class="alert alert-info py-2 small shadow-sm">
            <i class="fas fa-spinner fa-spin me-2"></i> خەریکی بارکردنی داتایە...
        </div>
    </div>

    {{-- Table --}}
    <div class="card glass fade-in">
        <div class="card-body">
            <h4 class="card-title mb-4"><i class="fa-solid fa-table-list me-2"></i> بەشەکان</h4>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th width="50">#</th>
                            <th>وێنە</th>
                            <th>ناو</th>
                            <th width="100">ن. ناوەندی</th>
                            <th width="100">ن. دەرەوە</th>
                            <th width="80">دۆخ</th>
                            <th width="150" class="text-center">کردار</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($departments as $department)
                            @php
                                $systemName = $department->system->name ?? '-';
                                $badge = match ($systemName) {
                                    'زانکۆلاین' => 'bg-primary',
                                    'پاراڵیل' => 'bg-success',
                                    default => 'bg-secondary',
                                };
                            @endphp
                            <tr>
                                <td>{{ ($departments->currentPage() - 1) * $departments->perPage() + $loop->iteration }}
                                </td>
                                <td>
                                    <img src="{{ $department->image }}" alt="{{ $department->name }}" class="rounded"
                                        style="width: 50px; height: 40px; object-fit: cover;">
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $department->name }}</div>
                                    <div class="text-muted small mt-1">
                                        {{ $department->system->name ?? '-' }} /
                                        {{ $department->province->name ?? '-' }} /
                                        {{ $department->university->name ?? '-' }} /
                                        {{ $department->college->name ?? '-' }}
                                    </div>
                                    <span class="badge {{ $badge }} mt-1">
                                        <i class="fa-solid fa-cube me-1"></i>{{ $systemName }}
                                    </span>
                                </td>
                                <td>
                                    @if ($department->local_score)
                                        <span class="badge bg-info">{{ $department->local_score }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($department->external_score)
                                        <span
                                            class="badge bg-warning text-dark">{{ $department->external_score }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($department->status)
                                        <span class="badge bg-success">چاڵاک</span>
                                    @else
                                        <span class="badge bg-danger">ناچاڵاک</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('admin.departments.show', $department->id) }}"
                                            class="btn btn-outline-info" title="پیشاندان">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.departments.edit', $department->id) }}"
                                            class="btn btn-outline-primary" title="دەستکاری">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        {{-- We use regular form submission for delete to utilize strict CRUD routes without converting to Livewire actions yet --}}
                                        <form action="{{ route('admin.departments.destroy', $department->id) }}"
                                            method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger"
                                                onclick="return confirm('ئایە دڵنیایت لە سڕینەوەی ئەم بەشە؟');"
                                                title="سڕینەوە">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-folder-open fs-1 mb-3 d-block"></i>
                                    هیچ بەشێک نەدۆزرایەوە
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $departments->links() }}
            </div>
        </div>
    </div>
</div>

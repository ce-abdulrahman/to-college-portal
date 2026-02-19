@php
    $center = $center ?? auth()->user()?->center;
    $currentModel = $currentModel ?? null;
    $subjectLabel = $subjectLabel ?? 'بەکارهێنەر';
    $formPrefix = $formPrefix ?? 'feature';
    $ownerLabel = $ownerLabel ?? 'سەنتەر';
    $featureDefinitions = $featureDefinitions ?? [
        'ai_rank' => 'ڕیزبەندی کرد بە زیرەکی دەستکرد',
        'gis' => ' سیستەمی نەخشە',
        'all_departments' => 'ڕێزبەندی 50 بەش',
    ];
@endphp

<div class="card border-light-subtle mt-4">
    <div class="card-body">
        <h6 class="mb-2">
            <i class="fa-solid fa-sliders me-1"></i>
            دیاریکردنی تایبەتمەندییەکان بۆ {{ $subjectLabel }}
        </h6>
        <p class="text-muted small mb-3">
            تەنها تایبەتمەندییە چالاکەکانی {{ $ownerLabel }} دەتوانرێت بۆ {{ $subjectLabel }} هەڵبژێردرێت.
        </p>

        <div class="row g-3">
            @foreach ($featureDefinitions as $featureKey => $featureLabel)
                @php
                    $centerEnabled = (int) ($center?->{$featureKey} ?? 0) === 1;
                    $fallbackValue = $centerEnabled ? 1 : 0;
                    $currentValue = (string) old(
                        $featureKey,
                        $currentModel ? (int) ($currentModel->{$featureKey} ?? $fallbackValue) : $fallbackValue,
                    );
                    if (!$centerEnabled) {
                        $currentValue = '0';
                    }
                    $idYes = $formPrefix . '_' . $featureKey . '_1';
                    $idNo = $formPrefix . '_' . $featureKey . '_0';
                @endphp

                <div class="col-12 col-md-6 col-xl-4">
                    <div class="border rounded p-3 h-100">
                        <div class="fw-semibold mb-2">{{ $featureLabel }}</div>

                        @if ($centerEnabled)
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="{{ $featureKey }}"
                                        id="{{ $idYes }}" value="1" @checked($currentValue === '1')>
                                    <label class="form-check-label" for="{{ $idYes }}">چالاک</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="{{ $featureKey }}"
                                        id="{{ $idNo }}" value="0" @checked($currentValue !== '1')>
                                    <label class="form-check-label" for="{{ $idNo }}">ناچالاک</label>
                                </div>
                            </div>
                        @else
                            <span class="badge bg-danger">لە {{ $ownerLabel }} ناچالاکە</span>
                            <input type="hidden" name="{{ $featureKey }}" value="0">
                        @endif
                    </div>
                    @error($featureKey)
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            @endforeach
        </div>
    </div>
</div>

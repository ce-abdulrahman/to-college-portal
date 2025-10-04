@extends('website.web.admin.layouts.app')

@section('content')
    {{-- Actions bar --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('admin.universities.index') }}" class="btn btn-outline">
            <i class="fa-solid fa-arrow-left me-1"></i> گەڕانەوە
        </a>

        <div class="d-none d-lg-block text-center flex-grow-1">
            <div class="navbar-page-title">{{ __('زانیاری زانکۆ') }}</div>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('admin.universities.edit', $university->id) }}" class="btn btn-sm btn-primary">
                <i class="fa-solid fa-pen-to-square"></i>
            </a>
            <form action="{{ route('admin.universities.destroy', $university->id) }}" method="POST"
                onsubmit="return confirm('دڵنیایت؟');">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger">
                    <i class="fa-solid fa-trash-can"></i>
                </button>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-xl-10 mx-auto">
            {{-- University basic info --}}
            <div class="card glass fade-in mb-4">
                <div class="card-body">
                    <h4 class="card-title mb-4">
                        <i class="fa-solid fa-building-columns me-2"></i> {{ __('زانیاری بنەڕەتی زانکۆ') }}
                    </h4>

                    <div class="table-wrap">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <tbody>
                                    <tr>
                                        <th style="width:260px"><i class="fa-solid fa-hashtag me-1 text-muted"></i> #</th>
                                        <td>{{ $university->id }}</td>
                                    </tr>

                                    <tr>
                                        <th><i class="fa-solid fa-school me-1 text-muted"></i> {{ __('ناوی زانکۆ') }}</th>
                                        <td class="fw-semibold">{{ $university->name }}</td>
                                    </tr>

                                    <tr>
                                        <th><i class="fa-solid fa-map-pin me-1 text-muted"></i> {{ __('پارێزگا') }}</th>
                                        <td>{{ $university->province->name ?? '—' }}</td>
                                    </tr>

                                    <tr>
                                        <th><i class="fa-solid fa-toggle-on me-1 text-muted"></i> {{ __('دۆخ') }}</th>
                                        <td>
                                            @if ($university->status)
                                                <span class="badge bg-success">{{ __('چاڵاک') }}</span>
                                            @else
                                                <span class="badge bg-danger">{{ __('ناچاڵاک') }}</span>
                                            @endif
                                        </td>
                                    </tr>

                                    <tr>
                                        <th><i class="fa-regular fa-calendar-plus me-1 text-muted"></i>
                                            {{ __('دروستکراوە لە') }}</th>
                                        <td>{{ $university->created_at?->format('Y-m-d H:i') ?? '—' }}</td>
                                    </tr>

                                    <tr>
                                        <th><i class="fa-regular fa-clock me-1 text-muted"></i> {{ __('گۆڕدراوە لە') }}
                                        </th>
                                        <td>{{ $university->updated_at?->format('Y-m-d H:i') ?? '—' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Colleges / Institutes of this University --}}
            <div class="card glass fade-in">
                <div class="card-body">
                    <h4 class="card-title mb-4">
                        <i class="fa-solid fa-building me-2"></i> {{ __('کۆلێژ/پەیمانگاکانی ئەم زانکۆیە') }}
                    </h4>

                    {{-- Optional toolbar: counters / future filters --}}
                    <div class="table-toolbar">
                        <span class="chip">
                            <i class="fa-solid fa-database"></i> {{ __('کۆی گشتی:') }} {{ count($colleges) }}
                        </span>
                    </div>

                    <div class="table-wrap">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead>
                                    <tr>
                                        <th style="width:60px">#</th>
                                        <th>{{ __('ناو') }}</th>
                                        <th style="width:120px">{{ __('دۆخ') }}</th>
                                        {{-- هەلبژاردن: ئەگەر خانەی تر هەیە وەکو جۆر/ژمارەی بەشەکان، لێرە زیاد بکە --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($colleges as $index => $college)
                                        <tr>
                                            <td>{{ ++$index }}</td>
                                            <td class="fw-semibold">
                                                <i class="fa-solid fa-building-columns me-1 text-muted"></i>
                                                {{ $college->name }}
                                            </td>
                                            <td>
                                                @if ($college->status)
                                                    <span class="badge bg-success">{{ __('چاڵاک') }}</span>
                                                @else
                                                    <span class="badge bg-danger">{{ __('ناچاڵاک') }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">
                                                <i class="fa-solid fa-circle-info me-1"></i>
                                                {{ __('هیچ کۆلێژ/پەیمانگایەک بۆ ئەم زانکۆیە نەدۆزرایەوە') }}
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
@endsection

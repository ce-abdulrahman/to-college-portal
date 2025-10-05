@extends('website.web.admin.layouts.app')

@section('content')
  <div class="d-flex justify-content-between align-items-center mb-3">
    <a href="{{ route('admin.departments.index') }}" class="btn btn-outline">
      <i class="fa-solid fa-arrow-left me-1"></i> {{ __('گەڕانەوە') }}
    </a>

    <div class="d-none d-lg-block text-center flex-grow-1">
      <div class="navbar-page-title">{{ __('زانیاری بەش') }}</div>
    </div>

    <div class="d-flex gap-2">
      <a href="{{ route('admin.departments.edit', $department->id) }}" class="btn btn-primary">
        <i class="fa-solid fa-pen-to-square me-1"></i>
      </a>
      <form action="{{ route('admin.departments.destroy', $department->id) }}" method="POST" onsubmit="return confirm('{{ __('دڵنیایت؟') }}');">
        @csrf @method('DELETE')
        <button type="submit" class="btn btn-danger">
          <i class="fa-solid fa-trash-can me-1"></i>
        </button>
      </form>
    </div>
  </div>

  <div class="row">
    <div class="col-12 col-xl-10 mx-auto">
      <div class="card glass fade-in">
        <div class="card-body">
          <h4 class="card-title mb-4">
            <i class="fa-solid fa-table-list me-2"></i> {{ __('زانیاری تەواوی بەش') }}
          </h4>

          <div class="table-wrap">
            <div class="table-responsive">
              <table class="table table-bordered align-middle">
                <tbody>
                  <tr>
                    <th style="width:260px"><i class="fa-solid fa-hashtag me-1 text-muted"></i> #</th>
                    <td>{{ $department->id }}</td>
                  </tr>
                  <tr>
                    <th><i class="fa-solid fa-tag me-1 text-muted"></i> {{ __('ناو') }}</th>
                    <td class="fw-semibold">{{ $department->name }}</td>
                  </tr>
                  <tr>
                    <th><i class="fa-solid fa-cube me-1 text-muted"></i> {{ __('سیستەم') }}</th>
                    <td>{{ $department->system->name }}</td>
                  </tr>
                  <tr>
                    <th><i class="fa-solid fa-map-pin me-1 text-muted"></i> {{ __('پارێزگا') }}</th>
                    <td>{{ $department->province->name }}</td>
                  </tr>
                  <tr>
                    <th><i class="fa-solid fa-school me-1 text-muted"></i> {{ __('زانکۆ') }}</th>
                    <td>{{ $department->university->name }}</td>
                  </tr>
                  <tr>
                    <th><i class="fa-solid fa-building-columns me-1 text-muted"></i> {{ __('کۆلێژ/پەیمانگا') }}</th>
                    <td>{{ $department->college->name }}</td>
                  </tr>
                  <tr>
                    <th><i class="fa-solid fa-percent me-1 text-muted"></i> {{ __('ن. ناوەندی') }}</th>
                    <td>{{ $department->local_score ?? '—' }}</td>
                  </tr>
                  <tr>
                    <th><i class="fa-solid fa-percent me-1 text-muted"></i> {{ __('ن. ناوخۆی') }}</th>
                    <td>{{ $department->internal_score ?? '—' }}</td>
                  </tr>
                  <tr>
                    <th><i class="fa-solid fa-layer-group me-1 text-muted"></i> {{ __('جۆر') }}</th>
                    <td>{{ $department->type }}</td>
                  </tr>
                  <tr>
                    <th><i class="fa-solid fa-venus-mars me-1 text-muted"></i> {{ __('ڕەگەز') }}</th>
                    <td>{{ $department->sex ?? '—' }}</td>
                  </tr>
                  <tr>
                    <th><i class="fa-regular fa-calendar-plus me-1 text-muted"></i> {{ __('دروستکراوە لە') }}</th>
                    <td>{{ $department->created_at?->format('Y-m-d H:i') ?? '—' }}</td>
                  </tr>
                  <tr>
                    <th><i class="fa-regular fa-clock me-1 text-muted"></i> {{ __('گۆڕدراوە لە') }}</th>
                    <td>{{ $department->updated_at?->format('Y-m-d H:i') ?? '—' }}</td>
                  </tr>
                  <tr>
                    <th><i class="fa-solid fa-toggle-on me-1 text-muted"></i> {{ __('دۆخ') }}</th>
                    <td>
                      @if ($department->status)
                        <span class="badge bg-success">{{ __('چاڵاک') }}</span>
                      @else
                        <span class="badge bg-danger">{{ __('ناچاڵاک') }}</span>
                      @endif
                    </td>
                  </tr>
                  <tr>
                    <th><i class="fa-solid fa-align-left me-1 text-muted"></i> {{ __('وەسف') }}</th>
                    <td>{!! nl2br(e($department->description)) !!}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <div class="d-flex justify-content-end gap-2 mt-3">
            <a href="{{ route('admin.departments.edit', $department->id) }}" class="btn btn-primary">
              <i class="fa-solid fa-pen-to-square me-1"></i> {{ __('گۆڕین') }}
            </a>
            <a href="{{ route('admin.departments.index') }}" class="btn btn-outline">
              <i class="fa-solid fa-list me-1"></i> {{ __('لیستەکە') }}
            </a>
          </div>

        </div>
      </div>
    </div>
  </div>
@endsection

@extends('website.web.admin.layouts.app')

@section('page_name', 'universities')
@section('view_name', 'edit')

@section('content')
  {{-- ... سەردێر و فرۆم و فیلمەکان ... --}}

  <div class="row">
    <div class="col-12 col-xl-8 mx-auto">
      <div class="card glass fade-in">
        <div class="card-body">
          <h4 class="card-title mb-4"><i class="fa-solid fa-pen-to-square me-2"></i> دەستکاری: {{ $province->name }}</h4>

          {{-- errors --}}
          @if ($errors->any())
            <div class="alert alert-danger">
              <i class="fa-solid fa-circle-exclamation me-1"></i> هەڵە هەیە:
              <ul class="mb-0 mt-2 ps-3">
                @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
              </ul>
            </div>
          @endif

          <form action="{{ route('admin.provinces.update', $province->id) }}" method="POST" class="needs-validation" novalidate enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="row g-3">
              <div class="col-12">
                <label class="form-label">ناوی پارێزگا</label>
                <input type="text" name="name" class="form-control" required value="{{ old('name', $province->name) }}">
                <div class="invalid-feedback">ناو پێویستە.</div>
              </div>

              <div class="col-12">
                <label class="form-label">ناوی پارێزگا (ئینگلیزی)</label>
                <input type="text" name="name_en" class="form-control" required value="{{ old('name_en', $province->name_en) }}">
                <div class="invalid-feedback">ناو پێویستە.</div>
              </div>

              {{-- GeoJSON textarea (هەمان ناو لەفرۆم و JS) --}}
              <div class="col-12">
                <label class="form-label">GeoJSON</label>
                <textarea name="geojson" rows="8" class="form-control">{{ is_array($province->geojson) ? json_encode($province->geojson, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : ($province->geojson ?? '') }}</textarea>
                <div class="form-text">دەتوانیت لێرە paste بکەیت یان لە خوارەوە فایل upload بکەیت.</div>
              </div>

              {{-- Map (تەنها container) --}}
            <div id="map" style="height:420px;border-radius:12px"></div>

              <div class="col-12 col-md-4 mb-3">
                <label class="form-label">Upload GeoJSON (Optional)</label>
                <input type="file" name="geojson_file" class="form-control" accept=".geojson,.json,.txt">
              </div>

              <div class="col-12 col-md-3">
                <label class="form-label">وێنە</label>
                <input type="file" name="image" class="form-control" accept="image/*">
              </div>

              <div class="col-12 col-md-3">
                <label class="form-label">دۆخ</label>
                <select class="form-select" name="status" required>
                  <option value="1" @selected(old('status', (int)$province->status) === 1)>چاڵاک</option>
                  <option value="0" @selected(old('status', (int)$province->status) === 0)>ناچاڵاک</option>
                </select>
                <div class="invalid-feedback">دۆخ دیاری بکە.</div>
              </div>
            </div>

            <div class="d-flex justify-content-end mt-4">
              <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-floppy-disk me-1"></i> پاشەکەوت
              </button>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  {{-- تەنیا فایلێکی خوارەوە پەیوەندیدار بکە (jQuery-based) --}}
  <script src="{{ asset('assets/admin/js/pages/provinces/edit.js') }}"></script>
@endpush

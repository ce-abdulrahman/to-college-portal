@extends('website.web.admin.layouts.app')

@section('content')
  <div class="d-flex justify-content-between align-items-center mb-3">
    <a href="{{ route('admin.universities.index') }}" class="btn btn-outline-success">
      <i class="fa-solid fa-arrow-left me-1"></i> گەڕانەوە
    </a>

    <div class=" d-lg-block text-center flex-grow-1">
      <div class="navbar-page-title" style="font-size: 32px">
        <i class="fa-solid fa-map-pin me-1 text-muted"></i> دروستکردنی زانکۆی نوێ
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-12 col-xl-8 mx-auto">
      <div class="card glass fade-in">
        <div class="card-body">
          <h4 class="card-title mb-4">
            <i class="fa-solid fa-building-columns me-2"></i> {{ __('دروستکردنی زانکۆ') }}
          </h4>

          @if ($errors->any())
            <div class="alert alert-danger">
              <i class="fa-solid fa-circle-exclamation me-1"></i> هەڵە هەیە:
              <ul class="mb-0 mt-2 ps-3">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form action="{{ route('admin.universities.store') }}" method="POST" class="needs-validation" enctype="multipart/form-data" novalidate>
            @csrf

            <div class="row g-3">
              {{-- Province --}}
              <div class="col-md-6">
                <label for="province_id" class="form-label">
                  <i class="fa-solid fa-map-pin me-1 text-muted"></i> {{ __('هەڵبژاردنی پارێزگا') }} <span class="text-danger">*</span>
                </label>
                <select id="province_id" name="province_id" class="form-select" required>
                  <option value="" disabled {{ old('province_id') ? '' : 'selected' }}>هەڵبژێرە...</option>
                  @foreach ($provinces as $province)
                    <option value="{{ $province->id }}" @selected(old('province_id') == $province->id)>{{ $province->name }}</option>
                  @endforeach
                </select>
                <div class="invalid-feedback">تکایە پارێزگا هەڵبژێرە.</div>
              </div>

              {{-- Name --}}
              <div class="col-md-6">
                <label for="name" class="form-label">
                  <i class="fa-solid fa-tag me-1 text-muted"></i> {{ __('ناوی زانکۆ') }} <span class="text-danger">*</span>
                </label>
                <input id="name" name="name" type="text" class="form-control" required minlength="2" placeholder="ناوی زانکۆ..." value="{{ old('name') }}">
                <div class="invalid-feedback">تکایە ناوی دروست بنوسە (کەمتر نیە لە ٢ پیت).</div>
              </div>

              <div class="col-12">
                <label class="form-label">ناوی زانکۆ (ئینگلیزی)</label>
                <input type="text" name="name_en" class="form-control" required placeholder="نموونە: University of Erbil" value="{{ old('name_en') }}">
                <div class="invalid-feedback">ناو پێویستە.</div>
              </div>

              {{-- GeoJSON (Optional) --}}
              <div class="col-12">
                <label class="form-label">GeoJSON (Optional)</label>
                <textarea name="geojson" rows="7" class="form-control" placeholder='{"type":"FeatureCollection","features":[...]'>{{ old('geojson_text') }}</textarea>
                <div class="form-text">دەتوانیت GeoJSON paste بکەیت یان فایل upload بکەیت.</div>
              </div>

              <div class="col-12 col-md-6">
                <label class="form-label">Upload GeoJSON (Optional)</label>
                <input type="file" name="geojson_file" class="form-control" accept=".geojson,.json,.txt">
              </div>

              {{-- Map (Leaflet CSS/JS لە layout بار کراون؛ لێرە دوبارە مەهێنە) --}}
              <div class="col-12">
                <div id="map" style="height:420px;border-radius:12px"></div>
              </div>

              {{-- Point (optional) --}}
              <div class="col-12 col-md-6">
                <label class="form-label">Latitude</label>
                <input id="lat" name="lat" type="number" step="any" class="form-control" value="{{ old('lat') }}">
              </div>
              <div class="col-12 col-md-6">
                <label class="form-label">Longitude</label>
                <input id="lng" name="lng" type="number" step="any" class="form-control" value="{{ old('lng') }}">
              </div>
              <div class="col-12">
                <div class="form-text">لەسەر نەخشە کلیک بکە، lat/lng خۆکار پڕ دەبن.</div>
              </div>

              <div class="col-12 col-md-6">
                <label class="form-label">وێنە</label>
                <input type="file" name="image" class="form-control" accept="image/*">
              </div>

              {{-- Status --}}
              <div class="col-md-12">
                <label for="status" class="form-label">
                  <i class="fa-solid fa-toggle-on me-1 text-muted"></i> {{ __('دۆخ') }} <span class="text-danger">*</span>
                </label>
                <select id="status" name="status" class="form-select" required>
                  <option value="1" @selected(old('status')==='1')>{{ __('چاڵاک') }}</option>
                  <option value="0" @selected(old('status')==='0')>{{ __('ناچاڵاک') }}</option>
                </select>
                <div class="invalid-feedback">تکایە دۆخ هەڵبژێرە.</div>
              </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
              <button type="reset" class="btn btn-outline">
                <i class="fa-solid fa-rotate-left me-1"></i> {{ __('پاککردنەوە') }}
              </button>
              <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-plus me-1"></i> {{ __('پاشەکەوتکردنی زانکۆ') }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script src="{{ asset('assets/admin/js/pages/universities/create.js') }}"></script>
@endpush

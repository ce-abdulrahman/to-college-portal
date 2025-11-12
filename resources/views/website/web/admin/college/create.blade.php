@extends('website.web.admin.layouts.app')

@section('page_name', 'colleges')
@section('view_name', 'create')

@section('content')
  <div class="d-flex justify-content-between align-items-center mb-3">
    <a href="{{ route('admin.colleges.index') }}" class="btn btn-outline-success mb-4">
      <i class="fa-solid fa-arrow-right-long me-1"></i> گەڕانەوە
    </a>

    <div class=" d-lg-block text-center flex-grow-1">
      <div class="navbar-page-title" style="font-size: 32px">
        <i class="fa-solid fa-building-columns me-1 text-muted"></i> دروستکردنی کۆلێژ یان پەیمانگا
      </div>
    </div>
  </div>

  <div data-page="colleges" data-view="create">

      <div class="row">
        <div class="col-12 col-xl-8 mx-auto">
          <div class="card glass fade-in">
            <div class="card-body">
              <h4 class="card-title mb-4">
                <i class="fa-solid fa-building-columns me-2"></i> دروست کردنی کۆلێژ
              </h4>

              @if ($errors->any())
                <div class="alert alert-danger">
                  <i class="fa-solid fa-circle-exclamation me-1"></i> هەڵە هەیە لە داهێنان
                  <ul class="mb-0 mt-2 ps-3">
                    @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                    @endforeach
                  </ul>
                </div>
              @endif

              <form id="collegeForm" action="{{ route('admin.colleges.store') }}" method="POST" class="needs-validation" enctype="multipart/form-data" novalidate>
                @csrf

                {{-- University --}}
                <div class="mb-3">
                  <label for="university_id" class="form-label">هەڵبژاردنی زانکۆ</label>
                  <select id="university_id" name="university_id" class="form-select @error('university_id') is-invalid @enderror" required>
                    <option value="" disabled {{ old('university_id') ? '' : 'selected' }}>هەڵبژێرە...</option>
                    @foreach ($universities as $uni)
                      <option value="{{ $uni->id }}" @selected(old('university_id') == $uni->id)>{{ $uni->name }}</option>
                    @endforeach
                  </select>
                  @error('university_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                {{-- Name --}}
                <div class="mb-3">
                  <label for="name" class="form-label">ناوی کۆلێژ</label>
                  <input id="name" name="name" type="text" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required>
                  @error('name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                <div class="col-12 mb-3">
                  <label class="form-label">ناوی کۆلێژ (ئینگلیزی)</label>
                  <input type="text" name="name_en" class="form-control" required value="{{ old('name_en') }}" placeholder="نموونە: College of Engineering">
                  <div class="invalid-feedback">ناو پێویستە.</div>
                </div>

                {{-- GeoJSON (Optional) --}}
                <div class="mb-3">
                  <label class="form-label">GeoJSON (Optional)</label>
                  <textarea name="geojson_text" rows="6" class="form-control" placeholder='{"type":"FeatureCollection","features":[...]'>{{ old('geojson_text') }}</textarea>
                </div>
                <div class="mb-3">
                  <label class="form-label">Upload GeoJSON (Optional)</label>
                  <input type="file" name="geojson_file" class="form-control" accept=".geojson,.json,.txt">
                </div>

                {{-- Map (Leaflet لە layout) --}}
                <div id="map" class="m-3" style="height:420px;border-radius:12px"></div>

                {{-- Point (optional) --}}
                <div class="row g-3">
                  <div class="col-md-6">
                    <label class="form-label">Latitude</label>
                    <input id="lat" name="lat" type="number" step="any" value="{{ old('lat') }}" class="form-control">
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">Longitude</label>
                    <input id="lng" name="lng" type="number" step="any" value="{{ old('lng') }}" class="form-control">
                  </div>
                  <div class="form-text">لەسەر نەخشە کلیک بکە، lat/lng خۆکار پڕ دەبن.</div>
                </div>

                {{-- Image --}}
                <div class="col-12 col-md-6 my-3">
                  <label class="form-label">وێنە</label>
                  <input type="file" name="image" class="form-control" accept="image/*">
                </div>

                {{-- Status --}}
                <div class="mb-3">
                  <label for="status" class="form-label">دۆخ</label>
                  <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
                    <option value="1" @selected(old('status')==='1')>چاڵاک</option>
                    <option value="0" @selected(old('status')==='0')>ناچاڵاک</option>
                  </select>
                  @error('status') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                <div class="d-flex justify-content-end">
                  <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-floppy-disk me-1"></i> پاشەکەوتکردن
                  </button>
                </div>
              </form>

            </div>
          </div>
        </div>
      </div>
  </div>
@endsection

@push('scripts')
  <script src="{{ asset('assets/admin/js/route-loader.js') }}"></script>
@endpush

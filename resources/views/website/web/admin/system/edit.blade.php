@extends('website.web.admin.layouts.app')

@section('content')
  <div class="d-flex justify-content-between align-items-center mb-3">
    <a href="{{ route('admin.systems.index') }}" class="btn btn-outline-success">
      <i class="fa-solid fa-arrow-left me-1"></i> گەڕانەوە
    </a>
    <div class="d-none d-lg-block text-center flex-grow-1">
      <div class="navbar-page-title">دەستکاری سیستەم</div>
    </div>
  </div>

  <div class="row">
    <div class="col-12 col-xl-8 mx-auto">
      <div class="card glass fade-in">
        <div class="card-body">
          <h4 class="card-title mb-4">
            <i class="fa-solid fa-pen-to-square me-2"></i> دەستکاری سیستەم
          </h4>

          <form action="{{ route('admin.systems.update', $system->id) }}" method="POST" class="needs-validation" novalidate>
            @csrf
            @method('PUT')

            <div class="row g-3">
              {{-- Name --}}
              <div class="col-md-8">
                <label for="name" class="form-label">
                  <i class="fa-solid fa-tag me-1 text-muted"></i> ناوی سیستەم <span class="text-danger">*</span>
                </label>
                <input type="text" id="name" name="name" class="form-control"
                       value="{{ $system->name }}" required minlength="2"
                       placeholder="ناوی سیستەم...">
                <div class="invalid-feedback">تکایە ناوێکی دروست بنوسە (کەمتر نیە لە ٢ پیت).</div>
              </div>

              {{-- Status --}}
              <div class="col-md-4">
                <label for="status" class="form-label">
                  <i class="fa-solid fa-toggle-on me-1 text-muted"></i> دۆخ <span class="text-danger">*</span>
                </label>
                <select id="status" name="status" class="form-select" required>
                  <option value="1" {{ $system->status ? 'selected' : '' }}>چاڵاک</option>
                  <option value="0" {{ !$system->status ? 'selected' : '' }}>ناچاڵاک</option>
                </select>
                <div class="invalid-feedback">تکایە دۆخ هەڵبژێرە.</div>
              </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
              <a href="{{ route('admin.systems.index') }}" class="btn btn-outline">
                <i class="fa-solid fa-xmark me-1"></i> ڕەتکردنەوە
              </a>
              <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-floppy-disk me-1"></i> پاشەکەوتکردنی گۆڕانکاری
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
<script>
(() => {
  const forms = document.querySelectorAll('.needs-validation');
  forms.forEach(form => {
    form.addEventListener('submit', e => {
      if (!form.checkValidity()) {
        e.preventDefault();
        e.stopPropagation();
      }
      form.classList.add('was-validated');
    });
  });
})();
</script>
@endpush

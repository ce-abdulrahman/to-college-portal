@php
    $isEdit = isset($user) && $user;
    $selectedRole = old('role', $isEdit ? $user->role : 'student');
    $center = $isEdit ? $user->center : null;
    $teacher = $isEdit ? $user->teacher : null;
    $student = $isEdit ? $user->student : null;
@endphp

<div class="row g-3">
    <div class="col-md-6">
        <label for="name" class="form-label">ناو <span class="text-danger">*</span></label>
        <input type="text" id="name" name="name" class="form-control"
            value="{{ old('name', $isEdit ? $user->name : '') }}" required>
    </div>

    <div class="col-md-3">
        <label for="code" class="form-label">کۆدی چوونەژوورەوە <span class="text-danger">*</span></label>
        <div class="input-group">
            <input type="text" id="code" name="code" class="form-control"
                value="{{ old('code', $isEdit ? $user->code : '') }}" required>
            <button class="btn btn-outline-secondary" type="button" id="generate-code" title="دروستکردنی کۆدی نوێ">
                <i class="fa-solid fa-rotate"></i>
            </button>
        </div>
    </div>

    <div class="col-md-3">
        <label for="rand_code" class="form-label">Rand Code <span class="text-danger">*</span></label>
        <div class="input-group">
            <input type="number" id="rand_code" name="rand_code" class="form-control"
                value="{{ old('rand_code', $isEdit ? $user->rand_code : '') }}" required>
            <button class="btn btn-outline-secondary" type="button" id="generate-rand-code" title="دروستکردنی کۆدی نوێ">
                <i class="fa-solid fa-rotate"></i>
            </button>
        </div>
    </div>

    <div class="col-md-4">
        <label for="phone" class="form-label">ژمارەی مۆبایل</label>
        <input type="text" id="phone" name="phone" class="form-control"
            value="{{ old('phone', $isEdit ? $user->phone : '') }}">
    </div>

    <div class="col-md-4">
        <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
        <select id="role" name="role" class="form-select" required>
            <option value="admin" @selected($selectedRole === 'admin')>Admin</option>
            <option value="center" @selected($selectedRole === 'center')>Center</option>
            <option value="teacher" @selected($selectedRole === 'teacher')>Teacher</option>
            <option value="student" @selected($selectedRole === 'student')>Student</option>
        </select>
    </div>

    <div class="col-md-4">
        <label for="status" class="form-label">دۆخی ئەکاونت <span class="text-danger">*</span></label>
        <select id="status" name="status" class="form-select" required>
            <option value="1" @selected((string) old('status', $isEdit ? $user->status : '1') === '1')>چاڵاک</option>
            <option value="0" @selected((string) old('status', $isEdit ? $user->status : '1') === '0')>ناچاڵاک</option>
        </select>
    </div>

    <div class="col-md-6">
        <label for="password" class="form-label">
            @if ($isEdit)
                وشەی نهێنی نوێ (ئیختیاری)
            @else
                وشەی نهێنی <span class="text-danger">*</span>
            @endif
        </label>
        <input type="password" id="password" name="password" class="form-control"
            @if (! $isEdit) required @endif>
    </div>

    <div class="col-md-6">
        <label for="password_confirmation" class="form-label">
            @if ($isEdit)
                دووبارەکردنەوەی وشەی نهێنی نوێ
            @else
                دووبارەکردنەوەی وشەی نهێنی <span class="text-danger">*</span>
            @endif
        </label>
        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control"
            @if (! $isEdit) required @endif>
    </div>
</div>

<div id="role-center" class="role-section mt-4 {{ $selectedRole === 'center' ? '' : 'd-none' }}">
    <hr>
    <h5 class="mb-3"><i class="fa-solid fa-building me-2 text-primary"></i>زانیاری Center</h5>

    <div class="row g-3">
        <div class="col-md-4">
            <label for="center_province" class="form-label">پارێزگا <span class="text-danger">*</span></label>
            <select id="center_province" name="center_province" class="form-select">
                <option value="">هەڵبژێرە...</option>
                @foreach ($provinces as $province)
                    <option value="{{ $province->name }}" @selected(old('center_province', data_get($center, 'province')) == $province->name)>
                        {{ $province->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4">
            <label for="center_referral_code" class="form-label">Referral Code</label>
            <input type="text" id="center_referral_code" name="center_referral_code" class="form-control"
                value="{{ old('center_referral_code', data_get($center, 'referral_code', auth()->user()->rand_code)) }}">
        </div>

        <div class="col-md-2">
            <label for="center_limit_teacher" class="form-label">سنووری مامۆستا</label>
            <input type="number" min="0" id="center_limit_teacher" name="center_limit_teacher" class="form-control"
                value="{{ old('center_limit_teacher', data_get($center, 'limit_teacher', 0)) }}">
        </div>

        <div class="col-md-2">
            <label for="center_limit_student" class="form-label">سنووری قوتابی</label>
            <input type="number" min="0" id="center_limit_student" name="center_limit_student" class="form-control"
                value="{{ old('center_limit_student', data_get($center, 'limit_student', 0)) }}">
        </div>

        <div class="col-md-6">
            <label for="center_address" class="form-label">ناونیشان</label>
            <textarea id="center_address" name="center_address" class="form-control" rows="2">{{ old('center_address', data_get($center, 'address')) }}</textarea>
        </div>

        <div class="col-md-6">
            <label for="center_description" class="form-label">وەسف</label>
            <textarea id="center_description" name="center_description" class="form-control" rows="2">{{ old('center_description', data_get($center, 'description')) }}</textarea>
        </div>

        <div class="col-12">
            <label class="form-label d-block">Features</label>
            <div class="d-flex flex-wrap gap-4">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="center_ai_rank" name="center_ai_rank"
                        value="1" @checked((int) old('center_ai_rank', data_get($center, 'ai_rank', 0)) === 1)>
                    <label class="form-check-label" for="center_ai_rank">AI Rank</label>
                </div>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="center_gis" name="center_gis"
                        value="1" @checked((int) old('center_gis', data_get($center, 'gis', 0)) === 1)>
                    <label class="form-check-label" for="center_gis">GIS</label>
                </div>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="center_all_departments" name="center_all_departments"
                        value="1" @checked((int) old('center_all_departments', data_get($center, 'all_departments', 0)) === 1)>
                    <label class="form-check-label" for="center_all_departments">All Departments</label>
                </div>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="center_queue_hand_department" name="center_queue_hand_department"
                        value="1" @checked((int) old('center_queue_hand_department', data_get($center, 'queue_hand_department', 0)) === 1)>
                    <label class="form-check-label" for="center_queue_hand_department">Queue Hand Department</label>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="role-teacher" class="role-section mt-4 {{ $selectedRole === 'teacher' ? '' : 'd-none' }}">
    <hr>
    <h5 class="mb-3"><i class="fa-solid fa-chalkboard-user me-2 text-warning"></i>زانیاری Teacher</h5>

    <div class="row g-3">
        <div class="col-md-4">
            <label for="teacher_province" class="form-label">پارێزگا <span class="text-danger">*</span></label>
            <select id="teacher_province" name="teacher_province" class="form-select">
                <option value="">هەڵبژێرە...</option>
                @foreach ($provinces as $province)
                    <option value="{{ $province->name }}" @selected(old('teacher_province', data_get($teacher, 'province')) == $province->name)>
                        {{ $province->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4">
            <label for="teacher_referral_code" class="form-label">Referral Code</label>
            <input type="text" id="teacher_referral_code" name="teacher_referral_code" class="form-control"
                value="{{ old('teacher_referral_code', data_get($teacher, 'referral_code', auth()->user()->rand_code)) }}">
        </div>

        <div class="col-md-4">
            <label for="teacher_limit_student" class="form-label">سنووری قوتابی</label>
            <input type="number" min="0" id="teacher_limit_student" name="teacher_limit_student" class="form-control"
                value="{{ old('teacher_limit_student', data_get($teacher, 'limit_student', 0)) }}">
        </div>

        <div class="col-12">
            <label class="form-label d-block">Features</label>
            <div class="d-flex flex-wrap gap-4">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="teacher_ai_rank" name="teacher_ai_rank"
                        value="1" @checked((int) old('teacher_ai_rank', data_get($teacher, 'ai_rank', 0)) === 1)>
                    <label class="form-check-label" for="teacher_ai_rank">AI Rank</label>
                </div>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="teacher_gis" name="teacher_gis"
                        value="1" @checked((int) old('teacher_gis', data_get($teacher, 'gis', 0)) === 1)>
                    <label class="form-check-label" for="teacher_gis">GIS</label>
                </div>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="teacher_all_departments" name="teacher_all_departments"
                        value="1" @checked((int) old('teacher_all_departments', data_get($teacher, 'all_departments', 0)) === 1)>
                    <label class="form-check-label" for="teacher_all_departments">All Departments</label>
                </div>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="teacher_queue_hand_department" name="teacher_queue_hand_department"
                        value="1" @checked((int) old('teacher_queue_hand_department', data_get($teacher, 'queue_hand_department', 0)) === 1)>
                    <label class="form-check-label" for="teacher_queue_hand_department">Queue Hand Department</label>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="role-student" class="role-section mt-4 {{ $selectedRole === 'student' ? '' : 'd-none' }}">
    <hr>
    <h5 class="mb-3"><i class="fa-solid fa-user-graduate me-2 text-success"></i>زانیاری Student</h5>

    <div class="row g-3">
        <div class="col-md-3">
            <label for="student_mark" class="form-label">نمرە <span class="text-danger">*</span></label>
            <input type="number" step="0.01" min="0" max="100" id="student_mark" name="student_mark" class="form-control"
                value="{{ old('student_mark', data_get($student, 'mark')) }}">
        </div>

        <div class="col-md-3">
            <label for="student_year" class="form-label">ساڵ <span class="text-danger">*</span></label>
            <input type="number" min="1" id="student_year" name="student_year" class="form-control"
                value="{{ old('student_year', data_get($student, 'year')) }}">
        </div>

        <div class="col-md-3">
            <label for="student_type" class="form-label">لق <span class="text-danger">*</span></label>
            <select id="student_type" name="student_type" class="form-select">
                <option value="زانستی" @selected(old('student_type', data_get($student, 'type')) === 'زانستی')>زانستی</option>
                <option value="وێژەیی" @selected(old('student_type', data_get($student, 'type')) === 'وێژەیی')>وێژەیی</option>
            </select>
        </div>

        <div class="col-md-3">
            <label for="student_gender" class="form-label">ڕەگەز <span class="text-danger">*</span></label>
            <select id="student_gender" name="student_gender" class="form-select">
                <option value="نێر" @selected(old('student_gender', data_get($student, 'gender')) === 'نێر')>نێر</option>
                <option value="مێ" @selected(old('student_gender', data_get($student, 'gender')) === 'مێ')>مێ</option>
            </select>
        </div>

        <div class="col-md-4">
            <label for="student_province" class="form-label">پارێزگا <span class="text-danger">*</span></label>
            <select id="student_province" name="student_province" class="form-select">
                <option value="">هەڵبژێرە...</option>
                @foreach ($provinces as $province)
                    <option value="{{ $province->name }}" @selected(old('student_province', data_get($student, 'province')) == $province->name)>
                        {{ $province->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4">
            <label for="student_referral_code" class="form-label">Referral Code</label>
            <input type="text" id="student_referral_code" name="student_referral_code" class="form-control"
                value="{{ old('student_referral_code', data_get($student, 'referral_code', auth()->user()->rand_code)) }}">
        </div>

        <div class="col-md-4">
            <label for="student_status" class="form-label">دۆخی Student <span class="text-danger">*</span></label>
            <select id="student_status" name="student_status" class="form-select">
                <option value="1" @selected((string) old('student_status', data_get($student, 'status', 1)) === '1')>چاڵاک</option>
                <option value="0" @selected((string) old('student_status', data_get($student, 'status', 1)) === '0')>ناچاڵاک</option>
            </select>
        </div>

        <div class="col-md-4">
            <label for="student_mbti_type" class="form-label">MBTI Type</label>
            <input type="text" id="student_mbti_type" name="student_mbti_type" class="form-control"
                value="{{ old('student_mbti_type', data_get($student, 'mbti_type')) }}" maxlength="4">
        </div>

        <div class="col-md-4">
            <label for="student_lat" class="form-label">Lat</label>
            <input type="number" step="0.0000001" id="student_lat" name="student_lat" class="form-control"
                value="{{ old('student_lat', data_get($student, 'lat')) }}">
        </div>

        <div class="col-md-4">
            <label for="student_lng" class="form-label">Lng</label>
            <input type="number" step="0.0000001" id="student_lng" name="student_lng" class="form-control"
                value="{{ old('student_lng', data_get($student, 'lng')) }}">
        </div>

        <div class="col-12">
            <label class="form-label d-block">Features</label>
            <div class="d-flex flex-wrap gap-4">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="student_ai_rank" name="student_ai_rank"
                        value="1" @checked((int) old('student_ai_rank', data_get($student, 'ai_rank', 0)) === 1)>
                    <label class="form-check-label" for="student_ai_rank">AI Rank</label>
                </div>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="student_gis" name="student_gis"
                        value="1" @checked((int) old('student_gis', data_get($student, 'gis', 0)) === 1)>
                    <label class="form-check-label" for="student_gis">GIS</label>
                </div>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="student_all_departments" name="student_all_departments"
                        value="1" @checked((int) old('student_all_departments', data_get($student, 'all_departments', 0)) === 1)>
                    <label class="form-check-label" for="student_all_departments">All Departments</label>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="d-flex justify-content-end gap-2 mt-4">
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">پاشگەزبوونەوە</a>
    <button type="submit" class="btn btn-primary">{{ $submitLabel ?? 'پاشەکەوتکردن' }}</button>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleSelect = document.getElementById('role');
            const sectionCenter = document.getElementById('role-center');
            const sectionTeacher = document.getElementById('role-teacher');
            const sectionStudent = document.getElementById('role-student');
            const codeInput = document.getElementById('code');
            const randCodeInput = document.getElementById('rand_code');
            const generateCodeBtn = document.getElementById('generate-code');
            const generateRandCodeBtn = document.getElementById('generate-rand-code');
            const studentAiRankInput = document.getElementById('student_ai_rank');
            const studentLatInput = document.getElementById('student_lat');
            const studentLngInput = document.getElementById('student_lng');

            function setRoleRequiredFields(role) {
                const centerFields = ['center_province'];
                const teacherFields = ['teacher_province'];
                const studentFields = ['student_mark', 'student_province', 'student_type', 'student_gender', 'student_year', 'student_status'];

                centerFields.forEach(id => {
                    const field = document.getElementById(id);
                    if (field) field.required = role === 'center';
                });
                teacherFields.forEach(id => {
                    const field = document.getElementById(id);
                    if (field) field.required = role === 'teacher';
                });
                studentFields.forEach(id => {
                    const field = document.getElementById(id);
                    if (field) field.required = role === 'student';
                });
            }

            function toggleRoleSections() {
                const role = roleSelect ? roleSelect.value : 'admin';

                if (sectionCenter) sectionCenter.classList.toggle('d-none', role !== 'center');
                if (sectionTeacher) sectionTeacher.classList.toggle('d-none', role !== 'teacher');
                if (sectionStudent) sectionStudent.classList.toggle('d-none', role !== 'student');

                setRoleRequiredFields(role);
                syncStudentLocationRequired();
            }

            function syncStudentLocationRequired() {
                const role = roleSelect ? roleSelect.value : 'admin';
                const aiRankEnabled = !!(studentAiRankInput && studentAiRankInput.checked);
                const shouldRequire = role === 'student' && aiRankEnabled;

                if (studentLatInput) {
                    studentLatInput.required = shouldRequire;
                }
                if (studentLngInput) {
                    studentLngInput.required = shouldRequire;
                }
            }

            function generateDigits(length) {
                const min = Math.pow(10, length - 1);
                const max = Math.pow(10, length) - 1;
                return Math.floor(Math.random() * (max - min + 1)) + min;
            }

            if (generateCodeBtn && codeInput) {
                generateCodeBtn.addEventListener('click', function() {
                    codeInput.value = generateDigits(6);
                });
            }

            if (generateRandCodeBtn && randCodeInput) {
                generateRandCodeBtn.addEventListener('click', function() {
                    randCodeInput.value = generateDigits(6);
                });
            }

            if (roleSelect) {
                roleSelect.addEventListener('change', toggleRoleSections);
                toggleRoleSections();
            }

            if (studentAiRankInput) {
                studentAiRankInput.addEventListener('change', syncStudentLocationRequired);
            }

            syncStudentLocationRequired();
        });
    </script>
@endpush

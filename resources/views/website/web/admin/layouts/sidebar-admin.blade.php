<nav class="drawer-nav">

                <a href="{{ route('admin.dashboard') }}" class="drawer-nav-item {{ navActive('admin.dashboard') }}">
                    <i class="bi bi-grid-fill"></i><span>ماڵەوە</span>
                </a>


                {{-- General Data Dropdown --}}
                @php
                    $isGeneralDataActive = request()->routeIs('admin.systems.index') ||
                                           request()->routeIs('admin.provinces.index') ||
                                           request()->routeIs('admin.universities.index') ||
                                           request()->routeIs('admin.colleges.index') ||
                                           request()->routeIs('admin.departments.index') ||
                                           request()->routeIs('admin.departments.compare-descriptions') ||
                                           request()->routeIs('admin.queue-hand-departments.index');
                @endphp

                <a href="#sidebarGeneralData" class="drawer-nav-item {{ $isGeneralDataActive ? '' : 'collapsed' }}"
                    data-bs-toggle="collapse" role="button" aria-expanded="{{ $isGeneralDataActive ? 'true' : 'false' }}"
                    aria-controls="sidebarGeneralData">
                    <i class="bi bi-database"></i>
                    <span>داتا سەرەکییەکان</span>
                    <span class="ms-auto"><i class="bi bi-chevron-down"></i></span>
                </a>

                <div class="collapse {{ $isGeneralDataActive ? 'show' : '' }}" id="sidebarGeneralData">
                    <div class="ps-3">
                        <a href="{{ route('admin.systems.index') }}"
                            class="drawer-nav-item {{ navActive('admin.systems.index') }}">
                            <i class="bi bi-dot"></i>
                            <span>سیستەمەکان</span>
                        </a>
                        <a href="{{ route('admin.provinces.index') }}"
                            class="drawer-nav-item {{ navActive('admin.provinces.index') }}">
                            <i class="bi bi-dot"></i>
                            <span>پارێزگاکان</span>
                        </a>
                        <a href="{{ route('admin.universities.index') }}"
                            class="drawer-nav-item {{ navActive('admin.universities.index') }}">
                            <i class="bi bi-dot"></i>
                            <span>زانکۆکان</span>
                        </a>
                        <a href="{{ route('admin.colleges.index') }}"
                            class="drawer-nav-item {{ navActive('admin.colleges.index') }}">
                            <i class="bi bi-dot"></i>
                            <span>کۆلێژەکان</span>
                        </a>
                        <a href="{{ route('admin.departments.index') }}"
                            class="drawer-nav-item {{ navActive('admin.departments.index') }}">
                            <i class="bi bi-dot"></i>
                            <span>بەشەکانی</span>
                        </a>
                        <a href="{{ route('admin.departments.compare-descriptions') }}"
                            class="drawer-nav-item {{ navActive('admin.departments.compare-descriptions') }}">
                            <i class="bi bi-dot"></i>
                            <span>بەراوردی وەسف</span>
                        </a>
                    </div>
                </div>

                <a href="{{ route('admin.queue-hand-departments.index') }}"
                    class="drawer-nav-item back-btn {{ navActive('admin.queue-hand-departments.index') }}">
                    <i class="bi bi-patch-check-fill"></i>
                    <span>ڕێزبەندی قوتابیان</span>
                </a>

                <a href="{{ route('admin.mbti.questions.index') }}"
                    class="drawer-nav-item back-btn {{ navActive('admin.mbti.questions.index') }}">
                    <i class="bi bi-bar-chart"></i>
                    <span>پرسیاری MBTI</span>
                </a>

                <a href="{{ route('admin.mbti.results.index') }}"
                    class="drawer-nav-item back-btn {{ navActive('admin.mbti.results.index') }}">
                    <i class="bi bi-bar-chart"></i>
                    <span>ئەنجامی پرسیاری MBTI</span>
                </a>

                {{-- AI Ranking Dropdown --}}
                @php
                    $isAIActive = request()->routeIs('admin.ai.questions.index') ||
                                  request()->routeIs('admin.ai.results');
                @endphp

                <a href="#sidebarAI" class="drawer-nav-item {{ $isAIActive ? '' : 'collapsed' }}"
                    data-bs-toggle="collapse" role="button" aria-expanded="{{ $isAIActive ? 'true' : 'false' }}"
                    aria-controls="sidebarAI">
                    <i class="fa-solid fa-brain"></i>
                    <span>سیستەمی AI</span>
                    <span class="ms-auto"><i class="bi bi-chevron-down"></i></span>
                </a>

                <div class="collapse {{ $isAIActive ? 'show' : '' }}" id="sidebarAI">
                    <div class="ps-3">
                        <a href="{{ route('admin.ai.questions.index') }}"
                            class="drawer-nav-item {{ navActive('admin.ai.questions.index') }}">
                            <i class="bi bi-dot"></i>
                            <span>پرسیارەکان</span>
                        </a>
                        <a href="{{ route('admin.ai.results') }}"
                            class="drawer-nav-item {{ navActive('admin.ai.results') }}">
                            <i class="bi bi-dot"></i>
                            <span>وەڵامەکانی قوتابیان</span>
                        </a>
                    </div>
                </div>

                @php
                    $pendingCount = \App\Models\RequestMoreDepartments::where('status', 'pending')->count();
                @endphp

                <a href="{{ route('admin.requests.index') }}"
                    class="drawer-nav-item back-btn {{ navActive('admin.requests.index') }}">
                    <i class="bi bi-bell"></i>
                    <span>پرسەکانی بەشەکانی</span>
                    @if($pendingCount > 0)
                <span class="badge bg-danger ms-1">{{ $pendingCount }}</span>
                @endif
                </a>

                <a href="{{ route('admin.backups.index') }}"
                    class="drawer-nav-item back-btn {{ navActive('admin.backups.index') }}">
                    <i class="bi bi-cloud-arrow-up"></i>
                    <span>Backup داتابەیس</span>
                </a>





                <a href="{{ route('admin.results.index') }}"
                    class="drawer-nav-item back-btn {{ navActive('admin.results.index') }}">
                    <i class="fa-solid fa-receipt"></i>
                    <span>ئەنجامی هەڵبژاردنەکانی قوتابیان</span>
                </a>

                <a href="{{ route('admin.settings.index') }}"
                    class="drawer-nav-item back-btn {{ navActive('admin.settings.index') }}">
                    <i class="fa-solid fa-gear"></i>
                    <span>ڕێکخستنەکان</span>
                </a>



                {{-- General Data Dropdown --}}
                @php
                    $isUsersActive = request()->routeIs('admin.centers.index') ||
                                           request()->routeIs('admin.teachers.index') ||
                                           request()->routeIs('admin.students.index') ||
                                           request()->routeIs('admin.users.index') ||
                                           request()->routeIs('admin.users.deleted') ||
                                           request()->routeIs('admin.users.admin-referrals');
                @endphp

                <a href="#sidebarUsers" class="drawer-nav-item {{ $isUsersActive ? '' : 'collapsed' }}"
                    data-bs-toggle="collapse" role="button" aria-expanded="{{ $isUsersActive ? 'true' : 'false' }}"
                    aria-controls="sidebarUsers">
                    <i class="bi bi-people"></i>
                    <span>بەکارهێنەرەکان</span>
                    <span class="ms-auto"><i class="bi bi-chevron-down"></i></span>
                </a>

                <div class="collapse {{ $isUsersActive ? 'show' : '' }}" id="sidebarUsers">
                    <div class="ps-3">
                        <a href="{{ route('admin.centers.index') }}"
                            class="drawer-nav-item {{ navActive('admin.centers.index') }}">
                            <i class="bi bi-dot"></i>
                            <span>سەنتەرەکان</span>
                        </a>

                        <a href="{{ route('admin.teachers.index') }}"
                            class="drawer-nav-item {{ navActive('admin.teachers.index') }}">
                            <i class="bi bi-dot"></i>
                            <span>مامۆستایەکان</span>
                        </a>

                        <a href="{{ route('admin.students.index') }}"
                            class="drawer-nav-item {{ navActive('admin.students.index') }}">
                            <i class="bi bi-dot"></i>
                            <span>قوتابیان</span>
                        </a>
                        <a href="{{ route('admin.users.index') }}"
                            class="drawer-nav-item {{ navActive('admin.users.index') }}">
                            <i class="bi bi-dot"></i>
                            <span>بەکارهێنەر</span>
                        </a>
                        <a href="{{ route('admin.users.deleted') }}"
                            class="drawer-nav-item {{ navActive('admin.users.deleted') }}">
                            <i class="bi bi-dot"></i>
                            <span>سڕاوەکان</span>
                        </a>
                        <a href="{{ route('admin.users.admin-referrals') }}"
                            class="drawer-nav-item {{ navActive('admin.users.admin-referrals') }}">
                            <i class="bi bi-dot"></i>
                            <span>سەر بە ئەدمین</span>
                        </a>
                    </div>
                </div>




            </nav>

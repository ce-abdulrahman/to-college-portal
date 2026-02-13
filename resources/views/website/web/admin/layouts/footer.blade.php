<footer class="footer mt-5 py-3 bg-dark text-light">
    <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center">
        <div class="footer-logo mb-2 mb-md-0">
            <i class="fa-solid fa-graduation-cap me-2 text-primary"></i>
            <span class="fw-bold">بۆ کۆلێژ  </span>
        </div>

        @php
            $copyright = $appSettings['copyright'] ?? null;
            $socialAccounts = [];
            if (!empty($appSettings['social_accounts'])) {
                $socialAccounts = json_decode($appSettings['social_accounts'], true) ?: [];
            }
            if (empty($socialAccounts)) {
                $socialAccounts = [
                    ['name' => 'Facebook', 'icon' => 'fab fa-facebook-f', 'url' => 'https://www.facebook.com/AghaAS7421'],
                    ['name' => 'Telegram', 'icon' => 'fab fa-telegram-plane', 'url' => 'https://t.me/AGHA_ACE'],
                    ['name' => 'Whatsapp', 'icon' => 'fab fa-whatsapp', 'url' => 'https://wa.me/9647504342452'],
                    ['name' => 'Instagram', 'icon' => 'fab fa-instagram', 'url' => 'https://www.instagram.com/agha_ace'],
                    ['name' => 'Viber', 'icon' => 'fab fa-viber', 'url' => 'viber://chat?number=9647504342452'],
                ];
            }
        @endphp

        @if ($copyright)
            <p class="mb-2 mb-md-0">&copy; <span class="me fx-text fx-extrude">{{ $copyright }}</span></p>
        @else
            <p class="mb-2 mb-md-0">&copy;
                مافی ئەم سیستەمە پارێزاوە بۆ،
                <span class="me fx-text fx-extrude">ئەندازیار عبدالرحمن</span>
            </p>
        @endif

        <div class="social-links d-flex gap-3">
            @foreach ($socialAccounts as $social)
                <a href="{{ $social['url'] ?? '#' }}" target="_blank" class="text-light" data-bs-toggle="tooltip"
                    title="{{ $social['name'] ?? '' }}">
                    <i class="{{ $social['icon'] ?? 'fa-solid fa-link' }}"></i>
                </a>
            @endforeach
        </div>
    </div>
</footer>


<style>
    @font-face {
        font-family: 'NizarNastaliqKurdish';
        src: url('../fonts/ku/3_NRT-Bd.ttf') format('truetype');
        font-weight: normal;
        font-style: normal;
    }
    .myname {
        font-family: 'NizarNastaliqKurdish', sans-serif;
        color: #007bff;
    }
</style>

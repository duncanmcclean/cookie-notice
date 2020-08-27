<div class="cookies-fixed cookies-bottom-0 md:cookies-right-0">
    <form id="cookiesForm" class="cookies-bg-white cookies-rounded-lg cookies-mx-6 cookies-my-6 cookies-p-6 md:cookies-w-1/3 cookies-float-right" action="{{ route('statamic.cookie-notice.update') }}" method="POST" style="display: none">
        <h2 class="cookies-font-semibold cookies-text-2xl cookies-mb-1">This site uses cookies</h2>
        <p class="cookies-text-sm">We use cookies on this site so we can provide you with personalised content, ads and to analyse our website's traffic. By continuing to use this website, you consent to cookies.</p>

        @csrf

        <div class="cookies-flex cookies-flex-col cookies-my-2">
            @foreach($groups as $group)
                <label class="cookis-mb-1" for="{{ $group['slug'] }}">
                    <input
                        id="{{ $group['slug'] }}"
                        type="checkbox"
                        name="{{ $group['slug'] }}"
                        @if($group['toggle_by_default'] || $group['required']) checked value="on" @endif
                        @if($group['required']) required value="on" onclick="this.checked = true" @endif
                        @if($group['consented']) value="on" checked @endif
                    >
                    {{ $group['name'] }}
                    @if($group['required'])
                        <span class="cookies-text-red-600 cookies-text-xs">required</span>
                    @endif
                </label>
            @endforeach
        </div>

        <div class="cookies-flex cookies-flex-row cookies-items-center">
            <button class="cookies-bg-blue-500 hover:cookies-bg-blue-600 cookies-rounded cookies-text-center cookies-text-white cookies-px-6 cookies-py-2 focus:cookies-outline-none" type="submit">Accept</button>
            @if ($hasConsented)
                <button id="hideConsentFormButton" class="cookies-ml-4 cookies-text-gray-800 cookies-text-sm focus:cookies-outline-none" type="button">Hide</button>
            @endif
        </div>
    </form>

    <button id="manageCookiesButton" class="cookies-bg-white cookies-rounded-lg cookies-mr-2 cookies-mb-2 cookies-p-2 cookies-float-right focus:cookies-outline-none">Manage Cookies</button>
</div>

<script>
    const cookiesForm = document.getElementById('cookiesForm')
    const hideConsentFormButton = document.getElementById('hideConsentFormButton')
    const manageCookiesButton = document.getElementById('manageCookiesButton')

    function showConsentNotice() {
        manageCookiesButton.setAttribute('style', 'display: none;')
        cookiesForm.removeAttribute('style')
    }

    function hideConsentNotice() {
        manageCookiesButton.removeAttribute('style')
        cookiesForm.setAttribute('style', 'display: none;')
    }

    @if (! $hasConsented)
        window.addEventListener('load', showConsentNotice)
    @endif
    if (hideConsentFormButton) ideConsentFormButton.addEventListener('click', hideConsentNotice)
    manageCookiesButton.addEventListener('click', showConsentNotice)
</script>

<link rel="stylesheet" href="/vendor/cookie-notice/css/cookie-notice.css">

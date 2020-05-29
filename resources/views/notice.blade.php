<link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">

<div class="fixed bottom-0 right-0">
    <form id="cookiesForm" class="bg-white rounded-lg mr-6 mb-6 p-6 md:w-1/3 float-right" action="{{ route('statamic.cookie-notice.update') }}" method="POST" style="display: none">
        <h2 class="font-semibold text-2xl mb-1">This site uses cookies</h2>
        <p class="text-sm">We use cookies on this site so we can provide you with personalised content, ads and to analyse our website's traffic. By continuing to use this website, you consent to cookies.</p>

        @csrf

        <div class="flex flex-col my-2">
            @foreach($groups as $group)
                <label class="mb-1" for="{{ $group['slug'] }}">
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
                        <span class="text-red-600 text-xs">required</span> 
                    @endif
                </label>
            @endforeach
        </div>

        <div class="flex flex-row items-center">
            <button class="bg-blue-500 hover:bg-blue-600 rounded text-center text-white px-6 py-2 focus:outline-none" type="submit">Accept</button>
            @if ($hasConsented)
                <button id="hideConsentFormButton" class="ml-4 text-gray-800 text-sm focus:outline-none" type="button">Hide</button>
            @endif
        </div>
    </form>

    <button id="manageCookiesButton" class="bg-white rounded-lg mr-2 mb-2 p-2 float-right focus:outline-none">Manage Cookies</button>
</div>

<script>
    const cookiesForm = document.getElementById('cookiesForm');
    const hideConsentFormButton = document.getElementById('hideConsentFormButton');
    const manageCookiesButton = document.getElementById('manageCookiesButton');

    function showConsentNotice() {
        manageCookiesButton.setAttribute('style', 'display: none;');
        cookiesForm.removeAttribute('style');
    }

    function hideConsentNotice() {
        manageCookiesButton.removeAttribute('style');
        cookiesForm.setAttribute('style', 'display: none;');
    }

    @if (! $hasConsented)
        window.addEventListener('load', showConsentNotice);
    @endif
    hideConsentFormButton.addEventListener('click', hideConsentNotice);
    manageCookiesButton.addEventListener('click', showConsentNotice);
</script>
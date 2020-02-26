<div class="cookie-notice">
    <div class="cookie-notice-container">
      <span class="cookie-notice-message">
        {!! $noticeText !!}
    </span>

        <button class="cookie-notice-button">
            Allow cookies
        </button>
    </div>
</div>

@if($noticeStyles === false)
    <style>
        .cookie-notice {
            position: fixed;
            @if($noticeLocation === 'bottom')
                bottom: 0px;
            @else
                top: 0px;
            @endif
            width: 100%;
            background-color: #4A5568;
            padding: 1rem;
        }

        .cookie-notice .cookie-notice-container {
            width: 90%;
            margin: auto;

            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
        }

        .cookie-notice .cookie-notice-message {
            color: #ffffff;
            font-weight: 600;
        }

        .cookie-notice .cookie-notice-button {
            border: none;
            background-color: #A0AEC0;
            padding: 10px;
            cursor: pointer;
            color: #ffffff;
            font-weight: 800;
        }

        .cookie-notice .cookie-notice-button:hover {
            background-color: #718096;
        }
    </style>
@endif

<script>
    window.cookieNotice = (function () {
        const COOKIE_VALUE = 1;
        const COOKIE_DOMAIN = '{{ $domainName }}';
        const COOKIE_NAME = '{{ $cookieName }}';

        function consentWithCookies() {
            setCookie(COOKIE_NAME, COOKIE_VALUE, '365 * 20');
            hideCookieDialog();
        }

        function cookieExists(name) {
            return (document.cookie.split('; ').indexOf(name + '=' + COOKIE_VALUE) !== -1);
        }

        function hideCookieDialog() {
            const dialogs = document.getElementsByClassName('cookie-notice');

            for (let i = 0; i < dialogs.length; ++i) {
                dialogs[i].style.display = 'none';
            }
        }

        function setCookie(name, value, expirationInDays) {
            const date = new Date();
            date.setTime(date.getTime() + (expirationInDays * 24 * 60 * 60 * 1000));

            document.cookie = name + '=' + value
                + ';expires=' + date.toUTCString()
                + ';domain=' + COOKIE_DOMAIN
                + ';path=/';
        }

        if (cookieExists(COOKIE_NAME)) {
            hideCookieDialog();
        }

        const buttons = document.getElementsByClassName('cookie-notice-button');

        for (let i = 0; i < buttons.length; ++i) {
            buttons[i].addEventListener('click', consentWithCookies);
        }

        return {
            consentWithCookies: consentWithCookies,
            hideCookieDialog: hideCookieDialog
        };
    })();
</script>

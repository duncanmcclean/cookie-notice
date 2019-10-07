<div id="notice" class="cookie-notice" style="display: none;">
    <div class="cookie-notice-container">
        <p class="cookie-notice-text">{!! $text !!}</p>
        <div class="cookie-notice-buttons">
            <button class="cookie-notice-accept" onclick="cookieNoticeAccept()">Accept</button>

            @if($dontAccept === true)
                <a class="cookie-notice-do-not-accept" href="https://google.com">Don't accept</a>
            @endif
        </div>
    </div>
</div>

@if($styles != true)
<style>
    .cookie-notice {
        background-color: #ffffff;
        @if($location === "bottom")
        bottom: 0px;
        @else
        top: 0px;
        @endif
        left: 0px;
        right: 0px;
        position: fixed;
        padding: 4px;
        font-family: sans-serif;
    }

    .cookie-notice-container {
        width: 60%;
        margin: auto;
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
    }

    .cookie-notice-accept {
        background-color: #3490DC;
        text-align: center;
        color: white;
        padding: 5px;
        margin: 2px;
        border-radius: 5px;
    }

    .cookie-notice-accept:hover {
        background-color: #6CB2EB;
    }

    .cookie-notice-do-not-accept {

    }
</style>
@endif

<script>
    var cookieNoticeName = '{{ $cookieName }}';

    document.addEventListener("DOMContentLoaded", function(event) {
        var cookie = document.cookie.indexOf(cookieNoticeName);

        if (cookie != 0) {
            document.getElementById('notice').removeAttribute('style');
        }
    });

    const allowsTracking = () => {
        const dnt =
            window.doNotTrack ||
            navigator.doNotTrack ||
            navigator.msDoNotTrack;

        if (dnt === 1 || dnt === '1' || dnt === 'yes') {
            return false
        }

        if ('msTrackingProtectionEnabled' in window.external) {
            return !window.external.msTrackingProtectionEnabled()
        }

        return true;
    };

    function cookieNoticeAccept() {
        var date = new Date();
        date.setTime(date.getTime() + (90*24*60*60*1000));
        var expires = "expires=" + date.toUTCString();
        document.cookie = cookieNoticeName + "=" + "accepted" + "; " + expires;

        window.doNotTrack = true;

        document.getElementById('notice').setAttribute('style', 'display: none;');
    }
</script>

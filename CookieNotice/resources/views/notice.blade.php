<div id="notice" class="cookie-notice" style="display: none;">
    <div class="cookie-notice-container">
        <p class="cookie-notice-text">{!! $text !!}</p>
        <div class="cookie-notice-buttons">
            <button class="cookie-notice-accept" onclick="cookieNoticeAccept()">Accept</button>
        </div>
    </div>
</div>

@if($styles != true)
<style>
    .cookie-notice {
        background-color: #ffffff;
        bottom: 0px;
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
</style>
@endif

<script>
    // On page load, show if the user has not accepted the cookie notice
    document.addEventListener("DOMContentLoaded", function(event) {
        var cookie = document.cookie.indexOf('cookie-notice');

        if (cookie != 0) {
            document.getElementById('notice').removeAttribute('style');
        }
    });

    // Don't run external scripts until user accepts cookies
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

        return true
    };

    // Accept cookies button
    function cookieNoticeAccept() {
        var date = new Date();
        date.setTime(date.getTime() + (90*24*60*60*1000));
        var expires = "expires=" + date.toUTCString();
        document.cookie = "cookie-notice" + "=" + "accepted" + "; " + expires;

        document.getElementById('notice').setAttribute('style', 'display: none;');
    }
</script>
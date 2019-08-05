<div id="notice" class="cookie-notice">
    <div class="cookie-notice-container">
        <p class="cookie-notice-text">{!! $text !!}</p>
        <div class="cookie-notice-buttons">
            <button class="cookie-notice-button" id="accept">Accept</button>
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

    .cookie-notice-button {
        background-color: #3490DC;
        text-align: center;
        color: white;
        padding: 5px;
        margin: 2px;
        border-radius: 5px;
    }

    .cookie-notice-button:hover {
        background-color: #6CB2EB;
    }
</style>
@endif

<script>
    function acceptCookie() {
        var date = new Date();
        date.setTime(date.getTime() + (90*24*60*60*1000));
        var expires = "expires="+date.toUTCString();

        document.cookie = "cookieNotice" + "=" + "accepted" + "; " + expires;
        checkCookie();
    }

    function checkCookie() {
        if (document.cookie.indexOf('cookieNotice') != -1) {
            document.getElementById('notice').innerHTML = '';
        }
    }

    document.addEventListener("DOMContentLoaded", function(event) { 
        checkCookie()
    });

    document.getElementById('accept').onclick = acceptCookie;
</script>
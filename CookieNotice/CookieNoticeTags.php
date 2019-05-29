<?php

namespace Statamic\Addons\CookieNotice;

use Statamic\Extend\Tags;

class CookieNoticeTags extends Tags
{
    public function __construct()
    {
        $this->styles = $this->getConfig('styles', false);
        $this->text = $this->getConfig('text', 'In order to use this website, you must accept cookies.');
    }

    public function index()
    {
        $html = "<!-- Start of Cookie Notice addon -->
                <div id=\"notice\" class=\"cookie-notice\">
                    <div class=\"cookie-notice-container\">
                        <p class=\"cookie-notice-text\">" . $this->text . "</p>
                        <div class=\"cookie-notice-buttons\">
                            <button class=\"cookie-notice-button\" id=\"accept\">Accept</button>
                        </div>
                    </div>
                </div>";

        $css = "<style>
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
                </style>";
        
        $js = "<script>
                function acceptCookie() {
                    document.cookie = \"cookienotice=accepted;\";
                    checkCookie()
                }

                function checkCookie() {
                    if (document.cookie.indexOf('cookienotice') != -1) {
                        document.getElementById('notice').innerHTML = '';
                    }
                }

                document.addEventListener(\"DOMContentLoaded\", function(event) { 
                    checkCookie()
                });

                document.getElementById('accept').onclick = acceptCookie;
            </script>
            <!-- End of Cookie Notice addon -->";

        if($this->styles === true) {
            return $html . $js;
        } else {
            return $html . $css . $js;
        }
    }
}

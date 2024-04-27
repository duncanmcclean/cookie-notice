window.CookieNotice = {
    widget: null,
    config: {},

    listeners: [],
    widgetIsVisible: false,

    boot(widget, config) {
        this.widget = widget
        this.config = config

        this.initListeners()
        this.initPreferences()

        this.widget.querySelector('[data-save-preferences]').addEventListener('click', this.savePreferences.bind(this))
        document.querySelector('[data-show-cookie-notice-widget]')?.addEventListener('click', this.showWidget.bind(this))
    },

    hideWidget() {
        this.widgetIsVisible = false
        this.widget.style.display = 'none'
    },

    showWidget() {
        this.widgetIsVisible = true
        this.widget.style.display = 'block'
    },

    /**
     * Initializes the script event listeners, to execute & disable scripts based on the user's consent preferences.
     */
    initListeners() {
        this.on('accepted', (consentGroup) => {
            document.querySelectorAll(`[data-consent-group="${consentGroup}"]`).forEach((script) => {
                let scriptContent = script.innerHTML
                script.remove()

                let newScript = document.createElement('script')
                newScript.innerHTML = scriptContent
                newScript.setAttribute('data-consent-group', consentGroup)
                document.head.appendChild(newScript)
            })
        })

        this.on('declined', (consentGroup) => {
            document.querySelectorAll(`[data-consent-group="${consentGroup}"]`).forEach((script) => {
                script.setAttribute('type', 'text/plain')
            })
        })
    },

    /**
    * Reads the user's preference cookie & dispatches the relevant events.
    */
    initPreferences() {
        console.log(this.listeners)

        if (this.cookieExists(this.config.cookie_name)) {
            this.hideWidget()
            let preferences = JSON.parse(this.getCookie(this.config.cookie_name))

            this.config.consent_groups.forEach((consentGroup) => {
                let preference = preferences.find((preference) => preference.handle === consentGroup.handle)

                if (preference) {
                    this.widget.querySelector(`[name="group-${consentGroup.handle}"]`).checked = preference.value

                    preference.value
                        ? this.dispatchEvent('accepted', consentGroup.handle)
                        : this.dispatchEvent('declined', consentGroup.handle)
                }
            });
        } else {
            this.config.consent_groups
                .filter((consentGroup) => consentGroup.enable_by_default)
                .forEach((consentGroup) => this.widget.querySelector(`[name="group-${consentGroup.handle}"]`).checked = true)
        }
    },

    /**
    * Saves the user's preferences to a cookie & dispatches the relevant events.
    */
    savePreferences() {
        let oldPreferences = this.cookieExists(this.config.cookie_name)
            ? JSON.parse(this.getCookie(this.config.cookie_name))
            : this.config.consent_groups.map((consentGroup) => {
                return {
                    handle: consentGroup.handle,
                    value: false
                }
            })

        let preferences = this.config.consent_groups.map((consentGroup) => {
            return {
                handle: consentGroup.handle,
                value: this.widget.querySelector(`[name="group-${consentGroup.handle}"]`).checked ? true : false
            }
        })

        this.dispatchEvent('preferences_updated', preferences)

        preferences.forEach((preference) => {
            let oldPreference = oldPreferences.find((oldPreference) => oldPreference.handle === preference.handle)

            if (! oldPreference) {
                oldPreference = {
                    handle: preference.handle,
                    value: false
                }
            }

            if (oldPreference.value !== preference.value) {
                if (preference.value === true) {
                    this.dispatchEvent('accepted', preference.handle)
                }

                if (preference.value === false) {
                    this.dispatchEvent('declined', preference.handle)
                }
            }
        })

        this.setCookie(this.config.cookie_name, JSON.stringify(preferences), this.config.cookie_expiry)

        this.hideWidget()
    },

    on(event, callback) {
        this.listeners.push({
            event: event,
            callback: callback,
        })
    },

    dispatchEvent(event, payload) {
        this.listeners
            .filter((listener) => listener.event === event)
            .forEach((listener) => listener.callback(payload))
    },

    cookieExists(name) {
        return document.cookie.indexOf(name + '=') !== -1
    },

    getCookie(name) {
        const value = `; ${document.cookie}`
        const parts = value.split(`; ${name}=`)
        if (parts.length === 2) return parts.pop().split(';').shift()
    },

    setCookie(name, value, expirationInDays) {
        const date = new Date()
        date.setTime(date.getTime() + (expirationInDays * 24 * 60 * 60 * 1000))

        document.cookie = name + '=' + value +
            ';expires=' + date.toUTCString() +
            `;domain=${this.config.session.domain}` +
            `;path=/` + (this.config.session.secure ? ';secure' : '') +
            (this.config.session.same_site ? `;samesite=${this.config.session.same_site}` : '')
    },
}

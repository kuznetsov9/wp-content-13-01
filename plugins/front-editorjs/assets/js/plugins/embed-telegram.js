// Logic Telegram Embed (custom for Front EditorJS -- Esenin WP)

class Telegram {
    constructor({ data }) {
        this.data = data;
        this.wrapper = undefined;
        this.iframe = undefined; 
    }

    render() {
        this.wrapper = document.createElement('div');
        this.wrapper.className = 'editor-telegram';
        if (this.data?.url) {
            this._createEmbed(this.data.url);
        }
        return this.wrapper;
    }

    save(blockContent) {
        return {
            url: this.data.url
        };
    }

    _createEmbed(url) {
        const postId = url.match(/^https?:\/\/t\.me\/(?:s\/)?(.+)/)?.[1].split('?')[0];
        if (document.querySelector(`#telegram-post-${postId.replace('/', '-')}`)) return;

        const iframeContainer = document.createElement('div');
        iframeContainer.style.position = 'relative';
        iframeContainer.style.width = '100%';
        this.wrapper.innerHTML = ''; 

        this.iframe = document.createElement('iframe');
        this.iframe.id = `telegram-post-${postId.replace('/', '-')}`;
        this.iframe.src = `${url}?embed=1`;
        this.iframe.width = '100%';
        this.iframe.frameBorder = '0';
        this.iframe.scrolling = 'no';
        this.iframe.style.overflow = 'hidden';
        this.iframe.style.border = 'none';
        this.iframe.style.minWidth = '320px';

        window.addEventListener('message', event => {
            if (event.origin !== 'https://t.me') return;
            const data = JSON.parse(event.data);
            if (data.event === 'resize' && data.height && event.source === this.iframe.contentWindow) { 
                this.iframe.style.height = `${data.height}px`;
            }
        });

        iframeContainer.appendChild(this.iframe);
        this.wrapper.appendChild(iframeContainer);

        const script = document.createElement('script');
        script.src = 'https://telegram.org/js/telegram-widget.js?22';
        script.async = true;
        script.dataset.telegramPost = postId;
        script.dataset.width = '100%';

        this.wrapper.appendChild(script);
    }

    static get pasteConfig() {
        return {
            patterns: {
                link: /^https?:\/\/t\.me\/(?:s\/)?[A-Za-z0-9_]{5,}\/\d+/
            }
        };
    }

    onPaste(event) {
        this.data.url = event.detail.data;
        this._createEmbed(event.detail.data);
    }
}
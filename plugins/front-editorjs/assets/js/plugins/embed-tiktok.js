// Logic TikTok Embed (custom for Front EditorJS -- Esenin WP)

class TikTok {
    constructor({ data }) {
        this.data = data;
        this.wrapper = undefined;
    }

    render() {
        this.wrapper = document.createElement('div');
        this.wrapper.className = 'editor-tiktok';

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
        const embedUrl = `https://www.tiktok.com/oembed?url=${encodeURIComponent(url)}`;

        fetch(embedUrl)
            .then(response => response.json())
            .then(data => {
                this.wrapper.innerHTML = data.html;
                const script = document.createElement('script');
                script.src = 'https://www.tiktok.com/embed.js';
                script.async = true;
                this.wrapper.appendChild(script);
            })
            .catch(error => {
                console.error('Error fetching TikTok embed:', error);
            });
    }

    static get pasteConfig() {
        return {
            patterns: {
                link: /^https?:\/\/www\.tiktok\.com\/@[\w.-]+\/video\/\d+(\?.*)?$/
            }
        };
    }

    onPaste(event) {
        this.data.url = event.detail.data;
        this._createEmbed(event.detail.data);
    }
}

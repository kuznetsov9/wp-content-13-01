window.onload = function() {

    // Определяем textarea, куда будем писать результат
    const textToSend = document.getElementById('cont');

    // 1. HTML в JSON (Парсинг исходного контента)
    // Используем защищенный вызов: если функции нет или она вернет ошибку, берем пустой массив
    let jData = { blocks: [] };
    if (typeof stringToBlocks === 'function') {
        try {
            jData = stringToBlocks(textToSend.value);
        } catch (e) {
            console.error("Ошибка при парсинге HTML в блоки:", e);
        }
    }

    // --- ВСТАВЛЯЕМ ЭТОТ БЛОК (САНИТАРНЫЙ КОРДОН) ---
    // Чистим входящие данные перед инициализацией редактора
    if (jData && jData.blocks && typeof DOMPurify !== 'undefined') {
        // Конфиг: разрешаем только безобидное форматирование внутри текстовых блоков
        const cleanConfig = {
            ALLOWED_TAGS: ['b', 'i', 'strong', 'em', 'a', 'br', 'u', 'mark', 'code', 'span'],
            ALLOWED_ATTR: ['href', 'target', 'rel', 'class', 'style']
            // ЗАМЕТЬ: тут НЕТ img, script, iframe. Они обрабатываются отдельными блоками, а не внутри текста.
        };

        jData.blocks.forEach(block => {
            // !!! ВАЖНАЯ ПРАВКА: Если блок пустой или null, пропускаем его, чтобы не было ошибки
            if (!block || !block.data) return;

            // Чистим текст в параграфах и заголовках
            if (typeof block.data.text === 'string') {
                block.data.text = DOMPurify.sanitize(block.data.text, cleanConfig);
            }

            // Чистим списки, если они есть
            if (block.type === 'list' && Array.isArray(block.data.items)) {
                block.data.items = block.data.items.map(item =>
                    DOMPurify.sanitize(item, cleanConfig)
                );
            }

            // Чистим подписи к картинкам и другим медиа
            if (typeof block.data.caption === 'string') {
                block.data.caption = DOMPurify.sanitize(block.data.caption, cleanConfig);
            }
        });
    } else {
        if (typeof DOMPurify === 'undefined') {
            console.error('DOMPurify не загружен! XSS защита отключена.');
        }
    }
    // --- КОНЕЦ БЛОКА ОЧИСТКИ ---

    // JSON в HTML (парсер для обратного сохранения)
    const edjsParser = edjsHTML();

    // Инициализация Editor.js
    let editor = new EditorJS({
        holder: 'editor',
        autofocus: true,
        placeholder: 'Нажмите Tab для выбора инструмента',

        tools: {
            header: {
                class: Header,
                inlineToolbar: ['marker', 'link'],
                config: {
                    placeholder: 'Подзаголовок',
                    levels: [2, 3, 4, 5],
                    defaultLevel: 3,
                    defaultAlignment: 'left'
                },
                // ЗАЩИТА ЗАГОЛОВКОВ
                sanitizer: {
                    b: true,
                    i: true,
                    a: false, // В заголовках ссылки обычно не нужны
                    img: false
                }
            },

            paragraph: {
                class: Paragraph,
                inlineToolbar: true,
                // Настройки очистки при вставке
                sanitizer: {
                    b: true,
                    i: true,
                    a: {
                        href: true,
                        target: true,
                        rel: true
                    },
                    img: false,
                    script: false,
                    iframe: false,
                    div: false
                },
                config: {
                    preserveBlank: true
                }
            },

            list: {
                class: List,
                inlineToolbar: true,
            },

            quote: {
                class: Quote,
                inlineToolbar: true,
                config: {
                    quotePlaceholder: 'Текст цитаты',
                    captionPlaceholder: 'Подпись',
                },
            },

            marker: {
                class: Marker,
            },

            underline: Underline,
            delimiter: Delimiter,

            image: {
                class: ImageTool,
                inlineToolbar: true,
                enableCaption: true,
                captionPlaceholder: 'Enter a caption',
                config: {
                    uploader: {
                        uploadByFile(file) {
                            const formData = new FormData();
                            formData.append('file', file);
                            formData.append('action', 'handle_image_upload');
                            if (typeof siteData !== 'undefined' && siteData.nonce) {
                                formData.append('security', siteData.nonce);
                            }

                            return fetch(siteData.ajaxUrl, {
                                    method: 'POST',
                                    body: formData,
                                })
                                .then(response => response.json())
                                .then(result => {
                                    if (result.success) {
                                        return {
                                            success: 1,
                                            file: {
                                                url: result.file.url,
                                                id: result.file.id // Важно для удаления
                                            }
                                        };
                                    } else {
                                        throw new Error(result.message);
                                    }
                                });
                        },
                        uploadByUrl(url) {
                            const formData = new FormData();
                            formData.append('action', 'handle_image_upload_by_url');
                            formData.append('url', url);
                            if (typeof siteData !== 'undefined' && siteData.nonce) {
                                formData.append('security', siteData.nonce);
                            }

                            return fetch(siteData.ajaxUrl, {
                                    method: 'POST',
                                    body: formData,
                                })
                                .then(response => response.json())
                                .then(result => {
                                    if (result.success) {
                                        return {
                                            success: 1,
                                            file: {
                                                url: result.file.url,
                                                id: result.file.id // Важно для удаления
                                            }
                                        };
                                    } else {
                                        throw new Error(result.message);
                                    }
                                });
                        },
                    },
                }
            },

            linkTool: {
                class: LinkTool,
                config: {
                    endpoint: `${siteData.url}/wp-json/edjs/link-info`,
                }
            },

            embed: {
                class: Embed,
                inlineToolbar: true,
                config: {
                    services: {
                        youtube: true,
                        coub: true,
                        vk: {
                            regex: /https?:\/\/vk\.com\/video(-?\d+)_(\d+)/,
                            embedUrl: '//vk.com/video_ext.php?oid=<%= remote_id %>',
                            html: "<iframe width='100%' height='450' src='<%= embedUrl %>' frameborder='0' allow='autoplay; encrypted-media; fullscreen; picture-in-picture;'></iframe>",
                            id: (groups) => groups[0] + '&id=' + groups[1],
                        },
                        vkvideo: {
                            regex: /https?:\/\/vkvideo\.ru\/video(-?\d+)_(\d+)/,
                            embedUrl: '//vkvideo.ru/video_ext.php?oid=<%= remote_id %>&hd=2',
                            html: "<iframe width='100%' height='450' src='<%= embedUrl %>' frameborder='0' allow='encrypted-media; fullscreen; picture-in-picture; screen-wake-lock;'></iframe>",
                            id: (groups) => groups.join('&id='),
                        },
                        vimeo: true,
                        imgur: true,
                        codepen: true,
                        instagram: true,
                        twitter: true,
                        pinterest: true,
                        facebook: true,
                        github: true,
                    }
                }
            },

            telegram: Telegram,
            tiktok: TikTok,

            linkButton: {
                class: LinkButton,
                config: {
                    colors: [{
                            name: 'green',
                            icon: '<div class="cdx-lbtn_tune-color" style="background-color:#00ad64;"></div>',
                            title: 'Зелёный фон',
                        },
                        {
                            name: 'blue',
                            icon: '<div class="cdx-lbtn_tune-color" style="background-color:#275efe;"></div>',
                            title: 'Синий фон',
                        }
                    ]
                },
            },

            alert: {
                class: Alert,
                inlineToolbar: true,
                config: {
                    alertTypes: ['primary', 'secondary', 'info', 'success', 'warning', 'danger', 'light', 'dark'],
                    defaultType: 'primary',
                    titlePlaceholder: 'Заголовок',
                    messagePlaceholder: 'Текст оповещения',
                },
            },
        },

        i18n: {
            messages: {
                ui: {
                    "popover": {
                        "Filter": "Поиск",
                        "Nothing found": "Ничего не найдено",
                        "Convert to": "Преобразовать в",
                    },
                    "blockTunes": {
                        "toggler": {
                            "Click to tune": "Нажмите, чтобы настроить",
                            "or drag to move": "или перетащите"
                        },
                    },
                    "inlineToolbar": {
                        "converter": {
                            "Convert to": "Конвертировать в"
                        }
                    },
                    "toolbar": {
                        "toolbox": {
                            "Add": "Добавить"
                        }
                    }
                },
                toolNames: {
                    "Text": "Текст",
                    "Heading": "Подзаголовок",
                    "List": "Список",
                    "Warning": "Примечание",
                    "Checklist": "Чеклист",
                    "Quote": "Цитата",
                    "Code": "Код",
                    "Delimiter": "Разделитель",
                    "Raw HTML": "HTML-фрагмент",
                    "Table": "Таблица",
                    "Link": "Ссылка",
                    "Marker": "Маркер",
                    "Bold": "Жирный",
                    "Italic": "Курсив",
                    "Underline": "Подчёркнутый",
                    "InlineCode": "Моноширинный",
                    "WPImage": "Картинка",
                    "Image": "Картинка",
                    "Ordered List": "Нумерация",
                    "Unordered List": "Маркеры",
                    "Alert": "Оповещение",
                },
                tools: {
                    "header": {
                        "Header": "Заголовок",
                    },
                    "paragraph": {
                        "Enter something": "Введите текст"
                    },
                    "warning": {
                        "Title": "Название",
                        "Message": "Сообщение",
                    },
                    "image": {
                        "Enter a caption": "Описание",
                        "Select an Image": "Загрузите изображение",
                        "With border": "Граница",
                        "Stretch image": "Растянуть",
                        "With background": "Фон",
                        "Caption": "Описание",
                    },
                    "link": {
                        "Add a link": "Добавьте ссылку"
                    },
                    "linkTool": {
                        "Link": "Вставьте ссылку",
                        "Couldn't fetch the link data": "Не удалось получить данные",
                        "Couldn't get this link data, try the other one": "Не удалось получить данные. Попробуйте другую ссылку",
                        "Wrong response format from the server": "Неполадки на сервере",
                    },
                    "stub": {
                        'The block can not be displayed correctly.': 'Блок не может быть отображён'
                    },
                    "embed": {
                        "Enter a caption": "Подпись",
                    },
                    "quote": {
                        "Align Left": "Текст слева",
                        "Align Center": "Текст по центру",
                    },
                    "list": {
                        "Unordered": "Обычный",
                        "Ordered": "Нумерованный",
                    },
                    "convertTo": {
                        "Convert to": "Преобразовать в",
                    },
                    "linkButton": {
                        "Enter a link": "Вставьте ссылку",
                        "Enter a caption": "Текст кнопки",
                    },
                    "alert": {
                        "Left": "Слева",
                        "Center": "По центру",
                        "Right": "Справа",
                        "Primary": "Основной",
                        "Secondary": "Вторичный",
                        "Info": "Информация",
                        "Success": "Успех",
                        "Warning": "Предупреждение",
                        "Danger": "Опасность",
                        "Light": "Светлый",
                        "Dark": "Тёмный",
                    },
                },
                blockTunes: {
                    "delete": {
                        "Delete": "Удалить блок",
                        "Click to delete": "Точно удалить?"
                    },
                    "moveUp": {
                        "Move up": "Переместить вверх"
                    },
                    "moveDown": {
                        "Move down": "Переместить вниз"
                    }
                },
            }
        },

        // Загружаем подготовленные данные
        data: jData,

        // Обработчик сохранения изменений
        onChange: function() {
            editor.save().then((output) => {
                // Парсим JSON в HTML строку
                let toHTML = edjsParser.parse(output);
                textToSend.value = "";

                toHTML.forEach(function(item) {
                    // ЧИСТИМ КАЖДЫЙ БЛОК ПЕРЕД ВСТАВКОЙ В TEXTAREA
                    // Здесь мы используем расширенные настройки DOMPurify,
                    // чтобы он не удалил iframe, виджеты telegram (script) и обертки figure
                    let cleanItem = DOMPurify.sanitize(item, {
                        ADD_TAGS: [
                            'iframe', 'figure', 'figcaption', 'script',
                            'blockquote', 'cite', 'div', 'section'
                        ],
                        ADD_ATTR: [
                            'allow', 'allowfullscreen', 'frameborder', 'scrolling',
                            'target', 'src', 'data-telegram-post', 'data-width',
                            'data-video-id', 'class', 'style', 'width', 'height', 'cite'
                        ]
                    });
                    textToSend.value += cleanItem;
                });
            }).catch((error) => {
                console.log('Saving failed: ', error);
            });
        }
    });
};

(function() {
    function fixTelegram() {
        var iframes = document.querySelectorAll('iframe[src*="t.me"]');
        iframes.forEach(function(ir) {
            // 1. Если стиль пустой или битый - сбрасываем его нахер
            if (ir.style.height === "" || ir.style.height === "0px") {
                ir.style.setProperty('height', '600px', 'important');
            }
        });
    }

    // Слушаем сообщения от Телеграма (настоящий ресайз)
    window.addEventListener('message', function(e) {
        if (e.origin.indexOf('t.me') === -1) return;
        try {
            var data = (typeof e.data === 'string') ? JSON.parse(e.data) : e.data;
            if (data.event === 'resize' && data.height) {
                var tgs = document.querySelectorAll('iframe[src*="t.me"]');
                tgs.forEach(function(f) {
                    if (f.contentWindow === e.source) {
                        f.style.setProperty('height', data.height + 'px', 'important');
                    }
                });
            }
        } catch (err) {}
    });

    // Запускаем проверку сразу и через секунду после загрузки
    fixTelegram();
    window.addEventListener('load', fixTelegram);
    setTimeout(fixTelegram, 1500); 
})();
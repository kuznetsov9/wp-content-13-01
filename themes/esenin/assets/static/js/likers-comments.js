document.addEventListener('DOMContentLoaded', function() {
    
    // --- 1. ЛОГИКА ЛАЙКОВ (REST API) ---
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.like-comm-btn');
        if (!btn || btn.classList.contains('is-waiting')) return;

        const container = btn.closest('.like-comment-button');
        if (!container) return; 

        const commentId = container.dataset.commentId;
        const countElement = container.querySelector('.like-count');
        const wasLiked = btn.classList.contains('liked');
        const oldTotal = parseInt(countElement.textContent) || 0;

        btn.classList.add('is-waiting');
        
        // Оптимистичный апдейт (меняем цифру сразу, не ждем ответа)
        if (wasLiked) {
            btn.classList.remove('liked');
            countElement.textContent = oldTotal - 1;
        } else {
            btn.classList.add('liked');
            countElement.textContent = oldTotal + 1;
        }

        fetch(`${rfplDataComm.root}rfpl/v1/like-comment/${commentId}`, {
            method: 'POST',
            headers: { 'X-WP-Nonce': rfplDataComm.nonce, 'Content-Type': 'application/json' }
        })
        .then(res => { if (!res.ok) throw new Error('Auth'); return res.json(); })
        .then(data => {
            btn.classList.remove('is-waiting');
            if (data.success) countElement.textContent = data.count;
        })
        .catch(err => {
            btn.classList.remove('is-waiting');
            btn.classList.toggle('liked', wasLiked);
            countElement.textContent = oldTotal;
            if (err.message === 'Auth') window.location.hash = "login";
        });
    });

    // --- 2. ХОВЕР НА ОЦЕНИВШИХ (REST API) ---
    document.addEventListener('mouseover', function(e) {
        const countEl = e.target.closest('.like-count');
        if (!countEl) return;
        const container = countEl.closest('.like-comment-button');
        if (!container) return; 

        const commentId = container.dataset.commentId;
        const dropdown = container.querySelector('.dropdown-content');
        if (!dropdown) return;

        dropdown.style.display = 'block';

        if (dropdown.innerHTML === '') { // Подгружаем только если пусто
            fetch(`${rfplDataComm.root}rfpl/v1/comment-voters/${commentId}`)
                .then(res => res.json())
                .then(data => { if (data.votersHtml) dropdown.innerHTML = data.votersHtml; });
        }
    });

    document.addEventListener('mouseout', function(e) {
        const container = e.target.closest('.like-comment-button');
        if (container && !container.contains(e.relatedTarget)) {
            const dropdown = container.querySelector('.dropdown-content');
            if (dropdown) dropdown.style.display = 'none';
        }
    });

    // --- 3. НОВИНКА: ЗАГРУЗКА И СОРТИРОВКА (REST API) ---
    // Слушаем клики по кнопкам сортировки (Популярные/Новые)
    const commentContainer = document.querySelector('.es-comments-list-container'); // Проверь селектор контейнера списка
    const sortButtons = document.querySelectorAll('.comment-sort-button'); // Проверь селектор кнопок
    
    if (sortButtons.length > 0) {
        sortButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const sort = this.dataset.sort; // 'popular', 'newest' и т.д.
                const postId = this.dataset.postId;
                
                loadRfplComments(postId, sort, 1);
            });
        });
    }

    function loadRfplComments(postId, sort, page) {
        const listWrapper = document.querySelector('#comments-list-wrapper'); // Куда вставлять HTML
        if (!listWrapper) return;

        listWrapper.classList.add('is-loading'); // Добавь CSS лоадер, если есть

        fetch(`${rfplDataComm.root}rfpl/v1/comments/${postId}?sort=${sort}&page=${page}`)
            .then(res => res.json())
            .then(data => {
                listWrapper.classList.remove('is-loading');
                if (data.html) {
                    listWrapper.innerHTML = data.html;
                    // Если была пагинация - тут её тоже можно обновить
                }
            })
            .catch(err => console.error('Ошибка загрузки комментов:', err));
    }
});
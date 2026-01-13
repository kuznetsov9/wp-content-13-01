document.addEventListener('DOMContentLoaded', function() {
    function updateVoters(postId, container) {
        const dropdown = container.querySelector('.dropdown-content');
        if (!dropdown) return;
        fetch(`${rfplData.root}rfpl/v1/voters/${postId}`)
            .then(res => res.json())
            .then(data => { if (data.votersHtml) dropdown.innerHTML = data.votersHtml; });
    }

    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.like-btn');
        if (!btn || btn.classList.contains('is-waiting')) return;
        const container = btn.closest('.like-button');
        if (!container) return;

        const postId = container.dataset.postId;
        const countElement = container.querySelector('.like-count');
        const wasLiked = btn.classList.contains('liked');
        const oldTotal = parseInt(countElement.textContent) || 0;

        btn.classList.add('is-waiting');
        if (wasLiked) {
            btn.classList.remove('liked');
            countElement.textContent = oldTotal - 1;
        } else {
            btn.classList.add('liked');
            countElement.textContent = oldTotal + 1;
        }

        fetch(`${rfplData.root}rfpl/v1/like/${postId}`, {
            method: 'POST',
            headers: { 'X-WP-Nonce': rfplData.nonce, 'Content-Type': 'application/json' }
        })
        .then(res => { if (!res.ok) throw new Error('Auth'); return res.json(); })
        .then(data => {
            btn.classList.remove('is-waiting');
            if (data.success) {
                countElement.textContent = data.count;
                updateVoters(postId, container);
            }
        })
        .catch(err => {
            btn.classList.remove('is-waiting');
            btn.classList.toggle('liked', wasLiked);
            countElement.textContent = oldTotal;
            if (err.message === 'Auth') window.location.hash = "login";
        });
    });

    document.addEventListener('mouseover', function(e) {
        const countEl = e.target.closest('.like-count');
        if (!countEl) return;
        const container = countEl.closest('.like-button');
        if (!container) return; 
        container.querySelector('.dropdown-content').style.display = 'block';
        updateVoters(container.dataset.postId, container);
    });

    document.addEventListener('mouseout', function(e) {
        const container = e.target.closest('.like-button');
        if (container && !container.contains(e.relatedTarget)) {
            const dropdown = container.querySelector('.dropdown-content');
            if (dropdown) dropdown.style.display = 'none';
        }
    });
});
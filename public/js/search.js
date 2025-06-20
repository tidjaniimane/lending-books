document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('searchInput');

    if (!input) return; // ✅ Prevents error if the element doesn't exist

    input.addEventListener('input', function () {
        const query = input.value;

        if (query.length < 2) return;

        fetch(`/NoticeExemplaire/search?q=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                const resultsList = document.getElementById('resultsList');
                resultsList.innerHTML = '';

                if (data.length === 0) {
                    resultsList.innerHTML = '<li>Aucun résultat</li>';
                    return;
                }

                data.forEach(notice => {
                    const li = document.createElement('li');
                    li.textContent = `${notice.doc_titre} — ${notice.doc_auteur}`;
                    resultsList.appendChild(li);
                });
            })
            .catch(error => console.error('Erreur:', error));
    });
});

document.querySelectorAll('.delete-news').forEach(element => {
    element.addEventListener('click', function() {
        if (true === confirm('Are you sure you want to delete this news?')) {
            const id = this.dataset.id;
            const url = new URL('/news', window.location.origin);
            url.searchParams.set('id', id);
            fetch(url, {method: 'DELETE'})
                .then(response => {
                    if (response.status !== 200) {
                        throw new Error('Server did not return 200 status code');
                    }
                    response.json().then(() => {
                        location.reload();
                    });
                })
                .catch(error => {console.log(error);});
        }
    });
});

document.querySelectorAll('.update-news').forEach(el => {
    el.addEventListener('click', function () {
        fetch(`/news?id=${this.dataset.id}`, {method: 'GET'})
            .then(response => {
                if (response.status !== 200) {
                    throw new Error('Server did not return 200 status code');
                }
                return response.json();
            })
            .then(data => {
                document.getElementById('title-input').value = data.title;
                document.getElementById('description-input').value = data.description;
                document.getElementById('js-form-title').innerText = 'Update News';
                const form = document.getElementById('js-news-form');
                form.action = `/news/update?id=${this.dataset.id}`;
                form.method = 'post';
                form.querySelector('button[type=submit]').innerText = 'Save';
                const closeIconWrapper = document.getElementById('close-icon-wrapper');

                if (!closeIconWrapper.querySelector('img')) {
                    const closeIcon = new Image();
                    closeIcon.src = 'icon/close.svg';
                    closeIcon.classList.add('action-icon', 'close-update');
                    closeIcon.addEventListener('click', () => { location.reload(); });
                    closeIconWrapper.appendChild(closeIcon);
                }
            })
            .catch(error => {console.log(error);});
    });
});

document.getElementById('js-logout-btn').addEventListener('click', () => window.location = '/logout');
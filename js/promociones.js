const img = document.querySelector('.foto img');

img.addEventListener('click', function() {
    if (this.classList.contains('zoomed')) {
        this.classList.remove('zoomed');
    } else {
        this.classList.add('zoomed');
    }
});

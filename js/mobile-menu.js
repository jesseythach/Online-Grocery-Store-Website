window.addEventListener('load', function () {
    if (window.innerWidth > 600) return
    this.document.getElementById('head').addEventListener('click', function () {
        if (document.getElementById('head').classList.contains('h-screen')) {
            document.getElementById('head').classList.remove('h-screen');
        }
        else {
            document.getElementById('head').classList.add('h-screen');
        }
    });
});
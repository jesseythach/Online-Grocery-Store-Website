if (this.window.location.pathname == '/index.php') {
    window.addEventListener('scroll', function () {
        if (this.window.scrollY > 20) this.document.getElementById('head').style.top = '0px';
        else this.document.getElementById('head').style.top = '-50px';

        if (this.window.scrollY > 20) this.document.getElementById('foot').style.bottom = '0px';
        else this.document.getElementById('foot').style.bottom = '-50px';
    });
} else {
    window.addEventListener('load', function () {
        this.document.getElementById('head').style.top = '0px';
        this.document.getElementById('foot').style.bottom = '0px';
    });
}
function performSearch() {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("content-container").innerHTML = this.responseText;
        }
    };

    var formData = new FormData(document.getElementById('searchForm'));
    xhttp.open("POST", 'grades.php', true);
    xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhttp.send("search=" + formData.get('search'));
}

function perfSearch() {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("content-container").innerHTML = this.responseText;
        }
    };

    var formData = new FormData(document.getElementById('searchForm'));
    xhttp.open("POST", 'student_list.php', true);
    xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhttp.send("search=" + formData.get('search'));
}

function toggleNav() {
    var sidenav = document.querySelector('.sidenav');
    sidenav.classList.toggle('open');
}
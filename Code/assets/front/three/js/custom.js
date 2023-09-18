if (document.getElementById('nav-mobile-btn')) {
    document.getElementById('nav-mobile-btn').addEventListener('click', function () {
        if (this.classList.contains('close')) {
            document.getElementById('nav').classList.add('hidden');
            this.classList.remove('close');
        } else {
            document.getElementById('nav').classList.remove('hidden');
            this.classList.add('close');
        }
    });
}

function showDropdownOptions() {
    document.getElementById("options").classList.toggle("hidden");
    document.getElementById("arrow-up").classList.toggle("hidden");
    document.getElementById("arrow-down").classList.toggle("hidden");
}
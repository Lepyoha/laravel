function SubMenu(text) {
    document.getElementById(text).classList.toggle("show");
}

window.onclick = function(event) {
    if (!event.target.matches('.dropbtn1')) {
        var dropdowns = document.getElementsByClassName("dropdown_dots_content");
        for (var i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('show')) {
                openDropdown.classList.remove('show');
            }
        }
    }
    if (!event.target.matches('.dropbtn2') && !event.target.matches('.menu-images') && !event.target.matches('.drop_communication_menu span')) {
        var dropdowns = document.getElementsByClassName("dropdown_communication_content");
        for (var i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('show')) {
                openDropdown.classList.remove('show');
            }
        }
    }
    if (!event.target.matches('.dropbtn4') && !event.target.matches('.menu-images') && !event.target.matches('.drop_menu_menu span')) {
        var dropdowns = document.getElementsByClassName("dropdown_menu_content");
        for (var i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('show')) {
                openDropdown.classList.remove('show');
            }
        }
    }
}

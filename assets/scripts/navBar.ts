
window.onscroll = function() {scrollFunction()};

function scrollFunction() {
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {

        document.getElementById("navbar").setAttribute("style", "background-color: #272727 !important;");
    } else {

        document.getElementById("navbar").setAttribute("style", "background-color: transparent !important;");
    }
}
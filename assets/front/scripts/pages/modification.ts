
function toggleModification() {
    // HTMLInputElement peut changer en fonction du type d'élément HTML que vous souhaitez récupérer
    const button: HTMLButtonElement = document.querySelector('.toggleBtn');
    const div: HTMLDivElement = document.querySelector('.accountModification');

    // On vérifit s'il existe, afin de ne pas avoir d'erreur JS sur la page
    if (button && div) {
        button.addEventListener('click', function () {
            if(div.classList.contains('active')) {
                div.classList.remove('active');
            }else{
                div.classList.add('active');
            }
        })
    }
}

window.addEventListener('load', () => {
    toggleModification();
});

function runBebou() {
    let arrayOfLinks = document.querySelectorAll('a');

    if(arrayOfLinks) {
        arrayOfLinks.forEach(link => {
            let bebouSound = new Audio('/build/mp3/bebou'+ Math.floor(Math.random() * 6) +'.mp3');
            link.addEventListener("click" , (e) => {
                e.preventDefault();
                let hrefClicked: string = link.href;

                bebouSound.play();

                bebouSound.onended = function() {
                    window.location.href = hrefClicked;
                };
            })
        } );
    }
}

window.addEventListener('load', () => {
    runBebou();
});
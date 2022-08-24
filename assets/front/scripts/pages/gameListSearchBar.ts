function queryGamesSearchBar() {
    // HTMLInputElement peut changer en fonction du type d'élément HTML que vous souhaitez récupérer
    const input: HTMLInputElement = document.querySelector('#searchGame');
    // On vérifit s'il existe, afin de ne pas avoir d'erreur JS sur la page
    if (input) {
        input.addEventListener('keyup', (event) => {
            // On récupère la valeur de l'input
            let value: string = input.value;
            fetch('/all_games/search/' + JSON.stringify(value))
                .then((response: Response) => {
                    return response.json() as Promise<any>;
                })
                .then(data => {
                    let results: HTMLDivElement = document.querySelector('.searchDisplay');
                    let basic: HTMLDivElement = document.querySelector('.basicDisplay');
                    results.innerHTML = data.html;
                    // On change le style display none en display block
                    if (value !== '') {
                        basic.style.display = 'none';
                        results.style.display = 'block';
                    } else {
                        basic.style.display = 'block';
                        results.style.display = 'none';
                    }

                })
                .catch((e) => {
                });

        });
    }
}

window.addEventListener('load', () => {
    queryGamesSearchBar();
});
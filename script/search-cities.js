document.addEventListener('DOMContentLoaded', () => {
    // List element
    let listResults = document.getElementById('result-code-or-city')
    // Inputs
    let searchCity = document.querySelector("[data-search-city]")
    let searchCode = document.querySelector("[data-search-code]")

    // Click events
    searchCity.addEventListener('keyup', async (event) => {
        // Reset ul
        listResults.innerHTML = 'Choisissez parmi les communes :'
        listResults.style.display = "block"

        const target = event.target.value
        const apiURL = "https://geo.api.gouv.fr/communes"

        // API RESPONSE
        const response = await fetch(`${apiURL}?nom=${target}&&limit=10`, {
            method: "GET",
            headers: {
                'Content-Type': 'application/json'
            }
        })
        const json = await response.json()

        // Add li to list
        json.forEach(item => {
            listResults.innerHTML += `<li>${item.nom}</li>`
        })
        choisirVille(listResults)
    })

    searchCode.addEventListener('keyup', async (event) => {

        listResults.innerHTML = 'Choisissez parmi les communes :'
        listResults.style.display = "block"

        const target = event.target.value
        const apiURL = "https://geo.api.gouv.fr/communes"

        // API RESPONSE
        const response = await fetch(`${apiURL}?codePostal=${target}&&limit=10`, {
            method: "GET",
            headers: {
                'Content-Type': 'application/json'
            }
        })
        const json = await response.json()

        json.forEach(item => {
            listResults.innerHTML += `<li>${item.nom}</li>`
        })
        choisirVille(listResults)
    })

    searchCode.addEventListener('click', () => {
        searchCity.value = ""
    })
    searchCity.addEventListener('click', () => {
        searchCode.value = ""
    })
})

function choisirVille(listResults) {
    let villes = document.querySelectorAll("#result-code-or-city li")
    let ville

    for (let i = 0; i < villes.length; i++) {
        villes[i].onclick = function () {
            listResults.innerHTML = "<input id=\"villeChoisie\" type=\"text\" disabled=\"disabled\">"
            ville = this.innerHTML
            document.getElementById("villeChoisie").setAttribute('value', ville);
            document.getElementById("villeChoisieHidden").setAttribute('value', ville);
        };
    }
}
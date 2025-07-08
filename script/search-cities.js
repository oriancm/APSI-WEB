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
            const departementCode = item.codeDepartement || '';
            const displayName = departementCode ? `${item.nom} (${departementCode})` : item.nom;
            listResults.innerHTML += `<li data-ville-complete="${displayName}">${displayName}</li>`
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
            const departementCode = item.codeDepartement || '';
            const displayName = departementCode ? `${item.nom} (${departementCode})` : item.nom;
            listResults.innerHTML += `<li data-ville-complete="${displayName}">${displayName}</li>`
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

    for (let i = 0; i < villes.length; i++) {
        villes[i].onclick = function () {
            // Récupérer la valeur de l'attribut data-ville-complete ou le texte
            const ville = this.getAttribute('data-ville-complete') || this.innerHTML;
            
            // Créer l'input désactivé pour afficher la sélection
            listResults.innerHTML = "<input id=\"villeChoisie\" type=\"text\" disabled=\"disabled\">";
            
            // Définir les valeurs dans les champs
            document.getElementById("villeChoisie").value = ville;
            document.getElementById("villeChoisieHidden").value = ville;
        };
    }
}
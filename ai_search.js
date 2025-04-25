const searchInput = document.querySelector("#searchInput");
const suggestionsBox = document.querySelector("#suggestions");

searchInput.addEventListener("input", () => {
    const query = searchInput.value.trim();
    if (query.length > 2) {
        fetch(`php/search_ai.php?query=${query}`)
            .then(response => response.json())
            .then(data => {
                suggestionsBox.innerHTML = data.map(item => `<li>${item}</li>`).join("");
            })
            .catch(err => console.error(err));
    } else {
        suggestionsBox.innerHTML = "";
    }
});

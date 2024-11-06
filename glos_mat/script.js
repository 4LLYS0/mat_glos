const termsList = document.getElementById('termsList');
const terms = [
    { name: 'Álgebra', description: 'Estudo de estruturas matemáticas.' },
    { name: 'Binômio', description: 'Expressão algébrica com dois termos.' },
    // Adicione mais termos
];



// Função para exibir os termos
function displayTerms() {
    termsList.innerHTML = '';
    terms.forEach(term => {
        let li = document.createElement('li');
        li.className = 'term-item';
        li.innerHTML = `<strong>${term.name}</strong>: ${term.description}`;
        termsList.appendChild(li);
    });
}

function searchTerms() {
    const query = document.getElementById('searchBar').value.toLowerCase();
    termsList.innerHTML = '';
    terms.filter(term => term.name.toLowerCase().includes(query)).forEach(term => {
        let li = document.createElement('li');
        li.className = 'term-item';
        li.innerHTML = `<strong>${term.name}</strong>: ${term.description}`;
        termsList.appendChild(li);
    });
}

function sortTerms() {
    terms.sort((a, b) => a.name.localeCompare(b.name));
    displayTerms();
}

// Inicializar a lista ao carregar a página
window.onload = displayTerms;

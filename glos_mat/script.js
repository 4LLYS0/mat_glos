const sidebar = document.querySelector('.sidebar');
const toggleButton = document.getElementById('toggle-sidebar-button');
const modeToggle = document.getElementById('mode-toggle');
const termosLista = document.getElementById('termos-lista');
const btnAddTerm = document.getElementById('btn-add-term');
const addTermForm = document.getElementById('addTermForm');

const termos = [
    { nome: 'Álgebra', descricao: 'Ramo da matemática que lida com símbolos e regras para manipular esses símbolos.' },
    { nome: 'Geometria', descricao: 'Estudo das propriedades e relações de pontos, linhas, superfícies e sólidos.' },
    { nome: 'Cálculo', descricao: 'Ramo da matemática focado em limites, derivadas, integrais e séries infinitas.' },
    { nome: 'Trigonometria', descricao: 'Estudo das relações entre os ângulos e os comprimentos dos triângulos.' }
];

// Alternar a sidebar e mudar o botão para "+" ou "Adicionar"
toggleButton.addEventListener('click', () => {
    sidebar.classList.toggle('collapsed');
    toggleButton.textContent = sidebar.classList.contains('collapsed') ? '❯' : '❮';
    btnAddTerm.textContent = sidebar.classList.contains('collapsed') ? '+' : 'Adicionar';
});

// Atualizar a lista de termos com botões de editar e excluir
function atualizarLista() {
    termosLista.innerHTML = termos
        .sort((a, b) => a.nome.localeCompare(b.nome))
        .map((termo, index) => `
            <div class="termo">
                <h2>${termo.nome}</h2>
                <p>${termo.descricao}</p>
                <div class="termo-buttons">
                    <button onclick="editTerm(${index})" class="btn-edit">Editar</button>
                    <button onclick="deleteTerm(${index})" class="btn-delete">Excluir</button>
                </div>
            </div>
        `).join('');
}

// Filtrar termos com base na busca
function searchTerms() {
    const query = document.getElementById('searchBar').value.toLowerCase();
    const termosFiltrados = termos.filter(termo => termo.nome.toLowerCase().includes(query));
    termosLista.innerHTML = termosFiltrados
        .map((termo, index) => `
            <div class="termo">
                <h2>${termo.nome}</h2>
                <p>${termo.descricao}</p>
                <div class="termo-buttons">
                    <button onclick="editTerm(${index})" class="btn-edit">Editar</button>
                    <button onclick="deleteTerm(${index})" class="btn-delete">Excluir</button>
                </div>
            </div>
        `).join('');
}

// Exibir o formulário de adição de termo
btnAddTerm.addEventListener('click', () => {
    addTermForm.style.display = 'block';
});

// Fechar o formulário de adição de termo
function closeForm() {
    addTermForm.style.display = 'none';
    document.getElementById('termName').value = '';
    document.getElementById('termDescription').value = '';
}

// Adicionar um novo termo
function addTerm() {
    const termName = document.getElementById('termName').value.trim();
    const termDescription = document.getElementById('termDescription').value.trim();

    if (termName && termDescription) {
        termos.push({ nome: termName, descricao: termDescription });
        atualizarLista();
        closeForm();
    }
}

// Editar um termo existente
function editTerm(index) {
    const termo = termos[index];
    document.getElementById('termName').value = termo.nome;
    document.getElementById('termDescription').value = termo.descricao;
    addTermForm.style.display = 'block';

    // Atualizar o termo após edição
    addTermForm.onsubmit = function(event) {
        event.preventDefault();
        termos[index] = {
            nome: document.getElementById('termName').value,
            descricao: document.getElementById('termDescription').value
        };
        atualizarLista();
        closeForm();
    };
}

// Excluir um termo
function deleteTerm(index) {
    termos.splice(index, 1);
    atualizarLista();
}

// Alternar modo escuro
modeToggle.addEventListener('change', () => {
    document.body.classList.toggle('dark-mode');
});

// Iniciar com a lista de termos atualizada
atualizarLista();

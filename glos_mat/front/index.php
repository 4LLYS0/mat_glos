<?php
// Incluindo a conexão com o banco de dados
require_once '../back/db/conexao.php'; // Ajuste o caminho conforme necessário

try {
    $pdo = new PDO('mysql:host=localhost;dbname=math_gloss', 'root', '');  
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}

// Inicializando as variáveis $erro e $sucesso
$erro = '';
$sucesso = '';

// Buscar os termos no banco
$termos = [];
try {
    $stmt = $pdo->query("SELECT * FROM termos");
    $termos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erro ao buscar os termos: " . $e->getMessage();
}

// Verificar se o formulário de adição de termo foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['termName']) && isset($_POST['termDescription'])) {
    // Coletar os dados do formulário
    $termName = $_POST['termName'] ?? '';
    $termDescription = $_POST['termDescription'] ?? '';

    // Validar os campos
    if (empty($termName) || empty($termDescription)) {
        $erro = 'Todos os campos são obrigatórios.';
    } else {
        // Inserir o termo no banco de dados
        $stmt = $pdo->prepare("INSERT INTO termos (termo, definicao) VALUES (:termo, :definicao)");
        $stmt->bindParam(':termo', $termName);
        $stmt->bindParam(':definicao', $termDescription);

        if ($stmt->execute()) {
            $sucesso = 'Termo adicionado com sucesso!';
        } else {
            $erro = 'Erro ao adicionar o termo. Tente novamente.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Glossário Matemático</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Itim&family=Tiny5&family=Varela+Round&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <div class="sidebar">
        <div class="logo">
            <span class="logo-icon">MT</span>
            <span class="logo-text">Matglossary</span>
        </div>
        <ul class="menu">
        <input type="text" id="searchBar" placeholder="Pesquisar termos..." onkeyup="searchTerms()">
            <li><a href="../back/index.php "><i class="bi bi-bar-chart-line-fill"></i><span>Dashboard</span></a></li>
            <li><a href="#"><i class="bi bi-signpost-split-fill"></i> <span>Categoria</span></a></li>
            <li><a href="#"><i class="bi bi-box"></i> <span>Símbolos</span></a></li>
            <li><a href="#"><i class="bi bi-info-circle"></i> <span>Sobre</span></a></li>
        </ul>
    
        <button id="btn-add-term" class="btn-add">Adicionar</button>
    
        <div class="bottom-menu">
            <a href="../back/login.php" class="logout"> <span>Login</span></a>
            <div class="mode-switch">
                <span>Dark Mode</span>
                <label class="switch-container">
                <input type="checkbox"  id="mode-toggle" >
                <span class="slider"></span>
                </label>

                <input type="checkbox" id="mode-toggle" style="margin-left: 10px;">
            </div>
            <button id="toggle-sidebar-button" class="toggle-sidebar-button">❮</button>
        </div>
    </div>

    <!-- Formulário de Adição de Termo -->
    <div id="addTermForm" class="addTermForm">
        <h2>Adicionar Termo</h2>
        <!-- Exibição de erros ou sucesso -->
        <?php if ($erro): ?>
            <div class="error-message"><?= $erro; ?></div>
        <?php endif; ?>
        <?php if ($sucesso): ?>
            <div class="success-message"><?= $sucesso; ?></div>
        <?php endif; ?>

        <form method="POST" action="index.php">
            <label for="termName">Nome do Termo:</label>
            <input type="text" id="termName" name="termName" required>
            
            <label for="termDescription">Descrição:</label>
            <textarea id="termDescription" name="termDescription" rows="3" required></textarea>
            
            <button type="submit">Salvar</button>
            <button type="button" onclick="closeForm()">Cancelar</button>
        </form>
    </div>

    <div class="main-content">
    <h1>Bem-vindo !!!</h1>
    <div id="termos-lista">
        <?php 
        // Depuração opcional para verificar os termos retornados
        // var_dump($termos);

        if (!empty($termos)): ?>
            <?php foreach ($termos as $termo): ?>
                <div class="termo">
                    <h3><?= htmlspecialchars($termo['termo']); ?></h3>
                    <p><?= htmlspecialchars($termo['definicao']); ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Não há termos cadastrados ainda.</p>
        <?php endif; ?>
    </div>
</div>


</body>

</html>
<script>
   const sidebar = document.querySelector('.sidebar');
    const toggleButton = document.getElementById('toggle-sidebar-button');
    const termosLista = document.getElementById('termos-lista');
    const btnAddTerm = document.getElementById('btn-add-term');
    const addTermForm = document.getElementById('addTermForm');
    const modeToggle = document.getElementById('mode-toggle');

    // Função para alternar o modo escuro
    modeToggle.addEventListener('change', function() {
        if (modeToggle.checked) {
            // Ativa o modo escuro
            document.body.classList.add('dark-mode');
            document.querySelector('.sidebar').classList.add('dark-mode');
            document.querySelector('.main-content').classList.add('dark-mode');
            document.querySelector('button').classList.add('dark-mode');
            document.querySelector('input[type="checkbox"]').classList.add('dark-mode');
        } else {
            // Desativa o modo escuro
            document.body.classList.remove('dark-mode');
            document.querySelector('.sidebar').classList.remove('dark-mode');
            document.querySelector('.main-content').classList.remove('dark-mode');
            document.querySelector('button').classList.remove('dark-mode');
            document.querySelector('input[type="checkbox"]').classList.remove('dark-mode');
        }
    });

    function searchTerms() {
    // Obtém o valor digitado no campo de pesquisa
    const query = document.getElementById('searchBar').value.toLowerCase();
    
    // Filtra os termos com base na busca (nome ou descrição)
    const termosFiltrados = termos.filter(termo => 
        termo.termo.toLowerCase().includes(query) || 
        termo.definicao.toLowerCase().includes(query)
    );

    // Exibe os termos filtrados na página
    termosLista.innerHTML = termosFiltrados
        .map((termo) => `
            <div class="termo">
                <h3>${termo.termo}</h3>
                <p>${termo.definicao}</p>
            </div>
        `)
        .join('');
}

    // Carrega o estado do modo escuro ao carregar a página
    window.addEventListener('load', function() {
        if (localStorage.getItem('darkMode') === 'true') {
            modeToggle.checked = true;
            document.body.classList.add('dark-mode');
            document.querySelector('.sidebar').classList.add('dark-mode');
            document.querySelector('.main-content').classList.add('dark-mode');
            document.querySelector('button').classList.add('dark-mode');
            document.querySelector('input[type="checkbox"]').classList.add('dark-mode');
        }
    });

    // Salva o estado do modo escuro no localStorage
    modeToggle.addEventListener('change', function() {
        localStorage.setItem('darkMode', modeToggle.checked);
    });

    const botao = document.getElementById('btn-add-term'); 
    const card = document.querySelector('.addTermForm');  

    botao.addEventListener('click', function() {
        card.style.display = 'block'; 
    });

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

    toggleButton.addEventListener('click', () => {
    // Alterna a classe 'collapsed' na sidebar
    sidebar.classList.toggle('collapsed');

    // Verifica se a sidebar está "colapsada"
    if (sidebar.classList.contains('collapsed')) {
        // Define o texto do botão de alternância e do botão de adicionar
        toggleButton.textContent = '❯';    // Texto para quando o menu estiver fechado
        btnAddTerm.textContent = '+';       // Texto para o botão de adicionar quando o menu estiver fechado
    } else {
        // Define o texto do botão de alternância e do botão de adicionar
        toggleButton.textContent = '❮';    // Texto para quando o menu estiver aberto
        btnAddTerm.textContent = 'Adicionar'; // Texto do botão de adicionar quando o menu estiver aberto
    }
    });



</script>







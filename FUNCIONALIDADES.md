# 📚 Documentação das Funcionalidades Implementadas

Este documento descreve em detalhes todas as funcionalidades desenvolvidas para o teste técnico da AgronomiQ.

---

## 🎯 Visão Geral

O projeto foi estendido com funcionalidades de visualização de dados geoespaciais e gráficos interativos, mantendo a estrutura base do Laravel 9.

---

## 📄 Estrutura de Arquivos Criados/Modificados

### **Arquivos Criados:**

1. **`resources/views/mapa.blade.php`**
   - View do mapa interativo com OpenLayers
   - Componentes: coordenadas do mouse, popup, botões de zoom, navegação

2. **`resources/views/grafico.blade.php`**
   - View de gráficos com Chart.js
   - Componentes: gráfico de linhas/barras, botões de controle, navegação

3. **`cypress.config.js`**
   - Configuração do framework de testes Cypress

4. **`cypress/e2e/home.cy.js`**
   - Testes E2E da página inicial (9 testes)

5. **`cypress/e2e/mapa.cy.js`**
   - Testes E2E do mapa interativo (35+ testes)

6. **`cypress/e2e/grafico.cy.js`**
   - Testes E2E dos gráficos (40+ testes)

7. **`cypress/support/e2e.js`**
   - Configurações globais do Cypress

8. **`cypress/support/commands.js`**
   - Comandos customizados reutilizáveis

9. **`resources/css/app.css`**
   - Estilos customizados da aplicação

### **Arquivos Modificados:**

1. **`resources/views/welcome.blade.php`**
   - Adicionados dois cartões de navegação (Mapa e Gráfico)

2. **`routes/web.php`**
   - Adicionadas rotas `/mapa` e `/grafico`
   - Documentação completa de cada rota

3. **`package.json`**
   - Adicionado Cypress como dependência
   - Scripts para executar testes

4. **`README.md`**
   - Documentação completa das funcionalidades
   - Instruções de instalação e uso

5. **`app/Http/Controllers/Controller.php`**
   - Adicionada documentação da classe base

6. **`app/Providers/AppServiceProvider.php`**
   - Melhorada documentação dos métodos

---

## 🗺️ Funcionalidades do Mapa (`/mapa`)

### **1. Componente de Coordenadas do Mouse**

**Localização:** Canto inferior esquerdo da tela

**Características:**
- Posição fixa (fixed)
- Fundo branco com opacidade 0.95
- Fonte monospace de 14px
- Borda esquerda azul (#2196F3)
- Efeito de blur no fundo (backdrop-filter)
- Atualização em tempo real ao mover o mouse

**Código:**
```javascript
map.on('pointermove', function(evt) {
    const coords = ol.proj.toLonLat(evt.coordinate);
    const lon = coords[0].toFixed(6);
    const lat = coords[1].toFixed(6);
    document.getElementById('mouse-position').innerHTML = 
        `<strong>Lat:</strong> ${lat} | <strong>Lon:</strong> ${lon}`;
});
```

### **2. Context Menu (Popup)**

**Ativação:** Clique com botão direito no mapa

**Conteúdo:**
- Latitude do ponto clicado
- Longitude do ponto clicado
- Data e hora do clique (formato: DD/MM/YYYY HH:mm:ss)
- Ícone de calendário ao lado da data

**Características:**
- Padding de 20px para espaçamento maior
- Margin de 12px entre elementos
- Ícones Font Awesome para cada informação
- Botão de fechar (×)

**Função de Formatação:**
```javascript
function formatDateTime(date) {
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = date.getFullYear();
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');
    const seconds = String(date.getSeconds()).padStart(2, '0');
    
    return `${day}/${month}/${year} ${hours}:${minutes}:${seconds}`;
}
```

### **3. Botões de Zoom**

**Localização:** Topo esquerdo da tela

**Características:**
- Dois botões agrupados em container vertical
- Botão + (Zoom In)
- Botão - (Zoom Out)
- Design moderno sem bordas grossas
- Hover com cor azul
- Efeito de clique (active state)

**Funções:**
```javascript
function ZoomInOut(action) {
    const view = map.getView();
    const currentZoom = view.getZoom();
    
    if (action === 'in') {
        view.animate({ zoom: currentZoom + 1, duration: 300 });
    } else if (action === 'out') {
        view.animate({ zoom: currentZoom - 1, duration: 300 });
    }
}
```

### **4. Botão Alternar Mapa de Fundo**

**Localização:** Topo direito da tela

**Funcionalidade:**
- Alterna entre OpenStreetMap e Google Satellite
- Indicador visual do mapa ativo
- Ícone de camadas (layer-group)

**Função:**
```javascript
function ToggleRaster() {
    isOSM = !isOSM;
    osmLayer.setVisible(isOSM);
    satelliteLayer.setVisible(!isOSM);
    
    const layerNameElement = document.getElementById('layerName');
    layerNameElement.textContent = isOSM ? 'OpenStreetMap' : 'Google Satellite';
}
```

### **5. Barra de Escala**

**Localização:** Canto inferior direito

**Características:**
- Movida da posição padrão (esquerda) para direita
- Exibe escala em metros/quilômetros
- Atualização automática conforme zoom

### **6. Navegação Contínua**

**Botões:**
- Início (ícone de casa)
- Gráfico (ícone de gráfico de barras)

**Características:**
- Posicionados verticalmente no topo esquerdo
- Hover com cores específicas
- Efeito de deslocamento ao passar o mouse

---

## 📊 Funcionalidades do Gráfico (`/grafico`)

### **1. Gráfico de Temperatura**

**Tipo:** Gráfico de linhas (line chart)

**Características:**
- Cor: Vermelho (#FF6384)
- Dados mensais (12 meses)
- Linha suavizada (tension: 0.4)
- Pontos destacados
- Eixo Y à esquerda

**Configuração:**
```javascript
{
    label: 'Temperatura (°C)',
    data: temperatureData,
    type: 'line',
    borderColor: 'rgb(255, 99, 132)',
    backgroundColor: 'rgba(255, 99, 132, 0.1)',
    borderWidth: 3,
    fill: true,
    tension: 0.4
}
```

### **2. Gráfico de Precipitação**

**Tipo:** Gráfico de barras (bar chart)

**Características:**
- Cor: Azul (#36A2EB)
- Dados mensais (12 meses)
- Barras com bordas arredondadas
- Eixo Y à direita

**Configuração:**
```javascript
{
    label: 'Precipitação (mm)',
    data: precipitationData,
    type: 'bar',
    backgroundColor: 'rgba(54, 162, 235, 0.7)',
    borderColor: 'rgb(54, 162, 235)',
    borderWidth: 2,
    borderRadius: 8
}
```

### **3. Botões de Controle**

**Opções:**
- **Ambos:** Exibe temperatura e precipitação simultaneamente
- **Temperatura:** Exibe apenas gráfico de temperatura
- **Precipitação:** Exibe apenas gráfico de precipitação

**Função:**
```javascript
function toggleDataset(view) {
    currentView = view;
    
    if (view === 'both') {
        chart.data.datasets[0].hidden = false;
        chart.data.datasets[1].hidden = false;
    } else if (view === 'temperature') {
        chart.data.datasets[0].hidden = false;
        chart.data.datasets[1].hidden = true;
    } else if (view === 'precipitation') {
        chart.data.datasets[0].hidden = true;
        chart.data.datasets[1].hidden = false;
    }
    
    chart.update('active');
}
```

### **4. Características Adicionais**

- Tooltip interativo ao passar o mouse
- Legenda customizada
- Dois eixos Y (temperatura e precipitação)
- Animações suaves
- Design responsivo

### **5. Navegação Contínua**

**Botões:**
- Início (ícone de casa)
- Mapa (ícone de mapa)

---

## 🧪 Testes Automatizados (Cypress)

### **Estrutura de Testes**

```
cypress/
├── e2e/
│   ├── home.cy.js       # 9 testes da página inicial
│   ├── mapa.cy.js       # 35+ testes do mapa
│   └── grafico.cy.js    # 40+ testes dos gráficos
├── support/
│   ├── commands.js      # Comandos customizados
│   └── e2e.js          # Configurações globais
└── cypress.config.js    # Configuração principal
```

### **Cobertura de Testes**

**Página Inicial (9 testes):**
- Carregamento da página
- Exibição dos cartões
- Navegação para mapa e gráfico
- Efeitos hover

**Mapa (35+ testes):**
- Carregamento do mapa
- Componente de coordenadas
- Context menu com data/hora
- Botões de zoom
- Alternar mapa de fundo
- Navegação contínua
- Barra de escala

**Gráficos (40+ testes):**
- Carregamento do Chart.js
- Datasets de temperatura e precipitação
- Botões de alternar visualização
- Tipos de gráfico
- Legenda customizada
- Responsividade

### **Comandos Customizados**

```javascript
// Aguardar carregamento do mapa
Cypress.Commands.add('waitForMap', () => {
    cy.get('#map').should('be.visible')
    cy.wait(2000)
})

// Aguardar carregamento do gráfico
Cypress.Commands.add('waitForChart', () => {
    cy.get('#myChart').should('be.visible')
    cy.wait(1000)
})

// Abrir popup do mapa
Cypress.Commands.add('openMapPopup', () => {
    cy.get('#map').rightclick(500, 300)
    cy.get('.ol-popup').should('be.visible')
})
```

---

## 🎨 Boas Práticas Implementadas

### **Código**
- ✅ Funções documentadas com docstrings
- ✅ Comentários explicativos
- ✅ Nomenclatura clara e descritiva
- ✅ Separação de responsabilidades
- ✅ Código modular e reutilizável

### **UI/UX**
- ✅ Design responsivo
- ✅ Feedback visual em interações
- ✅ Navegação intuitiva
- ✅ Cores e ícones consistentes
- ✅ Animações suaves
- ✅ Acessibilidade

### **Testes**
- ✅ Cobertura completa de funcionalidades
- ✅ Testes organizados por página
- ✅ Comandos customizados reutilizáveis
- ✅ Asserções claras e específicas

---

## 🛠️ Tecnologias Utilizadas

### **Backend**
- Laravel 9.x
- PHP 8.0+

### **Frontend**
- OpenLayers 8.2.0 (mapas)
- Chart.js 4.4.0 (gráficos)
- Font Awesome 6.4.0 (ícones)
- TailwindCSS (inline)

### **Testes**
- Cypress 13.6.0

### **Ferramentas**
- Vite (build tool)
- Composer (PHP)
- NPM (JavaScript)

---

## 📝 Manutenção e Extensão

### **Adicionar Novos Dados ao Gráfico**

Edite os arrays em `resources/views/grafico.blade.php`:

```javascript
const temperatureData = [22, 24, 23, ...]; // Adicione valores
const precipitationData = [180, 160, 140, ...]; // Adicione valores
const labels = ['Jan', 'Fev', 'Mar', ...]; // Adicione labels
```

### **Adicionar Novas Camadas ao Mapa**

Em `resources/views/mapa.blade.php`:

```javascript
const novaLayer = new ol.layer.Tile({
    source: new ol.source.XYZ({
        url: 'URL_DA_CAMADA'
    }),
    visible: false
});

map.addLayer(novaLayer);
```

### **Adicionar Novos Testes**

Crie um novo arquivo em `cypress/e2e/`:

```javascript
describe('Nova Funcionalidade', () => {
    beforeEach(() => {
        cy.visit('/rota')
    })
    
    it('deve fazer algo', () => {
        // Seu teste aqui
    })
})
```

---

## 🔗 Links Úteis

- [Documentação Laravel 9](https://laravel.com/docs/9.x)
- [Documentação OpenLayers](https://openlayers.org/)
- [Documentação Chart.js](https://www.chartjs.org/)
- [Documentação Cypress](https://docs.cypress.io/)

---

**Desenvolvido para AgronomiQ - Teste Técnico Backend**

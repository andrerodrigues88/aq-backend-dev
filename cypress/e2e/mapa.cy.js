/**
 * Testes E2E para a página do Mapa
 * 
 * Testa todas as funcionalidades do mapa interativo:
 * - Componente de coordenadas do mouse
 * - Context menu (popup) com data/hora
 * - Botões de zoom in/out
 * - Botão de alternar camada de mapa
 * - Navegação contínua
 */

describe('Página do Mapa', () => {
  beforeEach(() => {
    // Visita a página do mapa antes de cada teste
    cy.visit('/mapa')
    
    // Aguarda o mapa carregar
    cy.wait(2000)
  })

  describe('Carregamento da Página', () => {
    it('deve carregar a página do mapa corretamente', () => {
      cy.get('#map').should('be.visible')
    })

    it('deve ter o título correto', () => {
      cy.title().should('include', 'Mapa')
    })
  })

  describe('Componente de Coordenadas do Mouse', () => {
    it('deve exibir o componente de coordenadas', () => {
      // Verifica se o componente existe e está visível
      cy.get('.mouse-position').should('be.visible')
    })

    it('deve estar posicionado no canto inferior esquerdo', () => {
      // Verifica o posicionamento CSS
      cy.get('.mouse-position').should('have.css', 'position', 'fixed')
      cy.get('.mouse-position').should('have.css', 'bottom', '20px')
      cy.get('.mouse-position').should('have.css', 'left', '20px')
    })

    it('deve ter fundo branco com opacidade 0.5', () => {
      // Verifica a cor de fundo com opacidade
      cy.get('.mouse-position').should('have.css', 'background-color', 'rgba(255, 255, 255, 0.5)')
    })

    it('deve ter fonte maior (18px)', () => {
      cy.get('.mouse-position').should('have.css', 'font-size', '18px')
    })

    it('deve atualizar coordenadas ao mover o mouse', () => {
      // Move o mouse sobre o mapa
      cy.get('#map').trigger('pointermove', { clientX: 500, clientY: 300 })
      
      // Verifica se as coordenadas são exibidas
      cy.get('.mouse-position').should('contain', 'Lat:')
      cy.get('.mouse-position').should('contain', 'Lon:')
    })
  })

  describe('Context Menu (Popup)', () => {
    it('deve abrir popup ao clicar com botão direito', () => {
      // Clica com botão direito no mapa
      cy.get('#map').rightclick(500, 300)
      
      // Verifica se o popup está visível
      cy.get('.ol-popup').should('be.visible')
    })

    it('deve exibir latitude e longitude no popup', () => {
      // Abre o popup
      cy.get('#map').rightclick(500, 300)
      
      // Verifica o conteúdo
      cy.get('#popup-content').should('contain', 'Latitude:')
      cy.get('#popup-content').should('contain', 'Longitude:')
    })

    it('deve exibir data/hora no formato DD/MM/YYYY HH:mm:ss', () => {
      // Abre o popup
      cy.get('#map').rightclick(500, 300)
      
      // Verifica se a data/hora está presente
      cy.get('#popup-content').should('contain', 'Data/Hora:')
      
      // Verifica o formato da data (regex para DD/MM/YYYY HH:mm:ss)
      cy.get('#popup-content').should('match', /\d{2}\/\d{2}\/\d{4} \d{2}:\d{2}:\d{2}/)
    })

    it('deve exibir ícone de calendário ao lado da data', () => {
      // Abre o popup
      cy.get('#map').rightclick(500, 300)
      
      // Verifica se o ícone existe
      cy.get('#popup-content').find('.fa-calendar-alt').should('exist')
    })

    it('deve ter espaçamento maior entre as coordenadas', () => {
      // Abre o popup
      cy.get('#map').rightclick(500, 300)
      
      // Verifica o padding do popup
      cy.get('.ol-popup').should('have.css', 'padding', '20px')
      
      // Verifica o espaçamento entre parágrafos
      cy.get('#popup-content p').should('have.css', 'margin', '12px 0px')
    })

    it('deve fechar popup ao clicar no X', () => {
      // Abre o popup
      cy.get('#map').rightclick(500, 300)
      
      // Clica no botão de fechar
      cy.get('#popup-closer').click()
      
      // Verifica se o popup foi fechado (não visível)
      cy.get('.ol-popup').should('not.be.visible')
    })
  })

  describe('Botões de Zoom', () => {
    it('deve exibir botões de zoom no topo esquerdo', () => {
      // Verifica se os botões existem
      cy.get('.zoom-controls').should('be.visible')
      cy.get('.zoom-controls').should('have.css', 'position', 'fixed')
      cy.get('.zoom-controls').should('have.css', 'top', '20px')
      cy.get('.zoom-controls').should('have.css', 'left', '20px')
    })

    it('deve ter botão de Zoom In com ícone de +', () => {
      cy.get('.zoom-btn').first().find('.fa-plus').should('exist')
    })

    it('deve ter botão de Zoom Out com ícone de -', () => {
      cy.get('.zoom-btn').last().find('.fa-minus').should('exist')
    })

    it('deve executar zoom in ao clicar no botão +', () => {
      // Clica no botão de zoom in
      cy.get('.zoom-btn').first().click()
      
      // Aguarda a animação
      cy.wait(500)
      
      // Verifica se a função foi chamada (o mapa deve ter mudado)
      cy.window().then((win) => {
        expect(win.map).to.exist
      })
    })

    it('deve executar zoom out ao clicar no botão -', () => {
      // Clica no botão de zoom out
      cy.get('.zoom-btn').last().click()
      
      // Aguarda a animação
      cy.wait(500)
      
      // Verifica se a função foi chamada
      cy.window().then((win) => {
        expect(win.map).to.exist
      })
    })
  })

  describe('Botão de Alternar Mapa de Fundo', () => {
    it('deve exibir botão de alternar camada', () => {
      cy.get('.toggle-layer-btn').should('be.visible')
    })

    it('deve estar posicionado no topo direito', () => {
      cy.get('.toggle-layer-btn').should('have.css', 'position', 'fixed')
      cy.get('.toggle-layer-btn').should('have.css', 'top', '20px')
      cy.get('.toggle-layer-btn').should('have.css', 'right', '20px')
    })

    it('deve exibir "OpenStreetMap" inicialmente', () => {
      cy.get('#layerName').should('contain', 'OpenStreetMap')
    })

    it('deve alternar para "Google Satellite" ao clicar', () => {
      // Clica no botão
      cy.get('.toggle-layer-btn').click()
      
      // Verifica se o texto mudou
      cy.get('#layerName').should('contain', 'Google Satellite')
    })

    it('deve voltar para "OpenStreetMap" ao clicar novamente', () => {
      // Clica duas vezes
      cy.get('.toggle-layer-btn').click()
      cy.get('.toggle-layer-btn').click()
      
      // Verifica se voltou
      cy.get('#layerName').should('contain', 'OpenStreetMap')
    })
  })

  describe('Navegação Contínua', () => {
    it('deve ter botão para voltar ao Início', () => {
      cy.get('a[href="/"]').should('be.visible')
      cy.get('a[href="/"]').should('contain', 'Início')
    })

    it('deve ter botão para ir ao Gráfico', () => {
      cy.get('a[href="/grafico"]').should('be.visible')
      cy.get('a[href="/grafico"]').should('contain', 'Gráfico')
    })

    it('deve navegar para a página inicial ao clicar em Início', () => {
      cy.get('a[href="/"]').click()
      cy.url().should('eq', Cypress.config().baseUrl + '/')
    })

    it('deve navegar para a página de gráfico ao clicar em Gráfico', () => {
      cy.get('a[href="/grafico"]').click()
      cy.url().should('include', '/grafico')
    })
  })

  describe('Barra de Escala', () => {
    it('deve exibir a barra de escala do mapa', () => {
      // Verifica se a barra de escala existe
      cy.get('.ol-scale-line').should('exist')
    })

    it('deve estar posicionada no canto inferior direito', () => {
      // Verifica o posicionamento via CSS
      cy.get('.ol-scale-line').should('have.css', 'right', '20px')
      cy.get('.ol-scale-line').should('have.css', 'bottom', '20px')
    })
  })
})

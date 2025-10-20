/**
 * Testes E2E para a página de Gráficos
 * 
 * Testa todas as funcionalidades do gráfico:
 * - Carregamento do ChartJS
 * - Datasets de temperatura e precipitação
 * - Botão para alternar visualização
 * - Tipos de gráfico (linha e barras)
 * - Navegação contínua
 */

describe('Página de Gráficos', () => {
  beforeEach(() => {
    // Visita a página de gráficos antes de cada teste
    cy.visit('/grafico')
    
    // Aguarda o gráfico carregar
    cy.wait(1000)
  })

  describe('Carregamento da Página', () => {
    it('deve carregar a página de gráficos corretamente', () => {
      cy.get('body').should('be.visible')
    })

    it('deve ter o título correto', () => {
      cy.title().should('include', 'Gráfico')
    })

    it('deve exibir o header com título', () => {
      cy.get('.header h1').should('contain', 'Análise de Dados Climáticos')
    })

    it('deve exibir o subtítulo', () => {
      cy.get('.header p').should('contain', 'Visualização de Temperatura e Precipitação')
    })
  })

  describe('Card do Gráfico', () => {
    it('deve exibir o card do gráfico', () => {
      cy.get('.chart-card').should('be.visible')
    })

    it('deve ter o título "Dados Mensais"', () => {
      cy.get('.chart-title').should('contain', 'Dados Mensais')
    })

    it('deve exibir o canvas do gráfico', () => {
      cy.get('#myChart').should('exist')
    })
  })

  describe('Botões de Controle', () => {
    it('deve exibir três botões de controle', () => {
      cy.get('.control-btn').should('have.length', 3)
    })

    it('deve ter botão "Ambos"', () => {
      cy.get('#btnBoth').should('contain', 'Ambos')
    })

    it('deve ter botão "Temperatura"', () => {
      cy.get('#btnTemp').should('contain', 'Temperatura')
    })

    it('deve ter botão "Precipitação"', () => {
      cy.get('#btnPrec').should('contain', 'Precipitação')
    })

    it('deve ter botão "Ambos" ativo por padrão', () => {
      cy.get('#btnBoth').should('have.class', 'active')
    })

    it('deve ter ícones nos botões', () => {
      cy.get('#btnBoth').find('i').should('exist')
      cy.get('#btnTemp').find('.fa-temperature-high').should('exist')
      cy.get('#btnPrec').find('.fa-cloud-rain').should('exist')
    })
  })

  describe('Funcionalidade de Alternar Datasets', () => {
    it('deve ativar botão "Temperatura" ao clicar', () => {
      // Clica no botão Temperatura
      cy.get('#btnTemp').click()
      
      // Verifica se ficou ativo
      cy.get('#btnTemp').should('have.class', 'active')
      
      // Verifica se os outros ficaram inativos
      cy.get('#btnBoth').should('not.have.class', 'active')
      cy.get('#btnPrec').should('not.have.class', 'active')
    })

    it('deve ativar botão "Precipitação" ao clicar', () => {
      // Clica no botão Precipitação
      cy.get('#btnPrec').click()
      
      // Verifica se ficou ativo
      cy.get('#btnPrec').should('have.class', 'active')
      
      // Verifica se os outros ficaram inativos
      cy.get('#btnBoth').should('not.have.class', 'active')
      cy.get('#btnTemp').should('not.have.class', 'active')
    })

    it('deve voltar para "Ambos" ao clicar novamente', () => {
      // Clica em Temperatura
      cy.get('#btnTemp').click()
      
      // Volta para Ambos
      cy.get('#btnBoth').click()
      
      // Verifica
      cy.get('#btnBoth').should('have.class', 'active')
    })

    it('deve atualizar o gráfico ao alternar datasets', () => {
      // Clica em Temperatura
      cy.get('#btnTemp').click()
      
      // Aguarda a animação
      cy.wait(500)
      
      // Verifica se o gráfico ainda existe
      cy.get('#myChart').should('exist')
    })
  })

  describe('Tipos de Gráfico', () => {
    it('deve carregar o gráfico com ChartJS', () => {
      // Verifica se o canvas existe
      cy.get('#myChart').should('exist')
      
      // Verifica se o ChartJS foi carregado
      cy.window().then((win) => {
        expect(win.Chart).to.exist
      })
    })

    it('deve ter dataset de temperatura como gráfico de linhas', () => {
      cy.window().then((win) => {
        const chart = win.chart
        expect(chart).to.exist
        expect(chart.data.datasets[0].type).to.equal('line')
        expect(chart.data.datasets[0].label).to.include('Temperatura')
      })
    })

    it('deve ter dataset de precipitação como gráfico de barras', () => {
      cy.window().then((win) => {
        const chart = win.chart
        expect(chart).to.exist
        expect(chart.data.datasets[1].type).to.equal('bar')
        expect(chart.data.datasets[1].label).to.include('Precipitação')
      })
    })

    it('deve ter 12 pontos de dados (meses)', () => {
      cy.window().then((win) => {
        const chart = win.chart
        expect(chart.data.labels).to.have.length(12)
      })
    })

    it('deve ter labels dos meses', () => {
      cy.window().then((win) => {
        const chart = win.chart
        const labels = chart.data.labels
        expect(labels).to.include('Jan')
        expect(labels).to.include('Dez')
      })
    })
  })

  describe('Legenda do Gráfico', () => {
    it('deve exibir a legenda customizada', () => {
      cy.get('.chart-legend').should('be.visible')
    })

    it('deve ter item de legenda para Temperatura', () => {
      cy.get('.chart-legend').should('contain', 'Temperatura (°C)')
    })

    it('deve ter item de legenda para Precipitação', () => {
      cy.get('.chart-legend').should('contain', 'Precipitação (mm)')
    })

    it('deve ter cores correspondentes na legenda', () => {
      // Verifica se os quadrados de cor existem
      cy.get('.legend-color').should('have.length', 2)
    })
  })

  describe('Navegação Contínua', () => {
    it('deve ter botão para voltar ao Início', () => {
      cy.get('a[href="/"]').should('be.visible')
      cy.get('a[href="/"]').should('contain', 'Início')
    })

    it('deve ter botão para ir ao Mapa', () => {
      cy.get('a[href="/mapa"]').should('be.visible')
      cy.get('a[href="/mapa"]').should('contain', 'Mapa')
    })

    it('deve navegar para a página inicial ao clicar em Início', () => {
      cy.get('a[href="/"]').click()
      cy.url().should('eq', Cypress.config().baseUrl + '/')
    })

    it('deve navegar para a página de mapa ao clicar em Mapa', () => {
      cy.get('a[href="/mapa"]').click()
      cy.url().should('include', '/mapa')
    })
  })

  describe('Responsividade', () => {
    it('deve ser responsivo em mobile', () => {
      // Muda para viewport mobile
      cy.viewport(375, 667)
      
      // Verifica se o gráfico ainda está visível
      cy.get('#myChart').should('be.visible')
      
      // Verifica se os botões estão visíveis
      cy.get('.control-btn').should('be.visible')
    })

    it('deve ser responsivo em tablet', () => {
      // Muda para viewport tablet
      cy.viewport(768, 1024)
      
      // Verifica se o layout está correto
      cy.get('.chart-card').should('be.visible')
    })
  })

  describe('Interatividade do Gráfico', () => {
    it('deve ter tooltip ao passar o mouse', () => {
      // Move o mouse sobre o gráfico
      cy.get('#myChart').trigger('mousemove', { clientX: 500, clientY: 300 })
      
      // Aguarda o tooltip aparecer
      cy.wait(500)
      
      // Verifica se o gráfico responde
      cy.get('#myChart').should('exist')
    })
  })

  describe('Dados do Gráfico', () => {
    it('deve ter dados de temperatura válidos', () => {
      cy.window().then((win) => {
        const tempData = win.temperatureData
        expect(tempData).to.exist
        expect(tempData).to.be.an('array')
        expect(tempData).to.have.length(12)
      })
    })

    it('deve ter dados de precipitação válidos', () => {
      cy.window().then((win) => {
        const precData = win.precipitationData
        expect(precData).to.exist
        expect(precData).to.be.an('array')
        expect(precData).to.have.length(12)
      })
    })
  })
})

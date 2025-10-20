/**
 * Testes E2E para a página inicial
 * 
 * Testa a funcionalidade dos cartões de navegação
 * para Mapa e Gráfico
 */

describe('Página Inicial', () => {
  beforeEach(() => {
    // Visita a página inicial antes de cada teste
    cy.visit('/')
  })

  it('deve carregar a página inicial corretamente', () => {
    // Verifica se a página carregou
    cy.get('body').should('be.visible')
  })

  it('deve exibir o logo do Laravel', () => {
    // Verifica se o logo SVG está presente
    cy.get('svg').should('exist')
  })

  it('deve exibir dois cartões de navegação', () => {
    // Verifica se existem exatamente 2 links de cartão
    cy.get('a[href="/mapa"]').should('exist')
    cy.get('a[href="/grafico"]').should('exist')
  })

  it('deve exibir o cartão do Mapa com título e descrição', () => {
    // Verifica o conteúdo do cartão Mapa
    cy.get('a[href="/mapa"]').within(() => {
      cy.contains('Mapa').should('be.visible')
      cy.contains('Visualize dados geográficos').should('be.visible')
    })
  })

  it('deve exibir o cartão do Gráfico com título e descrição', () => {
    // Verifica o conteúdo do cartão Gráfico
    cy.get('a[href="/grafico"]').within(() => {
      cy.contains('Gráfico').should('be.visible')
      cy.contains('Analise dados através').should('be.visible')
    })
  })

  it('deve navegar para a página do Mapa ao clicar no cartão', () => {
    // Clica no cartão do Mapa
    cy.get('a[href="/mapa"]').click()
    
    // Verifica se navegou para /mapa
    cy.url().should('include', '/mapa')
  })

  it('deve navegar para a página do Gráfico ao clicar no cartão', () => {
    // Clica no cartão do Gráfico
    cy.get('a[href="/grafico"]').click()
    
    // Verifica se navegou para /grafico
    cy.url().should('include', '/grafico')
  })

  it('deve ter efeito hover nos cartões', () => {
    // Verifica se os cartões têm a classe de transição
    cy.get('a[href="/mapa"]').should('have.class', 'transition')
    cy.get('a[href="/grafico"]').should('have.class', 'transition')
  })
})

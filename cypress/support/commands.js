/**
 * Comandos customizados do Cypress
 * 
 * Este arquivo permite criar comandos reutilizáveis
 * para os testes E2E
 */

/**
 * Comando para fazer login (exemplo)
 * Uso: cy.login('email@example.com', 'password')
 */
// Cypress.Commands.add('login', (email, password) => {
//   cy.visit('/login')
//   cy.get('input[name="email"]').type(email)
//   cy.get('input[name="password"]').type(password)
//   cy.get('button[type="submit"]').click()
// })

/**
 * Comando para aguardar o carregamento do mapa
 * Uso: cy.waitForMap()
 */
Cypress.Commands.add('waitForMap', () => {
  cy.get('#map').should('be.visible')
  cy.wait(2000) // Aguarda o mapa carregar completamente
})

/**
 * Comando para aguardar o carregamento do gráfico
 * Uso: cy.waitForChart()
 */
Cypress.Commands.add('waitForChart', () => {
  cy.get('#myChart').should('be.visible')
  cy.wait(1000) // Aguarda o gráfico carregar completamente
})

/**
 * Comando para abrir o popup do mapa
 * Uso: cy.openMapPopup()
 */
Cypress.Commands.add('openMapPopup', () => {
  cy.get('#map').rightclick(500, 300)
  cy.get('.ol-popup').should('be.visible')
})

/**
 * Comando para verificar se um elemento está no viewport
 * Uso: cy.get('.elemento').isInViewport()
 */
Cypress.Commands.add('isInViewport', { prevSubject: true }, (subject) => {
  const rect = subject[0].getBoundingClientRect()
  
  expect(rect.top).to.be.at.least(0)
  expect(rect.left).to.be.at.least(0)
  expect(rect.bottom).to.be.at.most(Cypress.config('viewportHeight'))
  expect(rect.right).to.be.at.most(Cypress.config('viewportWidth'))
  
  return subject
})

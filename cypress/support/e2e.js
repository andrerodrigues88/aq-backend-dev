/**
 * Arquivo de suporte do Cypress
 * 
 * Este arquivo é carregado automaticamente antes dos testes
 * e pode conter comandos customizados e configurações globais
 */

// Import commands.js using ES2015 syntax:
import './commands'

// Alternatively you can use CommonJS syntax:
// require('./commands')

/**
 * Configurações globais para todos os testes
 */

// Desabilita verificação de erros não capturados
Cypress.on('uncaught:exception', (err, runnable) => {
  // returning false here prevents Cypress from failing the test
  return false
})

/**
 * Hooks globais
 */

// Antes de todos os testes
before(() => {
  cy.log('Iniciando suite de testes E2E')
})

// Depois de todos os testes
after(() => {
  cy.log('Finalizando suite de testes E2E')
})

const { defineConfig } = require('cypress')

/**
 * Configuração do Cypress para testes E2E
 * 
 * Este arquivo configura o Cypress para testar a aplicação Laravel
 * rodando em http://localhost:8000
 */
module.exports = defineConfig({
  e2e: {
    // URL base da aplicação
    baseUrl: 'http://localhost:8000',
    
    // Viewport padrão
    viewportWidth: 1280,
    viewportHeight: 720,
    
    // Timeout padrão para comandos
    defaultCommandTimeout: 10000,
    
    // Configuração de vídeos e screenshots
    video: true,
    screenshotOnRunFailure: true,
    
    // Pasta de testes
    specPattern: 'cypress/e2e/**/*.cy.{js,jsx,ts,tsx}',
    
    setupNodeEvents(on, config) {
      // implement node event listeners here
    },
  },
})

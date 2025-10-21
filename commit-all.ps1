# Script para fazer commits organizados
# Execute: .\commit-all.ps1

Write-Host "=== INICIANDO COMMITS ORGANIZADOS ===" -ForegroundColor Green

# Commit 1: Página inicial
Write-Host "`n[1/7] Commitando página inicial..." -ForegroundColor Yellow
git add resources/views/welcome.blade.php
git commit -m "feat: adiciona pagina inicial com cartoes de navegacao"

# Commit 2: Rotas
Write-Host "`n[2/7] Commitando rotas..." -ForegroundColor Yellow
git add routes/web.php
git commit -m "feat: adiciona rotas /mapa e /grafico com documentacao completa"

# Commit 3: Mapa
Write-Host "`n[3/7] Commitando mapa interativo..." -ForegroundColor Yellow
git add resources/views/mapa.blade.php
git commit -m "feat: implementa mapa interativo com OpenLayers e todas funcionalidades"

# Commit 4: Gráfico
Write-Host "`n[4/7] Commitando gráficos..." -ForegroundColor Yellow
git add resources/views/grafico.blade.php
git commit -m "feat: adiciona graficos com Chart.js e alternancia de datasets"

# Commit 5: Testes Cypress
Write-Host "`n[5/7] Commitando testes..." -ForegroundColor Yellow
git add cypress/ cypress.config.js package.json package-lock.json
git commit -m "test: adiciona suite completa de testes E2E com Cypress"

# Commit 6: Documentação
Write-Host "`n[6/7] Commitando documentação..." -ForegroundColor Yellow
git add app/Http/Controllers/Controller.php app/Providers/AppServiceProvider.php FUNCIONALIDADES.md README.md
git commit -m "docs: adiciona documentacao completa de codigo e funcionalidades"

# Commit 7: Estilos
Write-Host "`n[7/7] Commitando estilos..." -ForegroundColor Yellow
git add resources/css/app.css
git commit -m "style: adiciona estilos customizados no app.css"

Write-Host "`n=== COMMITS FINALIZADOS COM SUCESSO! ===" -ForegroundColor Green
Write-Host "`nVerifique o log:" -ForegroundColor Cyan
git log --oneline -7

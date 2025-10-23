# Script para corrigir o commit
git commit -m "feat: versao inicial com todas as alteracoes implementadas"
git push origin main --force
git branch -D desenvolvimento
git checkout -b desenvolvimento
git push origin desenvolvimento --force

Write-Host "`n=== COMMIT CORRIGIDO COM SUCESSO! ===" -ForegroundColor Green
git log --oneline -1

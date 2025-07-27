# 🔧 INSTRUÇÕES PARA TESTE DOS PLANOS DE EMPRÉSTIMO

## 🚨 PROBLEMA IDENTIFICADO
O loop infinito na segunda visualização indica um possível problema no modelo LoanPlanModel ou cache do CodeIgniter.

## 📋 TESTES A REALIZAR (EM ORDEM)

### 1. TESTE DA VERSÃO DEBUG (MAIS SEGURA)
```
URL: https://amxcred-code/settings/loan-plans/debug/1
```
- Esta versão usa dados estáticos e conexão direta ao banco
- Se funcionar: o problema está no modelo original
- Se não funcionar: o problema é mais profundo

### 2. TESTE DA VERSÃO SIMPLIFICADA ATUAL
```
URL: https://amxcred-code/settings/loan-plans/view/1
```
- Esta versão evita o modelo e usa query direta
- Tem logs de debug habilitados

### 3. VERIFICAR LOGS DE DEBUG
```
Arquivo: writable/logs/log-[data].php
```
Procure por linhas com:
- `viewLoanPlan chamado para ID:`
- `Dados do plano preparados`
- `Erro crítico em viewLoanPlan:`

### 4. TESTE DE CONEXÃO COM BANCO
```
Comando: php debug_loan_plan.php
```
Execute no terminal para verificar se o banco está OK.

## 🔍 SEQUÊNCIA DE TESTE RECOMENDADA

1. **Primeiro**: Teste a URL debug (`/debug/1`)
2. **Se funcionar**: O problema está no modelo original
3. **Se não funcionar**: Execute o debug do banco (`php debug_loan_plan.php`)
4. **Depois**: Teste a URL normal (`/view/1`) e verifique os logs

## 📊 POSSÍVEIS CAUSAS DO PROBLEMA

### A. Problema no Modelo (mais provável)
- Método `findWithCalculations()` com loop infinito
- Cache do CodeIgniter corrompido
- Validações do modelo causando recursão

### B. Problema no Banco de Dados
- Dados corrompidos na tabela
- Constraint ou trigger problemático
- Conexão instável

### C. Problema de Memória/PHP
- Limite de memória do PHP
- Timeout de execução
- Configuração do MAMP

## 🛠️ SOLUÇÕES IMPLEMENTADAS

### Versão Debug (`SettingsControllerDebug`)
- Dados completamente estáticos
- Sem uso do modelo
- Logs detalhados

### Versão Simplificada (`SettingsController::viewLoanPlan`)
- Query SQL direta
- Cálculos manuais simples
- Evita o modelo LoanPlanModel

### View Ultra-Simplificada (`loan_plan_view_simple`)
- Sem loops ou operações complexas
- Apenas exibição de dados
- HTML estático

## 📞 PRÓXIMOS PASSOS

1. Teste as URLs acima
2. Reporte qual funciona e qual não funciona
3. Se nenhuma funcionar, verifique os logs do Apache/PHP no MAMP
4. Considere reiniciar o MAMP completamente

## 🔧 COMANDOS ÚTEIS

```bash
# Limpar cache do CodeIgniter
rm -rf writable/cache/*

# Ver logs em tempo real
tail -f writable/logs/log-$(date +%Y-%m-%d).php

# Testar conexão com banco
php debug_loan_plan.php
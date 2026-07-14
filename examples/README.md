# Exemplos

Os exemplos usam dados fictícios. Troque URLs, POS ID e credenciais pelos
valores fornecidos pela entidade adquirente.

- `php-puro/ciclo-completo.php`: cliente PHP puro com PDO SQLite, criação de
  pagamento e HTML de redirecionamento.
- `laravel/PaymentController.php`: controller simples com injecção de
  `Kowts\Sisp\Sisp`.
- `symfony/PaymentController.php`: controller simples com autowiring.
- `yii2/PaymentController.php`: controller simples usando `Yii::$app->sisp`.

Não exponha `posAutCode`, tokens, dados reais de cartão ou recibos reais nos
exemplos da aplicação.

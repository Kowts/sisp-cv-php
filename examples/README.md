# Exemplos

Os exemplos usam dados ficticios. Troque URLs, POS ID e credenciais pelos
valores fornecidos pela entidade adquirente.

- `php-puro/ciclo-completo.php`: cliente PHP puro com PDO SQLite, criacao de
  pagamento e HTML de redirecionamento.
- `laravel/PaymentController.php`: controller simples com injeccao de
  `Kowts\Sisp\Sisp`.
- `symfony/PaymentController.php`: controller simples com autowiring.
- `yii2/PaymentController.php`: controller simples usando `Yii::$app->sisp`.

Nao exponha `posAutCode`, tokens, dados reais de cartao ou recibos reais nos
exemplos da aplicacao.

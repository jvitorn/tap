<html lang="pt-BR">
  <head>
    <meta charset="utf-8">
    <title>MVC | <?= $this->get('title'); ?></title>
    <meta name="description" content="<?= $this->get('description'); ?>">
    <meta name="keywords" content="<?= $this->get('keywords'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Fonts  -->
    <link href='https://fonts.googleapis.com/css?family=Raleway:400,500,600,700,300' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="<?= DIR_CSS; ?>bootstrap/bootstrap.min.css">
   	<link rel="stylesheet" href="<?= DIR_CSS; ?>style.css">
   	<link rel="icon" href="<?= DIR_IMG; ?>favicon.ico">
  </head>
  <body>
    <main>
      <!-- carrega conteudo principal -->
      <?= $this->main(); ?>
    </main>
    <footer>
      <script src="<?= DIR_JS; ?>jquery-3.4.1.min.js"></script>
      <script src="<?= DIR_JS; ?>global.js"></script>
      <script src="<?= DIR_JS; ?>vanilla-masker/lib/vanilla-masker.js"></script>
      <script src="<?= DIR_JS; ?>bootstrap/bootstrap.min.js"></script>
      <script src="<?= DIR_JS; ?>mascaras.js"></script>
      <script src="<?= DIR_JS; ?>validacoes.js"></script>
      <script type="text/javascript" src="<?= DIR_JS; ?>main.js"></script>
      <!-- carrega models e scripts especificos -->
      <?= $this->footer(); ?>
    </footer>
  </body>
</html>
<!--aqui va el header-->
<html lang="<?= $lang ?>">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=<?= $chatset ?>" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <title><?= $title?$title:'NO title'; ?></title>
        <link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css">
        <link rel="stylesheet" href="assets/css/style.css" type="text/css">
        <script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
        <script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="assets/js/scripts.js"></script>
    </head>
    <body>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger alert-dismissible fade in text-center" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
            <p><?= _('Error: ')?><?= $error ?></p>
        </div>
    <?php endif; ?>
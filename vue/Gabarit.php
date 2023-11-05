<!doctype html>
<html lang="fr">
<head>
    <title><?php echo $titre ?></title>
    <meta charset="utf-8">
    <!-- Pour cellulaire -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
</head>
<body>

<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <?php require_once 'vue/Menu.php';?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <H1 class="text-center"> <?php echo $titre ?></H1>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <?php echo $contenu ?>
        </div>
    </div>
</div>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
</body>
</html>
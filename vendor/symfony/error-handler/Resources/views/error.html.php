<!DOCTYPE html>
<html>
<head>
    <meta charset="<?= $this->charset; ?>" />
    <meta name="robots" content="noindex,nofollow,noarchive" />
    <title>Error: <?= $statusText; ?></title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-6 bg-light my-3 mr-auto ml-auto text-center">
            <h1 class="text-danger ">Oops! An Error Occurred</h1>
            <h3 class="text-info">The server returned a "<x class="text-dark"><?= $statusCode; ?> <?= $statusText; ?></x>".</h3>

            <p>
                Something is broken. Please let us know what you were doing when this error occurred.
                We will fix it as soon as possible. Sorry for any inconvenience caused.

            </p>
            <a href="/">Back to homepage</a>
        </div>
    </div>
</div>
</body>
</html>

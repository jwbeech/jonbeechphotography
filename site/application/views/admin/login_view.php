<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">

    <title>Login</title>

    <link href="/static/css/main.css" rel="stylesheet">
    <link href="/static/css/bootstrap.css" rel="stylesheet">

    <script type="text/javascript" src="/static/js/jquery-2.1.3.js"></script>
    <script type="text/javascript" src="/static/js/bootstrap.js"></script>
</head>

<body>
    <div id="loginWrapper">
        <h1>Login</h1>

        <?php echo validation_errors(); ?>

        <?php echo form_open('admin/login'); ?>
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" name="username" id="username" placeholder="Username">
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" name="password" id="password" placeholder="Password">
            </div>
            <button type="submit" class="btn btn-success pull-right">Login</button>
        </form>
    </div>
</body>
</html>
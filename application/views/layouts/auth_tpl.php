<?php
$title = $this->settings->get(1);
$title = $title->site_title;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo $template['title'].' | '.$title; ?></title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet" />

    <style type="text/css">
        @import url(https://fonts.googleapis.com/css?family=Lato);

        body {
            background: url(/assets/img/parallax/back.png);
            background-color: #444;
            background: url(/assets/img/parallax/pinlayer2.png), url(/assets/img/parallax/pinlayer1.png), url(/assets/img/parallax/back.png);
            font-family: 'lato';
        }

        .vertical-offset-100 {
            padding-top: 100px;
        }

        label{
            color:#999999 !important;
            font-weight: 100;
        }

        h3 {
            font-size: 22px ! important;
        }

        .btn-primary {
            background: #428BCA;
        }
    </style>
</head>
<body class="hold-transition login-page">
  <div class="login-box">
    <?php echo $template['body'] ?>
</div>
<?php echo $template['partials']['footer'] ?>

<script type="text/javascript">
    $(document).ready(function () {
        $(document).mousemove(function (event) {
            TweenLite.to($('body'), .5, {css: {'background-position': parseInt(event.pageX / 8) + "px " + parseInt(event.pageY / 12) + "px, " + parseInt(event.pageX / 15) + "px " + parseInt(event.pageY / 15) + "px, " + parseInt(event.pageX / 30) + "px " + parseInt(event.pageY / 30) + "px"}});
        });

    //Flat red color scheme for iCheck
    $('input[type="checkbox"].minimal-blue').iCheck({
        checkboxClass: 'icheckbox_minimal-blue'
    });
});
</script>

<script src="/assets/js/TweenLite.min.js"></script>

</body>
</html>
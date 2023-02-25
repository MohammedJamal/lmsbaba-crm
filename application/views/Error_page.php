<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
    <style type="text/css">
        body{
            margin: 0px;
        }
        .error-pic{
            width: 500px;
            height: auto;
            display: block;
            position: absolute;
            z-index: 55;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
        }
        .error-pic img{
            width: 100%;
            height: auto;
            display: block;
        }
        @media (max-width: 767px) {
            .error-pic{
                width: 100%;
                box-sizing: border-box;
                padding: 15px;
            }
        }
    </style>
</head>
<body>
<div class="error-pic"><a href="<?php echo base_url(); ?>"><img src="https://dev.lmsbaba.com/assets/images/logged-out-pic.png"></a></div>
</body>
</html>
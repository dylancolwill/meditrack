<?php include 'connectors/connectLogin.php'; ?>

<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>


<link rel="stylesheet" type="text/css" href="css/loginPageStyle.css" />
</head>

<body>
<form method="POST"> 
<div class="container" id="padding">
    <div class="main" id="backing">
    <div class="row">
            <div class="col-lg-12"><h1 class="mainHead">MediTrack</h1></div>
    </div>
          <div class="row">
            <input type="text" id="uName" name="uName" placeholder="Username"> 
            <input type="password" id="pswd" name="pswd"  placeholder="Password"> 
            <button type="submit" id="button">Login</button>
          </div>
        </div>
      </div> 
    </div>
</div>

</body>
</form>
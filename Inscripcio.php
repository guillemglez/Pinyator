<?php
if (!empty($_GET['id']))
{
	$cookie_name = "marrec_inscripcio";
	$cookie_value = strval($_GET['id']);
	if((isset($_COOKIE[$cookie_name])) && ($_COOKIE[$cookie_name] != $cookie_value)) 
	{		
		unset($_COOKIE[$cookie_name]);	
		setcookie($cookie_name, $cookie_value, -1, "/"); // 86400 = 1 day
	}
	else
	{
		setcookie($cookie_name, $cookie_value, time() + (86400 * 320), "/"); // 86400 = 1 day	
	}
}
?>

<html id="engrescatsApuntatHtml">
<head>
  <title>Pinyator</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="apple-touch-icon" sizes="111x192" href="icons\logo192.png">
  <link rel="icon" sizes="111x192" href="icons\logo192.png">
  <script src="llibreria/inscripcio.js?v=1.7"></script>
  <script src="llibreria/Cookies.js?v=1.1"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<?php include "$_SERVER[DOCUMENT_ROOT]/pinyator/Style.php";?>

<script>
$(document).unload = function(){window.location.reload();};
</script>

<body id="engrescatsApuntatBody">
<header id="engrescatsApuntatHeader">
    <article id="headerButtons">
        <section>
            <a href="Apuntat.php?reset=1">
                <div class="buttonLink">
                    <p>No sóc jo</p>
                </div>
            </a>
        </section>
        <section>
            <a href="Documentacio_Llista.php">
                <div class="buttonLink">
                    <p>Documentació</p>
                </div>
            </a>
        </section>
    </article>
    <article id="headerSocial">
        <section>
            <a href="https://www.youtube.com/channel/UClYwGl4Cz0G99akjFT0BKDw">
                <div id="youtubeButton" class="buttonSocial">
                </div>
            </a>
        </section>
        <section>
            <a href="https://www.instagram.com/engrescatsurl/?hl=es">
                <div id="instagramButton" class="buttonSocial">
                </div>
            </a>
        </section>
        <section>
            <a href="https://es-es.facebook.com/engrescats.delaurl?fref=mentions">
                <div id="facebookButton" class="buttonSocial">
                </div>
            </a>
        </section>
        <section>
            <a href="https://twitter.com/engrescatsurl?lang=es">
                <div id="twitterButton" class="buttonSocial">
                </div>
            </a>
        </section>
        <section>
            <a href="https://www.tiktok.com/@engrescats.url">
                <div id="ticktockButton" class="buttonSocial">
                </div>
            </a>
        </section>
    </article>
</header>


<div class = "missatge" id="missatgeM" style="display: table; height:100%;display: table-cell; vertical-align: middle;"onclick="HideMessage('missatgeM');" >
	<p>
        <b>Si et vols apuntar a un esdeveniment, només has de clicar a la "M" de Marrecs.</b>
		<br>
        <a class="ok" onclick="PonerCookie('apuntatCookie', 'missatgeM');"><b>OK</b></a>
	</p>
</div>

<script>
	iniCookie('apuntatCookie', 'missatgeM');
</script>  

<div style='position: fixed; z-index: -1; width: 90%; height: 90%;background-image: url("icons/logoEngrescats.svg");background-repeat: no-repeat;
background-attachment: fixed;  background-position: center; opacity:0.4; background-size: 27%;'>
</div>

<?php
	$topLlista = 60;

	include "$_SERVER[DOCUMENT_ROOT]/pinyator/Connexio.php";
	
	$visualitzarFites = 0;
	$visualitzarPenya = 0;
				
	$sql="SELECT FITES, PARTICIPANTS, PERCENATGEASSISTENCIA
	FROM CONFIGURACIO";

	$result = mysqli_query($conn, $sql);

	if (mysqli_num_rows($result) > 0) 
	{
		while($row = mysqli_fetch_assoc($result))
		{
			$visualitzarFites = $row["FITES"];
			$visualitzarPenya = $row["PARTICIPANTS"];
			$visualitzarPercentAssistecia = $row["PERCENATGEASSISTENCIA"];			
		}
	}
?>

<?php
if ((!empty($_GET['id'])) && (isset($_COOKIE[$cookie_name])))
{
	$Casteller_uuid = strval($_GET['id']);
	$Casteller_id=0;
	$malnom="";
	$malnomPrincipal="";
	$percentatgeAssistencia=0;

	
	$sql="SELECT C.MALNOM, C.CASTELLER_ID, C.Nom, C.Cognom_1, C.Cognom_2 
	FROM CASTELLER AS C
	WHERE C.CODI='".$Casteller_uuid."'";

	$result = mysqli_query($conn, $sql);

	if (mysqli_num_rows($result) > 0) 
	{
		while($row = mysqli_fetch_assoc($result)) 
		{
			$malnom=$row["MALNOM"];
			$malnomPrincipal=$row["MALNOM"];
			$Casteller_id = $row["CASTELLER_ID"];
            $nom=$row["Nom"]." ".$row["Cognom_1"]." ".$row["Cognom_2"];
		}
	}
}
else
{
	echo "<meta http-equiv='refresh' content='0; url=Apuntat.php'/>";	
}

function StarOff($left)
{
	echo "<div style='position:absolute; left:".$left."'><span class='fa fa-star starOff' style='font-size:30px'></span></div>";
}
	
?>

        <main id="engrescatsApuntatMain">
            <!-- PART DEL USER -->
            <article>
                <section>
                    <div id="userIMGBack">
                        <div id="userIMG"></div>
                    </div>
                </section>
                <section>
                    <div id="userName">
                        <h1><?php echo $nom; ?></h1>
                        <p>Àlies: <?php echo $malnom; ?></p>
                    </div>
                </section>
            </article>

            <?php
            /***+++++++++++ LLISTAT +++++++++++***/


            $Casteller_id_taula = $Casteller_id;
            include "$_SERVER[DOCUMENT_ROOT]/pinyator/Inscripcio_taula.php";

            $sql="SELECT DISTINCT C.CODI, C.MALNOM, C.CASTELLER_ID
            FROM CASTELLER AS CR
            INNER JOIN CASTELLER AS C ON C.FAMILIA_ID = CR.CASTELLER_ID OR C.FAMILIA2_ID = CR.CASTELLER_ID
            WHERE CR.CODI='".$Casteller_uuid."'
            ORDER BY C.MALNOM";

            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0)
            {
                while($row = mysqli_fetch_assoc($result))
                {
                    $malnom = $row["MALNOM"];
                    $Casteller_id_taula = $row["CASTELLER_ID"];
                    include "$_SERVER[DOCUMENT_ROOT]/pinyator/Inscripcio_taula.php";
                }
            }
            mysqli_close($conn);

            ?>
        </main>
   </body>
</html>
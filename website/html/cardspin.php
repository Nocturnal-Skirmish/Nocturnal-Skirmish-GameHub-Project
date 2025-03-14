<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nocturnal Skirmish - Matchmaking</title>
    <link rel="shortcut icon" href="../img/favicon.png" type="image/x-icon">
    <style><?php require "./cardspin.css" ?></style>
    <link rel="stylesheet" href="../css/universal.css">
</head>
<body>
    
<div class="perspective-container">
    <div class="perspective">
        <div class="box">
          
          <div class="face back"></div>
          <div class="face front"></div>
          
        </div>
    </div>
</div>
<div class="matchmaking-text-container">
    <h1 id="finding-match">Finding match</h1>
</div>
<button class="cancel-button" title="Cancel matchmaking" onclick="window.location.href = '../hub.php'">Cancel Matchmaking</button>
<audio src="../audio/music/IntermissionOST.mp3" autoplay style="display: none;" loop></audio>
</body>
<script>
    var matchmakingText = document.getElementById("finding-match")
    var original = matchmakingText.innerHTML;
    var dots = "";
    var dotscounter = 0;
    setInterval(function(){
        dots = dots + ".";
        dotscounter++
        matchmakingText.innerHTML = original + dots
        if (dotscounter == 4) {
            dots = "";
            dotscounter = 0;
            matchmakingText.innerHTML = original;
        }
    }, 500)
</script>
</html>
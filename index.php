
<!DOCTYPE html>
<html>
  <head>
  	<title>Snake :33333 </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
  </head>

  <?php
    Include "connexion.php";
    if ($connexion)
    {
      $requete = "select * from joueur ORDER BY score DESC LIMIT 25;";
      $resultat = $connexion-> query ($requete) or die (print_r($connexion->errorInfo()));
      echo "<table border='2'  class='table table-dark table-hover table-striped tableau' >";
      echo "<tr><td>Pseudo</td><td>Score</td></tr>\n";
  
      
      while ($ligne = $resultat-> fetch())
      {
        echo "<tr><td class='couleur'>{$ligne ['pseudo']}</td><td class='couleur'>{$ligne ['score']}</td></tr>\n";
          // echo "<option class='couleur' value=".$ligne ['pseudo'].">".$ligne ['pseudo']." ".$ligne['score']." </option>";
      }
      echo "</table>";
      $resultat-> closeCursor();
    }
                    
  ?>

  <body>
      
     
     <form action="reponse.php" method="GET">
     <div class="container ladiv">

        <div class="row">
          <div class="col">
            <input maxlength="30"  class="iptext" id="pseudo" name="lepseudo" placeholder="Entrez un pseudo" type="text">
           
          </div>
        </div>
        <div class="row ">
          <div class="col">
            <input type="submit" value="Enregister votre score" class="btn btn-violet">
            <input type="button" value="Start the game" class="btn btn-outline-success" onClick="jouer();">
            <input type="button" value="Rejouer" class="btn btn-outline-danger" onClick="window.location.reload();" >
            <input type="button" value="Pause"class="btn btn-outline-primary"  onClick="pause();" >
          </div> 
        </div>
        <div class="row ">
          <div class="col">
            <div class=" divscore ">
            <p id="score">0</p>
            </div>
             
          </div> 
        </div>
      </div>
          
      </div>
    </div>
    <input type="hidden" id="mydata" name="lescore" >
    
    </form>
   
    <canvas id="snakeboard" width="400" height="400"></canvas>


  </body>
</html>
<style>
      .tableau {
        width:30%;
      }
      #snakeboard {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: rgb(82, 76, 76);
    }
    .ladiv {
      position:absolute;
      top: 80%;
      left: 46%;
    }

    .btn-violet {
      background-color: #7916FF;
      width:200px;
      border-radius: 5px;
      border: 1px solid grey;
      color: white;
    }

    .btn-violet:hover{
      background-color: black;
      color: #7916FF;
    }
    .iptext {
      background-color:white;
      width:200px;
      height:50px;
      border: 2px solid grey;
      border-radius: 5px;
    }
    .couleur {
      color: #7916FF;

    }
    .divscore {
      width:200px;
      height:76px;
      border: 1px solid grey;
      border-radius: 3px;
      background-color: #7916FF;

    }
    #score {
      /* width:200px;
      height:76px;
      border: 1px solid grey;
      padding: 10px; */
      /* border-radius: 3px;
      background-color: #7916FF; */
      /* position:fixed;
      top:60%;
      left:60%; */
      /* z-index: 1; */
      /* transform: translate(-60%, -60%); */
      text-align:center;
      font-size: 50px;
      color:white;
    }
    body {
        background-color: rgb(37, 37, 37);
        overflow-y: hidden; 
        overflow-x: hidden; 
    }

</style>
<script>

const eatAudio = new Audio()
eatAudio.src = 'eat.mp3'
const deadAudio = new Audio()
deadAudio.src = 'dead.mp3'

const board_border = 'black';
const board_background = "#B4C4F5";
const snake_col = 'lightblue';
const snake_border = 'darkblue';

let snake = [
{x: 200, y: 200},
{x: 190, y: 200},
{x: 180, y: 200},
{x: 170, y: 200},
{x: 160, y: 200},
{x: 150, y: 200},
{x: 140, y: 200}
]

var input = document.createElement("input");
input.setAttribute('type', 'text');

let score = 0;
// True if changing direction
let changing_direction = false;
// Horizontal velocity
let food_x;
let food_y;
let dx = 10;
// Vertical velocity
let dy = 0;


// Get the canvas element
const snakeboard = document.getElementById("snakeboard");
// Return a two dimensional drawing context
const snakeboard_ctx = snakeboard.getContext("2d");

function jouer (){
  // Start game
main();
}
function pause(){
  alert('Le jeux est en pause, pour reprendre cliquez sur OK.        cliquer sur espace met automatiquement le jeux en pause !');
}
gen_food();

document.addEventListener("keydown", change_direction);

// main function called repeatedly to keep the game running
function main() {

  if (has_game_ended()) return;

  changing_direction = false;
  setTimeout(function onTick() {
  clear_board();
  drawFood();
  move_snake();
  drawSnake();
  // Repeat
  main();
}, 65)
}

// draw a border around the canvas
function clear_board() {
//  Select the colour to fill the drawing
snakeboard_ctx.fillStyle = board_background;
//  Select the colour for the border of the canvas
snakeboard_ctx.strokestyle = board_border;
// Draw a "filled" rectangle to cover the entire canvas
snakeboard_ctx.fillRect(0, 0, snakeboard.width, snakeboard.height);
// Draw a "border" around the entire canvas
snakeboard_ctx.strokeRect(0, 0, snakeboard.width, snakeboard.height);
}

// Draw the snake on the canvas
function drawSnake() {
// Draw each part
snake.forEach(drawSnakePart)
}

function drawFood() {
snakeboard_ctx.fillStyle = '#AB3F3F';
snakeboard_ctx.strokestyle = '#BC7373';
snakeboard_ctx.fillRect(food_x, food_y, 10, 10);
snakeboard_ctx.strokeRect(food_x, food_y, 10, 10);
}

// Draw one snake part
function drawSnakePart(snakePart) {

// Set the colour of the snake part
snakeboard_ctx.fillStyle = snake_col;
// Set the border colour of the snake part
snakeboard_ctx.strokestyle = snake_border;
// Draw a "filled" rectangle to represent the snake part at the coordinates
// the part is located
snakeboard_ctx.fillRect(snakePart.x, snakePart.y, 10, 10);
// Draw a border around the snake part
snakeboard_ctx.strokeRect(snakePart.x, snakePart.y, 10, 10);
}

function has_game_ended() {
for (let i = 4; i < snake.length; i++) {
  if (snake[i].x === snake[0].x && snake[i].y === snake[0].y) return true
}
const hitLeftWall = snake[0].x < 0;
const hitRightWall = snake[0].x > snakeboard.width - 10;
const hitToptWall = snake[0].y < 0;
const hitBottomWall = snake[0].y > snakeboard.height - 10;
return hitLeftWall || hitRightWall || hitToptWall || hitBottomWall

}

function random_food(min, max) {
return Math.round((Math.random() * (max-min) + min) / 10) * 10;
}

function gen_food() {
// Generate a random number the food x-coordinate
food_x = random_food(0, snakeboard.width - 10);
// Generate a random number for the food y-coordinate
food_y = random_food(0, snakeboard.height - 10);
// if the new food location is where the snake currently is, generate a new food location
snake.forEach(function has_snake_eaten_food(part) {
  const has_eaten = part.x == food_x && part.y == food_y;
  if (has_eaten) gen_food();
});
}

function change_direction(event) {
const LEFT_KEY = 37;
const RIGHT_KEY = 39;
const UP_KEY = 38;
const DOWN_KEY = 40;
const PAUSE = 32;
// Prevent the snake from reversing

if (changing_direction) return;
changing_direction = true;
const keyPressed = event.keyCode;
const goingUp = dy === -10;
const goingDown = dy === 10;
const goingRight = dx === 10;
const goingLeft = dx === -10;
if (keyPressed === LEFT_KEY && !goingRight) {
  dx = -10;
  dy = 0;
}
if (keyPressed === UP_KEY && !goingDown) {
  dx = 0;
  dy = -10;
}
if (keyPressed === RIGHT_KEY && !goingLeft) {
  dx = 10;
  dy = 0;
}
if (keyPressed === DOWN_KEY && !goingUp) {
  dx = 0;
  dy = 10;
}
if (keyPressed === PAUSE) {
  pause();
}
}

function move_snake() {
// Create the new Snake's head
const head = {x: snake[0].x + dx, y: snake[0].y + dy};
// Add the new head to the beginning of snake body
snake.unshift(head);
const has_eaten_food = snake[0].x === food_x && snake[0].y === food_y;
if (has_eaten_food) {
  // Increase score
  score += 10;
  eatAudio.play();
  
  // Display score on screen
  document.getElementById('score').innerHTML = score;
  document.getElementById('mydata').value = score;
  // Generate new food location
  gen_food();
} else {
  // Remove the last part of snake body
  snake.pop();
}
}

</script>
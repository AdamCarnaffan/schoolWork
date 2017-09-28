<html>
<head>
  <style>
    table, tr, td {
      border: 1px solid black;
      border-collapse: collapse;
    }
  
  
  </style>
</head>
<body>
<?php 

/* This assignment requires you to create a Tic Tac Toe game that plays itself.  As with common Tic Tac Toe games, player X will compete against player O,
 however in our game, the computer will be both players.  Your game must adhere to the standard rules of Tic Tac Toe including the following:

once a square is taken by either player, this square cannot be re-taken
once a win occurs, the game must end
players must alternate turns
a player's win/tie must be declared
Game output will occur in the following format:



Be certain a winning row (horizontally, diagonally and vertically) is highlighted in yellow. 
Consider making use of the following array functions in order to randomize the progress of the game:

shuffle($positionAvailable);
array_pop($PositionAvailable);
BONUS

write code that allows the user to determine the dimensions of the gameboard
write some basic AI such that the computer tries to beat itself */

class Game {
  
  public $availablePositions = [];
  public $takenPositions = [];
  public $positions = [];
  public $dimmensions;
  public $winner;
  
  public function __construct($boardSize) {
    $this->dimmensions = $boardSize; // For use in the victory function
    // Generate all positions as available
    for($x = 1; $x <= $boardSize; $x++) {
      for($y = 1; $y <= $boardSize; $y++) {
        $tempPos = new Pos($x, $y);
        array_push($this->availablePositions, $tempPos);
      }
    }
    // Shuffle all positions 
    shuffle($this->availablePositions); // To randomize which order the positions are picked in
  }
  
  public function claimPos($player) {
    $pickedPos = array_pop($this->availablePositions);
    $pickedPos->claim = $player;
    array_push($this->takenPositions, $pickedPos);
    if ($this->checkVictory($player)) {
      return true;
    } else {
      return false;
    }
  }
  
  public function checkVictory($player) {
    // Checks for a tie after all positions are taken
    if (count($this->availablePositions) < 1) {
      return true;
    } else {
      return false;
    }
  }
  
  public function displayGame() {
    $positions = array_merge($this->availablePositions, $this->takenPositions);
    $displayPositions = [];
    foreach ($positions as $pos) {
      switch ($pos->claim) {
        case 1:
          $pos->symbol = "X";
          break;
        case 2:
          $pos->symbol = "O";
          break;
        default:
          $pos->symbol = "-";
      }
      $displayPositions[$pos->x][$pos->y] = $pos->symbol;
    }
    sort($displayPositions);
    // Construct the table output
    echo "<table>";
    foreach ($displayPositions as $position) {
      echo "<tr>";
      foreach ($position as $y) {
        echo "<td>" . $y . "</td>";
      }
      echo "</tr>";
    }
    echo "</table>";
  }
}

class Pos {
  
  public $x;
  public $y;
  public $claim;
  
  public function __construct($x, $y) {
    $this->x = $x;
    $this->y = $y;
    $this->claim = 0;
  }
  
}

// Settings
$boardSize = 3;

// Start the game
$ticTacToe = new Game($boardSize);
$playing = 1;

while ($playing) { // Begin the game loop
  // Player One plays first 
  if ($ticTacToe->claimPos(1)) {
    $playing = false;
    break;
  }
  // Player Two follows
  if ($ticTacToe->claimPos(2)) {
    $playing = false;
    break;
  }
}

echo $ticTacToe->winner;

// Display
$ticTacToe->displayGame();
 ?>
</body>
</html>

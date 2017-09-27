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
        array_push($this->positionsAvailable, $tempPos);
      }
    }
    // Shuffle all positions 
    shuffle($positionsAvailable); // To randomize which order the positions are picked in
  }
  
  public function claimPos($player) {
    $position = array_pop($this->availablePositions);
    $position->claim = $player;
    array_push($this->takenPositions, $pickedPos);
    if ($this->checkVictory()) {
      return true;
    } else {
      return false;
    }
  }
  
  public function checkVictory() {
    // Loop player 1 then 2
    /*for ($player = 1; $player < 3; $player++) {
    
    }*/
    
    if (count($this->availablePositions) < 1) {
      return true;
    }
    return false;
  }
  
  public function displayGame() {
    $positions = array_merge($this->availablePositions, $this->takenPositions);
    foreach ($this->positions as $pos) {
      
    }
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
  }
  // Player Two follows
  if ($ticTacToe->claimPos(2)) {
    $playing = false;
  }
}

echo $ticTacToe->winner;

// Display
$ticTacToe->displayGame();

 ?>

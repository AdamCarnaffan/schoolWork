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
      // Generate Row / Column victory total
      $winTotal = 0;
      for ($size = 1; $size <= $this->dimmension; $size++) {
             $winTotal += $size;
            }
    // Check Column Victories
      // Generate Column arrays
      $claimCol = [];
      for ($x = 1; $x <= $this->dimmension; $x++) {
         $claimCol[$x] = [];
      }
      // Convert the position objects into array values
    foreach ($this->takenPositions as $pos) {
      if ($pos->claim == $player) {
         array_push($claimCol[$pos->x], $pos->y);
      }
    }
      // Sum each Column
      for ($x = 1; $x <= $this->dimmension; $x++) {
              $colTotal = 0;
              foreach ($claimCol[$x] as $y) {
                              $colTotal += $y;
                            }
              if ($colTotal == $winTotal) {
                              $this->winner = $player;
                              return true;
                            }
            }
    // Check Row Victories
      // Generate Column arrays
      $claimRow = [];
      for ($y = 1; $y <= $this->dimmension; $y++) {
         $claimCol[$y] = [];
      }
      // Convert the position objects into array values
    foreach ($this->takenPositions as $pos) {
      if ($pos->claim == $player) {
         array_push($claimCol[$pos->y], $pos->x);
      }
    }
      // Sum each Column
      for ($y = 1; $y <= $this->dimmension; $y++) {
              $colTotal = 0;
              foreach ($claimCol[$y] as $x) {
                              $rowTotal += $x;
                            }
              if ($rowTotal == $winTotal) {
                              $this->winner = $player;
                              return true;
                            }
            }
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
        case 3:
          $pos->symbol = "W";
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

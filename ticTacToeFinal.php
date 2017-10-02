<html>
<head>
  <style>
  table, tr, td {
    border: 1px solid black;
  }
  td {
    width: 14px;
    text-align: center;
  }
  .red {
    color: red;
  }
  .win-tile {
    background-color: yellow;
  }
  </style>
</head>
<body>
<?php 
class Game {
  
  public $availablePositions = [];
  public $takenPositions = [];
  public $positions = [];
  public $dimmensions;
  public $winner;
  public $rowWin;
  public $colWin;
  public $diagWin;
  
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
      for ($size = 1; $size <= $this->dimmensions; $size++) {
       $winTotal += $size;
      }
    // Check Row Victories
      // Generate Row arrays
      $claimRow = [];
      for ($x = 1; $x <= $this->dimmensions; $x++) {
         $claimRow[$x] = [];
      }
      // Convert the position objects into array values
    foreach ($this->takenPositions as $pos) {
      if ($pos->claim == $player) {
         array_push($claimRow[$pos->x], $pos->y);
      }
    }
      // Sum each Row
      for ($x = 1; $x <= $this->dimmensions; $x++) {
        $rowTotal = 0;
        foreach ($claimRow[$x] as $y) {
          $rowTotal += $y;
        }
        if ($rowTotal == $winTotal) {
          $this->winner = $player;
          $this->rowWin = $x;
          //echo "The win comes from Row";
          return true;
        }
      }
    // Check Column Victories
      // Generate Column arrays
      $claimCol = [];
      for ($y = 1; $y <= $this->dimmensions; $y++) {
         $claimCol[$y] = [];
      }
      // Convert the position objects into array values
    foreach ($this->takenPositions as $pos) {
      if ($pos->claim == $player) {
         array_push($claimCol[$pos->y], $pos->x);
      }
    }
      // Sum each Column
      for ($y = 1; $y <= $this->dimmensions; $y++) {
        $colTotal = 0;
        foreach ($claimCol[$y] as $x) {
          $colTotal += $x;
        }
        if ($colTotal == $winTotal) {
          $this->winner = $player;
          $this->colWin = $y;
          //echo "The win comes from Col";
          return true;
        }
      }
    // Check Diagonal Victory
    // Use $claimRow as it contains all in a certain format
    // Sum the first Diagonal
    for ($y = 1; $y <= $this->dimmensions; $y++) {
      if (!in_array($y, $claimRow[$y])) {
          break;
      } elseif ($y == $this->dimmensions) {
        $this->winner = $player;
        //echo "The win comes from Diag \ ";
        $this->diagWin = 1;
        return true;
      }
    }
    // Sum the second Diagonal
    for ($y = $this->dimmensions; $y >= 1; $y--) {
      if (!in_array($this->dimmensions - ($y-1), $claimRow[$y])) {
          break;
      } elseif ($y == 1) {
        $this->winner = $player;
        $this->diagWin = 2;
        //echo "The win comes from Diag / ";
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
  
  public function displayGame($roundNo) {
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
    ksort($displayPositions);
    for ($c = 1; $c <= count($displayPositions); $c++) {
      ksort($displayPositions[$c]);
    }
    // Construct the table output
    echo "<h4>Round " . $roundNo . ":</h4>";
    echo "<table>";
    foreach ($displayPositions as $key=>$position) {
      if ($this->rowWin == $key) {
        echo "<tr class='win-tile'>";
      } else {
        echo "<tr>";
      }
      foreach ($position as $key2=>$y) {
        if ($this->colWin == $key2 || $this->diagWin == 1 && $key == $key2 || $this->diagWin == 2 && $key == $this->dimmensions - ($key2-1)) {
          echo "<td class='win-tile'>" . $y . "</td>";
        } else {
          echo "<td>" . $y . "</td>";
        }
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
$customDims = (isset($_POST['dims'])) ? $_POST['dims'] : null;
// Make sure that the board size is never below 1
$boardSize = (isset($customDims) && $customDims > 0) ? $customDims : 3;
// Start the game
$ticTacToe = new Game($boardSize);
$playing = true;
$roundNumber = 1;
while ($playing) { // Begin the game loop
  // Player One plays first 
  if ($ticTacToe->claimPos(1)) {
    $playing = false;
    // Display
    $ticTacToe->displayGame($roundNumber);
    break;
  }
  // Display
  $ticTacToe->displayGame($roundNumber);  
  $roundNumber++;
  // Player Two follows
  if ($ticTacToe->claimPos(2)) {
    $playing = false;
    // Display
    $ticTacToe->displayGame($roundNumber);
    break;
  }
  // Display
  $ticTacToe->displayGame($roundNumber);
  $roundNumber++;
}
if (isset($ticTacToe->winner)) {
  echo "<p class='red'><b>Player " . $ticTacToe->winner . " Wins!</b></p>";
} else {
  echo "<p class='red'>It is a Tie!</p>";
}
  ?>
<form method="POST">
  <input type='number' name='dims' <?php if (isset($customDims)) echo "value='" . $customDims . "'";?>>
  <input type='submit' name='go' value='Change Dimmensions'>
</form>
</body>
</html>

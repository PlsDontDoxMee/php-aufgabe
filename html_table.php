<?php
class CSSUtils {
    public static function get_cell_class($cell) {
        $class = "";
        if ($cell->isPrime) {
            if ($cell->connectedToTop || $cell->connectedToBottom) {
                $class .= "connected_prime";
            } else {
                $class .= "prime";
            }

            if ($cell->connectedToTop) {
                $class .= " connected_top";
            }

            if ($cell->connectedToBottom) {
                $class .= " connected_bottom";
            }
        }

        return $class;
    }
}

class MathUtils {
    public static function is_prime($n) {
        if ($n == 0 || $n == 1) { return false; }
    
        for ($i = 2; $i <= $n / 2; $i++) {
            if ($n % $i == 0) {
                return false;
            } 
        }
    
        return true;
    }
}

class Cell {
    public $text;
    public $isPrime;

    public $connections;
    public $connectedToTop;
    public $connectedToBottom;

    public function __construct(
        string $text, 
        string $isPrime
    ) {
        $this->text = $text;
        $this->isPrime = $isPrime;

        $this->connections = 0;
        $this->connectedToTop = false;
        $this->connectedToBottom = false;
    }

    public function connect_bottom() {
        $this->connectedToBottom = true;
        $this->connections++;
    }

    public function connect_top() {
        $this->connectedToTop = true;
        $this->connections++;
    }
}

class Table { 
    public $cell_rows;

    public $prime_count;
    public $prime_pairs_count;
    public $prime_triples_count;

    private function create_cell_rows($width, $height, $start) {
        $this->cell_rows = [];

        for ($rowNumber = 0; $rowNumber < $height; $rowNumber++) {
            $row = [];

            for ($colNumber = 0; $colNumber < $width; $colNumber++) {
                $number = $start + $colNumber + $rowNumber*$width;
                
                $isPrime = MathUtils::is_prime($number);
                $cell = new Cell($number, $isPrime);

                if ($isPrime && $rowNumber > 0) {
                    $cellAbove = $this->cell_rows[$rowNumber - 1][$colNumber];

                    if ($cellAbove->isPrime) {
                        $cellAbove->connect_bottom();
                        $cell->connect_top();
                    }
                }

                array_push($row, $cell);
            }

            array_push($this->cell_rows, $row);
        }
    }

    public function count_primes($width, $height) {
        $single_connections = 0;
        $double_connections = 0;

        for ($rowNumber = 0; $rowNumber < $height; $rowNumber++) {
            for ($colNumber = 0; $colNumber < $width; $colNumber++) {
                $cell = $this->cell_rows[$rowNumber][$colNumber];
                if ($cell->isPrime) {
                    $this->prime_count++;

                    switch ($cell->connections) {
                        case 2: $double_connections++; break;
                        case 1: $single_connections++; break;
                        default: break;
                    }
                }
            }
        }

        $this->prime_triples_count = $double_connections;
        $this->prime_pairs_count = ($single_connections / 2) - $this->prime_triples_count;
    }

    public function __construct($width, $height, $start) {
        $this->create_cell_rows($width, $height, $start);
        $this->count_primes($width, $height);
    }
}
?>


<html>
    <head>
        <style>
            table {
                border: solid grey 1px;
                border-spacing: unset;

                td {
                    width: 15px;
                    height: 40px;
                    padding: 10px; 
                    text-align: center; 
                    border: inset 3px; 
                }
            }

            .prime {
                background: yellow;
            }
            .connected_prime {
                background: red;
            }
            .connected_top {
                border-top: none;
            }
            .connected_bottom {
                border-bottom: none;
            }

            .prime_count_containers {
                margin-top: 20px; 
                display: flex; 
                align-items: start;
            }
            .prime_count_container {
                display: flex;
                margin-right: 8px;
            }

            .fake_table {
                margin-right: 5px;
            }
        </style>
    </head>
    <body>
        <?php $table = new Table(4, 10, 0) ?>
        
        <table> 
            <?php foreach($table->cell_rows as $row): ?>
                <tr>
                    <?php foreach($row as $cell): ?>
                        <td class="<?=CSSUtils::get_cell_class($cell)?>">
                            <?= $cell->text ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </table>
        
        <div class="prime_count_containers">
            <div class="prime_count_container">
                <table class="fake_table">
                    <td class="prime"></td>
                </table>
                <?=$table->prime_count?> Primzahlen
            </div>

            <div class="prime_count_container">
                <table class="fake_table">
                    <tr>
                        <td class="connected_prime"></td>
                    </tr>
                    <tr>
                        <td class="connected_prime"></td>
                    </tr>
                </table>
                <?=$table->prime_pairs_count?> Primzahlenpaare
            </div>

            <div class="prime_count_container">
                <table class="fake_table">
                    <tr>
                        <td class="connected_prime"></td>
                    </tr>
                    <tr>
                        <td class="connected_prime"></td>
                    </tr>
                    <tr>
                        <td class="connected_prime"></td>
                    </tr>
                </table>
                <?=$table->prime_triples_count?> Primzahlendreier
            </div>
            
        </div>
    </body>
</html>
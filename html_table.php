<?php
function is_prime($n) {
    if ($n == 0) { return false; }
    if ($n == 1) { return false; }

    for ($i = 2; $i <= $n / 2; $i++) {
        if ($n % $i == 0) {
            return false;
        } 
    }

    return true;
}

class Table {
    public $html;
    public $prime_count;

    function add_html($html) {
        $this->html .= $html;
    }

    function __construct() {
        $this->prime_count = 0;
        $this->html = "";
        
        $this->add_html("<table>");
        for ($row = 0; $row < 10; $row++) {
            $this->add_html("<tr>");
            for ($col = 0; $col < 10; $col++) {
                $i = $row * 10 + $col;
    
                $is_prime = is_prime($i);
                if ($is_prime) {
                    $this->prime_count++;
                    $this->add_html("<td class=\"prime\">$i</td>");
                } else {
                    $this->add_html("<td>$i</td>");
                }
            }
            $this->add_html("</tr>");
        }
        $this->add_html("</table>");
    }
}
?>


<html>
    <head>
        <style>
            table {
                border: solid black 1px;

                td {
                    width: 15px;
                    height: 25px;
                    padding:5px; 
                    text-align:center; 
                    border: solid black 1px; 
                }
            }
            
            .prime {
                background: yellow;
            }
        </style>
    </head>
    <body>
        <?php $table = new Table()?>

        <div>
            <?=$table->html?>
        </div>
        
        <div style="margin-top: 20px; display: flex; align-items: center;">
            <table style="margin-right: 5px;"><td class="prime"></td></table>
            <?=$table->prime_count?> Primzahlen
        </div>
    </body>
</html>
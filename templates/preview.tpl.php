Examine data:
<p>
<?php
    echo $count." orders to export.";
    if ($count > 0) echo " Here are some of them:";
?>
</p>
<table>
<?php
    while( $row = $this->modx->db->getRow( $exportPreview ) ) {
        echo "<tr>";
            echo "<td>".$row['id']."</td><td>".$row['short_txt']."</td><td>".$row['price']."</td><td>".$row['userid']."</td>";
        echo "</tr>";
    }
?>
</table>
<?php
    if ($count>0) 
        echo '<input type="submit" onclick="postForm(\'export\')" value="Export" />';
?>

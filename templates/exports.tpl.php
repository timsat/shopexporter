Choose export:
<select name="exportId">
    <option value="">New export</option>
<?php
    while($row = $this->modx->db->getRow($exports)){
        echo "<option value=".$row['id'].">".$row['date']."</option>";
    }
?>
</select>
<input type="submit" onclick="postForm('showPreview')" value="Preview" />

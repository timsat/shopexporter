<span style="font-family:courier,monospace,serif; font-size:13px;">
<!--order_no*, order_date, name*, address_1*, address_2, address_3, city*, state*, zip*, country*, email, phone*, company_name, is_commercial, shipping_method, sku*, quantity*, hold, hold_reason-->
[email],[order_no],[quantity],[order_date],[name],[address_1],[address_2],[address_3],[city],[state],[sku],[zip],[country],[phone],[shipping_method],[is_commercial],[company_name],[hold],[hold_reason]<br/>
<?php
    $fl = false;
    while( $row = $this->modx->db->getRow( $exportData ) ) {
        $info = split(',', $row['short_txt']);
        $qinfo = split('{', $row['content']);
        $qinfo = split(';', $qinfo[2]);
        $quantity = 'quantity';
//        $date = split('Y-m-d G:i:s',$row['date']);
        $d = split(' ',$row['date']);
        $d = split('-',$d[0]);
        $date = $d[1].'/'.$d[2].'/'.$d[0];
        foreach($qinfo as $f) {
            $pos = strpos($f, 's:1:"');
            if ($pos !== false) {
                $t = split(':', $f);
                $quantity = trim($t[2],'"');
                break;
            }
        }
        $a = parseAddress($row['short_txt']);
        $address = trans(str_replace(',',';',$row['address']));
        $address2 = trans(str_replace(',',';',$row['address2']));
        $country = isset($a['country']) ? $a['country'] :'country';
        $name = str_replace(',',';',trans($info[0]));
        if(isset($row['phone'])&&strlen($row['phone'])>0) {
            $phone = trans($row['phone']);
        } else {
            if(isset($row['message'])&&strlen($row['message'])>0) {
                $m = array();
                if (preg_match_all('/[\+]{0,1}(\(\d{2,4}\)[0-9\-\ ]{6,})|([0-9\-\ ]{10,})/', $row['message'], $m))
                    $phone = implode('; ', $m[0]);
                else
                    $phone = '';
            }
        }
        $phone = str_replace(',',';',$phone);
        $email = str_replace(',',';',$row['email']);
        $city = str_replace(',',';',trans($row['city']));
        echo "<nobr>".$email.",PXL-0".$row['id'].",".$quantity.",".$date.",".$name.",".$address.",".$address2.",,".$city.",,Lightpack,".trans($row['postcode']).",".$country.",".$phone.",,,,,</nobr><br/>";
    }
?>
</span>

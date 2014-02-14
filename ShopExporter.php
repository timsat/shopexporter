<?php
class ShopExporter {

    private $dbprefix;
    private $mod_page;
    private $action;
    private $shopkeeper_table;
    private $exportsTable;
    private $exportedOrdersTable;
    private $modx;
    
    function __construct($modx) {

        $this->modx = $modx;
        $this->dbprefix = $modx->db->config['table_prefix'];
        $this->mod_page = "index.php?a=112&id=".$_GET['id'];
        $this->action = isset($_POST['action']) ? $_POST['action']: ( isset($_GET['action']) ? $_GET['action'] : 'showExports' );
        $this->shopkeeper_table  = $this->dbprefix."manager_shopkeeper";
        $this->exportsTable = $this->dbprefix.'shk_exports';
        $this->exportedOrdersTable = $this->dbprefix.'shk_exportedOrders';
    }

    private function getPartSqlWithEID($exportId) {
        return "from `$this->shopkeeper_table` sk inner join `$this->exportedOrdersTable` e on sk.id=e.shkId where e.`shkExportId`=".$exportId." and `status`=6 and content like '%i:0;s:3:\"145\"%'";
    }

    private function getPartSqlWithoutEID() {
        return "from `$this->shopkeeper_table` sk left join `$this->exportedOrdersTable` e on sk.id=e.shkId where e.`id` is null and `status`=6 and content like '%i:0;s:3:\"145\"%'";
    }

    public function processRequest() {
        include "templates/header.tpl.php";
        //include "templates/debug.tpl.php";
        switch($this->action) {

        case 'showExports':
            $exports = $this->modx->db->query("select * from `$this->exportsTable` order by `date` desc");
            include "templates/exports.tpl.php";
        break;

        case 'showPreview':
            if (isset($_POST['exportId']) && strlen($_POST['exportId'])>0) {
                $_SESSION['exportId']=$_POST['exportId'];
                $fromWhere = $this->getPartSqlWithEID($_SESSION['exportId']);
            } else {
                unset($_SESSION['exportId']);
                $fromWhere = $this->getPartSqlWithoutEID();
            }
            $ordersCount = $this->modx->db->query("select count(sk.`id`)".$fromWhere);
            $count = $this->modx->db->getRow($ordersCount, 'num');
            $count = $count[0];
            $exportPreview = $this->modx->db->query("select sk.* ".$fromWhere." order by sk.id desc limit 5");
            include "templates/preview.tpl.php";
        break;

        case 'export':
            if (!isset($_SESSION['exportId']) || $_SESSION['exportId'] == '') {
                $this->modx->db->query("insert into `$this->exportsTable` values (NULL, NOW())");
                $exportId = $this->modx->db->getInsertId();
                if (isset($exportId)) {
                    $partSql = $this->getPartSqlWithoutEID();
                    $this->modx->db->query("insert into `$this->exportedOrdersTable` (`shkExportId`,`shkId`) select ".$exportId." as `shkExportId`, sk.id as `shkId` ".$partSql);
                }
            } else {
                $exportId = $_SESSION['exportId'];
            }
            
            $fromWhere = $this->getPartSqlWithEID($exportId);
            $exportData = $this->modx->db->query("select sk.* ".$fromWhere." order by sk.id desc");
            
            include "templates/exported.tpl.php";
            unset($_SESSION['exportId']);
        break;

        //Installation of the module (create tables in the DB)
        case 'install':
          install($this->exportsTable, $shopkeeper_table);
          //include "templates/installed.tpl.php";
        break;

        }

        include "templates/footer.tpl.php";
    }
}
?>

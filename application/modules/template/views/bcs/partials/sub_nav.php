<?php
/**
 * Created by PhpStorm.
 * User: RamS-NSET
 * Date: 2/21/2017
 * Time: 2:25 PM
 *
 * @var $sub_nav
 */
?>
<!--
<nav style="display: inline"><b><?= $module?> | 	</b>Dashboard | Checklist Groups | Checklist Items  | Submissions | Reports </nav>
-->
<nav style="display: inline; background-color: lightgoldenrodyellow"><a href="<?=base_url()?>">Home | </a><b><?= $module?> 		 </b>
	<?php foreach($sub_nav as $nav):?>
	|	<a href="<?= $nav['route']?>"><?= $nav['label']?></a>
	<?php endforeach;?>
</nav>


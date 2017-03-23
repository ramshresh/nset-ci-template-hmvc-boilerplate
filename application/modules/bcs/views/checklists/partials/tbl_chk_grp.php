<?php
/**
 * Created by PhpStorm.
 * User: RamS-NSET
 * Date: 2/21/2017
 * Time: 3:15 PM
 *
 * @var $chk_groups
 * @var $chk_items
 */
?>
<style>
	.cd-accordion-menu input[type=checkbox] {
		/* hide native checkbox */
		position: absolute;
		opacity: 0;
	}

	.cd-accordion-menu label, .cd-accordion-menu a {
		position: relative;
		/*display: block;*/
		/*padding: 18px 18px 18px 64px;*/
		/*background: #4d5158;*/
		/*box-shadow: inset 0 -1px #555960;*/
		/*color: #ffffff;*/
		font-size: 1.6rem;
	}

	.cd-accordion-menu ul {
		/* by default hide all sub menus */
		display: none;
	}

	.cd-accordion-menu input[type=checkbox]:checked + label + ul,
	.cd-accordion-menu input[type=checkbox]:checked + label:nth-of-type(n) + ul {
		/* use label:nth-of-type(n) to fix a bug on safari (<= 8.0.8) with multiple adjacent-sibling selectors*/
		/* show children when item is checked */
		display: block;
	}
</style>
<div class="row">
			<label for="b_type">Building Type</label>
			<select class="form-control" id="b_type">
				<option value=""> All</option>
				<?php foreach ($chk_groups as $chk_group): ?>
					<option value="<?= $chk_group->chk_grp_id ?>"><?= $chk_group->label ?></option>
				<?php endforeach; ?>
			</select>

			<label for="chk_grp_select">Checklist Group</label>
			<select class="form-control" id="chk_grp_select">
				<option value=""> All</option>
				<?php foreach ($chk_groups as $chk_group): ?>
					<option value="<?= $chk_group->chk_grp_id ?>"><?= $chk_group->label ?></option>
				<?php endforeach; ?>
			</select>
</div>
<div class="row">
	<div class="form-horizontal">
		<h5>Checklist Items</h5>
		<fieldset class="border"  id="chk_itm_list">
		</fieldset>
	</div>
</div>


<style>
	fieldset.border {
		border: 1px groove #ddd !important;
		padding: 0 1.4em 1.4em 1.4em !important;
		margin: 0 0 1.5em 0 !important;
		-webkit-box-shadow:  0px 0px 0px 0px #000;
		box-shadow:  0px 0px 0px 0px #000;
	}
</style>


<script>
	'use strict';
	var chk_grps = <?php echo json_encode($chk_groups)?>;
	var chk_items = <?php echo json_encode($chk_items)?>;
	//If parent option is changed
	function makeChkItems(selector, array_list) {
		$(selector).html(""); //reset child options
		$(array_list).each(function (i) { //populate child options
			$(selector).append('<li style="font-size:x-small;list-style-type: none;">['+array_list[i].chk_itm_code+'] '+array_list[i].label+'</li>');
		});
	}

	function filterChkItems(chk_items, chk_grp) {
		var arr = [];
		if (typeof  chk_grp == 'undefined' || chk_grp == '') {
			arr = arr.concat(vdcs);
		} else if (chk_grp instanceof Array) {
			chk_grp.forEach(function (d) {
				arr = arr.concat(chk_items.filter(function (el) {
					return el.chk_grp_id == d;
				}));
			});
		} else {
			arr = arr.concat(chk_items.filter(function (el) {
				return el.chk_grp_id == chk_grp;
			}));
		}
		return arr;
	}

	$("#chk_grp_select").change(function () {
		var chk_grp = $(this).val(); //get option value from parent
		var chk_itm_select = filterChkItems(chk_items, chk_grp);
		makeChkItems('#chk_itm_list', chk_itm_select);
	});

</script>
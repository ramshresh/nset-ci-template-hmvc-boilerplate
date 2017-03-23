<?php
/**
 * @var $choices
 * @var $checklists
 * @var $submissions
 *
 * $data['choices']['building_types']
 * $data['choices']['construction_statuses']
 * $data['choices']['districts']
 * $data['choices']['vdc_municipalities']
 * $data['choices']['districts_snm']
 * $data['choices']['vdc_municipalities_snm']
 *
 * $data['checklists']['chk_groups']
 * $data['checklists']['chk_items']
 *
 * $data['submissions']['oldest_date']
 * $data['submissions']['recent_date']
 */
?>

<style>
	.user {
		border: 1px solid #ccc;
		background-color: white;
		padding: 10px;
		margin-bottom: 15px;
	}
</style>
<div id="rpt" class="well">
	<div id="rptHeader">

	</div>
	<div id="rptOverview">
	</div>
	<div id="rptBtypes">
		<div id="rptBtypesSummary">

		</div>
		<!--		The following section repeats for each building Types	-->
		<div id="rptBtype">
			<div id="bType-1"></div>
		</div>
	</div>
</div>

<script>
	Vue.component('rpt-user', {
		data: function () {
			return {
				users: [
					{username: 'Ram Shrestha'},
					{username: 'Subina Shahi'}
				]
			};
		},
		template: '<div><div class="user" v-for="user in users"><p>Username: {{user.username}}</p></div></div>'
	});
	var rpt = new Vue({
		el: '#rpt',
		data: {
			title: 'Hello Vue!'
		}
	});
</script>
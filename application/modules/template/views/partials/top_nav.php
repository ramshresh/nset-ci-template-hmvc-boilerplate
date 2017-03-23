<link rel="stylesheet" href="<?= base_url() . 'assets/css/top_nav.css' ?>">
<link rel="stylesheet" href="<?= base_url() . 'assets/css/top_nav-admin_menu.css' ?>">
<ul id="menu">
	<li><a href="#">Home</a></li>
	<li><a href="#">Activities/Events</a>
		<ul>
			<li><a href="<?= base_url() . 'mt/dashboard' ?>">Mason Training</a></li>
			<li><a href="<?= base_url() . 'ort/dashboard' ?>">Orientation</a></li>
			<li><a href="#">Mason-TOT</a></li>
			<li><a href="#">OJT</a></li>
		</ul>


	</li>
	<li><a href="#">Surveys</a>
		<ul>
			<li><a href="<?= base_url() . 'bcs/dashboard' ?>">Building Compliance Survey</a></li>
		</ul>
	</li>

	<?php if ($this->session->userdata('role') == 'superadmin') { ?>
		<li><a href="#">Admin</a>
			<ul>
				<li><a href="../Home/costSharing">Cost Sharing</a></li>
				<li><a href="../Home/eventOrganizer">Organiser Entry</a></li>
				<li><a href="../Home/newcourses">Event Entry</a></li>
				<li><a href="../Home/newCoverage">Coverage Entry</a></li>
			</ul>
		</li>
	<?php } ?>
</ul>

<div style="height:55px;width:500px;position:fixed;left:-484px; margin-top:0px;z-index:100" class="table_a">
	<table style="width:500px;height:55px">
		<tr>
			<td style="background:#ccc;">

				<table class="dataListing" width="100%" cellspacing="0" cellpadding="5" border="0">
					<tbody>
					<tr>
						<th width="5%" align="center">#</th>
						<th width="20%" align="left">Name</th>
						<th width="20%" align="left">User Name</th>
						<th width="20%" align="left">Logged in as</th>
						<th width="25%" align="left">Previous login</th>
						<th rowspan="2" class="uppercase nicefont size11">
							<a style="background:transparent" href="../Home/logout"><i class="icon-leaf"></i> Logout</a>
						</th>
					</tr>
					<tr>
						<td align="center">1</td>
						<td align="left"><?= $this->session->userdata('fullname') ?></td>
						<td align="left"><?= $this->session->userdata('username') ?></td>
						<td align="left"><?= $this->session->userdata('role') ?></td>
						<td align="left"><?= $this->session->userdata('prevlogin') ?></td>
					</tr>
					</tbody>
				</table>


			</td>
			<td style="width:16px; border-bottom-right-radius: 10px; border-top-right-radius: 10px; color:#fff"
				class="btn-info right_a"><img class="table_img_a"
											  src="<?= base_url() . 'assets/img/arrow_rt.png' ?>"
											  style="margin:0;padding:0"/>
			</td>
		</tr>
	</table>
	<?php if ($this->session->userdata('role') == 'superadmin') { ?>
		<div class="upperdiv">
			<div style="padding:10px 0 0 20px;">
				<img src="<?= base_url() . 'assets/img/cms.png' ?>"/>
				<h5 class="inline-block">Superadmin control Panel </h5>
				<!--hr style="background:url('../img/hr.jpg');margin:5px 0 5px 0" /-->
				<ul class="adminmenu">
					<li class="nicefont uppercase size11"><b class="icon-picasa"></b><a href="../Home/sliderManager">
							manage gallery(slider)</a></li>
					<li class="nicefont uppercase size11"><b class="icon-picasa"></b><a href="../Home/help"> manage help
							content</a></li>
				</ul>
			</div>
		</div>
		<div class="curve">
		</div>
		<div style="clear:both"></div>
		<div class="lowerdiv">
			<div style="padding:3px 0 0 20px ;">
				<ul class="adminmenu">
					<li class="nicefont size11"><b class="icon-wrench"></b> <a href="../Control/dc">Manage Deleted
							Course <?php if (isset($deleted_count)) echo '(' . $deleted_count[2] . '/' . $deleted_count[3] . ')'; ?></a>
					</li>
					<li class="nicefont size11"><b class="icon-wrench"></b> <a href="../Control/de">Manage Deleted
							Events <?php if (isset($deleted_count)) echo '(' . $deleted_count[1] . ')'; ?></a></li>
					<li class="nicefont size11"><b class="icon-wrench"></b> <a href="../Control/dp">Manage Deleted
							People <?php if (isset($deleted_count)) echo '(' . $deleted_count[0] . ')'; ?></a></li>
					<li class="nicefont size11"><b class="icon-wrench"></b> <a href="../Home/userManagement">Manage
							user </a></li>
				</ul>
			</div>
		</div>
		<div class="curve1">
		</div>
		<div style="clear:both"></div>
		<div class="curve2">
		</div>
	<?php } ?>
</div>
<script src="<?= base_url() . 'assets/js/top_nav-admin_menu.js'; ?>"></script>


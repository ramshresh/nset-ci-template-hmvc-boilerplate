<div class="row">
	<div class="panel panel-info">
		<div class="panel-body">
			<div class="col-lg-4">
				<div class="well">
					<?php $this->load->view('partials/tbl_chk_grp'); ?>
				</div>
			</div>
			<div class="col-md-8">
				<div class="well"></div>
			</div>
		</div>
	</div>
</div>


<div id="editor">
	<textarea :value="input" @input="update"></textarea>
	<div v-html="compiledMarkdown"></div>
</div>

<script>
	new Vue({
		el: '#editor',
		data: {
			input: '# hello'
		},
		computed: {
			compiledMarkdown: function () {
				return marked(this.input, { sanitize: true })
			}
		},
		methods: {
			update: _.debounce(function (e) {
				this.input = e.target.value
			}, 300)
		}
	})
</script>

<style>
	html, body, #editor {
		margin: 0;
		height: 100%;
		font-family: 'Helvetica Neue', Arial, sans-serif;
		color: #333;
	}

	textarea, #editor div {
		display: inline-block;
		width: 49%;
		height: 100%;
		vertical-align: top;
		box-sizing: border-box;
		padding: 0 20px;
	}

	textarea {
		border: none;
		border-right: 1px solid #ccc;
		resize: none;
		outline: none;
		background-color: #f6f6f6;
		font-size: 14px;
		font-family: 'Monaco', courier, monospace;
		padding: 20px;
	}

	code {
		color: #f66;
	}
</style>

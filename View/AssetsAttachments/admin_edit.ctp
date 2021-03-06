<?php

$this->extend('/Common/admin_edit');

$this->Html
	->addCrumb('', '/admin', array('icon' => 'home'))
	->addCrumb(__d('croogo', 'Attachments'), array('plugin' => 'assets', 'controller' => 'assets_attachments', 'action' => 'index'))
	->addCrumb($this->data['AssetsAttachment']['title'], '/' . $this->request->url);

if ($this->layout === 'admin_popup'):
	$this->append('title', ' ');
endif;

$formUrl = array('controller' => 'assets_attachments', 'action' => 'edit');
if (isset($this->request->query)) {
	$formUrl = array_merge($formUrl, $this->request->query);
}
echo $this->Form->create('AssetsAttachment', array(
	'url' => $formUrl,
));

?>
<div class="row-fluid">
	<div class="span8">

		<ul class="nav nav-tabs">
		<?php
			echo $this->Croogo->adminTab(__d('croogo', 'Attachment'), '#attachment-main');
			echo $this->Croogo->adminTabs();
		?>
		</ul>

		<div class="tab-content">

			<div id="attachment-main" class="tab-pane">
			<?php
				echo $this->Form->input('id');

				$fileType = explode('/', $this->data['AssetsAsset']['mime_type']);
				$fileType = $fileType['0'];
				$path = $this->data['AssetsAsset']['path'];
				if ($fileType == 'image') {
					$imgUrl = $this->AssetsImage->resize($path, 200, 300,
						array('adapter' => $this->data['AssetsAsset']['adapter']),
						array('class' => 'img-polaroid')
					);
				} else {
					$imgUrl = $this->Html->image('/croogo/img/icons/' . $this->Filemanager->mimeTypeToImage($this->data['AssetsAttachment']['mime_type'])) . ' ' . $this->data['AssetsAttachment']['mime_type'];
				}
				echo $this->Html->link($imgUrl, $this->data['AssetsAsset']['path'], array(
					'class' => 'thickbox pull-right',
				));
				$this->Form->inputDefaults(array(
					'class' => 'span6',
					'label' => false,
				));
				echo $this->Form->input('title', array(
					'label' => __d('croogo', 'Title'),
				));
				echo $this->Form->input('excerpt', array(
					'label' => __d('croogo', 'Caption'),
				));

				echo $this->Form->input('file_url', array(
					'label' => __d('croogo', 'File URL'),
					'value' => Router::url($this->data['AssetsAsset']['path'], true),
					'readonly' => 'readonly')
				);

				echo $this->Form->input('file_type', array(
					'label' => __d('croogo', 'Mime Type'),
					'value' => $this->data['AssetsAsset']['mime_type'],
					'readonly' => 'readonly')
				);

			?>
			</div>

			<?php echo $this->Croogo->adminTabs(); ?>
		</div>
	</div>

	<div class="span4">
	<?php
		$redirect = array('action' => 'index');
		if ($this->Session->check('Wysiwyg.redirect')) {
			$redirect = $this->Session->read('Wysiwyg.redirect');
		}
		if (isset($this->request->query['model'])) {
			$redirect = array_merge(
				array('action' => 'browse'),
				array('?' => $this->request->query)
			);
		}
		echo $this->Html->beginBox(__d('croogo', 'Publishing')) .
			$this->Form->button(__d('croogo', 'Save')) .
			$this->Html->link(
				__d('croogo', 'Cancel'),
				$redirect,
				array('class' => 'cancel', 'button' => 'danger')
			) .
			$this->Html->endBox();
	?>
	</div>
</div>
<?php echo $this->Form->end(); ?>
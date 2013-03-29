<?
defined('C5_EXECUTE') or die("Access Denied.");
$form = Loader::helper('form');
$types = array();
$pagetypes = CollectionType::getList();
foreach($pagetypes as $ct) {
	$types[$ct->getCollectionTypeID()] = $ct->getCollectionTypeName();
}
$targetTypes = ComposerTargetType::getList();

$cmpName = '';
$cmpCTID = array();
$token = 'add_composer';
if (is_object($composer)) {
	$token = 'update_composer';
	$cmpName = $composer->getComposerName();
	$selectedtypes = $composer->getComposerPageTypeObjects();
	foreach($selectedtypes as $ct) {
		$cmpCTID[] = $ct->getCollectionTypeID();
	}
}
?>

<?=Loader::helper('validation/token')->output($token)?>
	<div class="control-group">
		<?=$form->label('cmpName', t('Composer Name'))?>
		<div class="controls">
			<?=$form->text('cmpName', $cmpName, array('class' => 'span5'))?>
		</div>
	</div>

	<div class="control-group">
		<?=$form->label('cmpCTID', t('Page Type'))?>
		<div class="controls">
			<?=$form->selectMultiple('cmpCTID', $types, $cmpCTID, array('class' => 'span5'))?>
		</div>
	</div>

	<div class="control-group">
		<?=$form->label('cmpTargetTypeID', t('Publish Method'))?>
		<div class="controls">
			<? for ($i = 0; $i < count($targetTypes); $i++) {
				$t = $targetTypes[$i];
				if (!is_object($composer)) {
					$selected = ($i == 0);
				} else {
					$selected = $composer->getComposerTargetTypeID();
				}
				?>
				<label class="radio"><?=$form->radio('cmpTargetTypeID', $t->getComposerTargetTypeID(), $selected)?> <span><?=$t->getComposerTargetTypeName()?></label>
			<? } ?>
		</div>
	</div>

	<? foreach($targetTypes as $t) { 
		if ($t->hasOptionsForm()) {
		?>

		<div style="display: none" data-composer-target-type-id="<?=$t->getComposerTargetTypeID()?>">
			<? $t->includeOptionsForm($composer);?>
		</div>

	<? }

	} ?>

<script type="text/javascript">
$(function() {
	$('#cmpCTID').chosen();
	$('input[name=cmpTargetTypeID]').on('click', function() {
		$('div[data-composer-target-type-id]').hide();
		var cmpTargetTypeID = $('input[name=cmpTargetTypeID]:checked').val();
		$('div[data-composer-target-type-id=' + cmpTargetTypeID + ']').show();
	});
	$('input[name=cmpTargetTypeID]:checked').trigger('click');
});
</script>
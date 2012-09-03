<form name="content_edit" method="post" action="?dispatch=save_content">
    <input type="hidden" name="key" value="<?= $c->getKey() ?>">

    <h3>Inhalt (<?= $c->getKey() ?>) bearbeiten</h3>
    
    <div>
        <label for="content_txt" style="font-weight: bold;">Inhalt:</label>
        <textarea name="content_txt" id="content_txt" class="mceAdvanced" style="height:400px; width:100%;"><?= $c->getContentTxt() ?></textarea>
    </div>

    <div style="text-align: center;">
        <input type="image" <?=makeButton('speichern','src')?>>
        <a href="<?= $GLOBALS['BASE_URI'] ?>">
            <img <?=makeButton('abbrechen','src')?> alt="abbrechen">
        </a>
    </div>
</FORM>

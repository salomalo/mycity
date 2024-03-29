<!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fade">
        <td>
            <span class="preview"></span>
        </td>
        <td>
            <p class="name">{%=file.name%}</p>
            <strong class="error text-danger"></strong>
        </td>
        <td>
            <p class="size">Processing...</p>
            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
        </td>
        <td>
            {% if (!i && !o.options.autoUpload && (file.type == "image/png" || file.type == "image/jpeg" || file.type == "image/gif")) { %}
                <button class="btn btn-primary start" disabled>
                    <i class="glyphicon glyphicon-upload"></i>
                    <span><?= Yii::t('business', 'Start') ?></span>
                </button>
            {% } else if (file.type != "image/png" && file.type != "image/png" && file.type != "image/gif") { %}
                <span><?= Yii::t('business', 'The_selected_type_not_supported')?></span>
                {% continue; %}
            {%}%}
            {% if (!i) { %}
                <button class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span><?= Yii::t('business', 'Cancel') ?></span>
                </button>
            {% } %}
        </td>
    </tr>
{% } %}
</script>